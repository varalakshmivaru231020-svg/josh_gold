<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\Commission;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\WhatsAppService;
use Illuminate\Support\Str;
use Razorpay\Api\Api;

class OrderController extends Controller
{
    private $razorpay;

    public function __construct()
    {
        $this->razorpay = new Api(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));
    }

    // STEP 1: Checkout without shipping info
    public function checkout(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
            'referral_code' => 'nullable|exists:agents,referral_code'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $quantity = $request->quantity;
        $unitPrice = $product->price;
        $subtotal = $quantity * $unitPrice;
        $discount = 0;

        if ($request->referral_code) {
            $discount = 25*$request->quantity;// 10% discount
        }

        $total = $subtotal - $discount;

        // Store cart in session WITHOUT shipping info
        session([
            'cart' => [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'referral_code' => $request->referral_code,
                'checkout_step' => 1 // Track step
            ]
        ]);

        // Show checkout WITHOUT shipping fields
        return view('orders.checkout-step1', compact('product', 'quantity', 'subtotal', 'discount', 'total'));
    }

    // STEP 1: Create Razorpay order (payment initiation)
    public function createPayment(Request $request)
    {
        $cart = session('cart');
        if (!$cart) {
            return redirect()->route('products.index');
        }

        $product = Product::findOrFail($cart['product_id']);

        $orderData = [
            'receipt' => 'receipt_' . time(),
            'amount' => ((int)$cart['total']) * 100, // Razorpay expects amount in paise
            'currency' => 'INR',
            'payment_capture' => 1,
            'notes' => [
                'product_id' => $product->id,
                'quantity' => $cart['quantity'],
                'referral_code' => $cart['referral_code'] ?? null
            ]
        ];

        try {
            $razorpayOrder = $this->razorpay->order->create($orderData);
            
            // Store razorpay order ID in session
            session(['razorpay_order_id' => $razorpayOrder['id']]);
            
            return response()->json([
                'success' => true,
                'order_id' => $razorpayOrder['id'],
                'amount' => $cart['total'] * 100,
                'name' => config('app.name'),
                'description' => $product->name,
                'theme' => [
                    'color' => '#F37254'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $cart['total'] * 100,
                'amount' => $cart['total'] * 100,
            ]);
        }
    }

    // STEP 2: Handle payment verification (without shipping info)
    public function verifyPayment(Request $request)
    {
        $cart = session('cart');
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please start over.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'razorpay_payment_id' => 'required',
            'razorpay_order_id' => 'required',
            'razorpay_signature' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid payment data'
            ]);
        }

        try {
            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            ];

            // Verify payment signature
            $this->razorpay->utility->verifyPaymentSignature($attributes);

            // Create order with minimal info (NO shipping info yet)
            $order = $this->createOrderRecord($cart, $request);
            
            if ($order) {
                // Store order ID in session for step 3
                session([
                    'pending_order_id' => $order->id,
                    'payment_id' => $request->razorpay_payment_id,
                    'checkout_step' => 2
                ]);
                
                // Clear cart as payment is successful
                session()->forget('cart');
                
                return response()->json([
                    'success' => true,
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'message' => 'Payment successful! Please provide shipping details.',
                    'redirect_url' => route('orders.shipping', $order->id)
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to create order record'
            ]);

        } catch (\Exception $e) {
            \Log::error('Payment verification failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed: ' . $e->getMessage()
            ]);
        }
    }

    // STEP 3: Show shipping info form (AFTER successful payment)
    public function showShippingForm($orderId)
    {
        $order = Order::where('id', $orderId)
                     ->where('payment_status', 'completed')
                     ->whereNull('shipping_address')
                     ->firstOrFail();

        // Verify this is the pending order from session
        if (session('pending_order_id') != $orderId) {
            return redirect()->route('products.index')
                ->with('error', 'Invalid order access');
        }

        return view('orders.shipping-form', compact('order'));
    }

    // STEP 3: Update order with shipping info
    public function updateShipping(Request $request, Order $order)
    {
        // Verify order belongs to this session and needs shipping info
        if (session('pending_order_id') != $order->id || 
            $order->payment_status != 'completed' || 
            $order->shipping_address) {
            return back()->with('error', 'Invalid shipping update request');
        }

        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'customer_mobile' => 'required|digits:10',
            'customer_email' => 'nullable|email',
            'shipping_address' => 'required|string',
            'country' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'pincode' => 'required|digits:6'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Update order with shipping info
            $order->update([
                'customer_name' => $request->customer_name,
                'customer_mobile' => $request->customer_mobile,
                'customer_email' => $request->customer_email,
                'shipping_address' => $request->shipping_address,
                'country' => $request->country,
                'state' => $request->state,
                'city' => $request->city,
                'pincode' => $request->pincode,
                'order_status' => 'confirmed' // Update status after shipping info
            ]);

            // Update product stock
            $product = Product::find($order->product_id);
            if ($product) {
                $product->decrement('stock', $order->quantity);
            }

            // Create commission if referral code exists
            if ($order->referral_code) {
                $this->createCommission($order);
            }
             
            try {
                $wa = new WhatsAppService();

                $invoiceUrl = route('admin.orders.invoice', ['order' => $order->id]);

                $wa->sendTemplate(
                    $order->customer_mobile,
                    'order_confirmed',
                    [
                        $order->customer_name,              // {{1}}
                        $order->order_number,               // {{2}}
                        number_format($order->total, 2), // {{3}}
                        $invoiceUrl                          // {{4}}
                    ]
                );
            } catch (\Exception $e) {
                \Log::error('WhatsApp send failed: ' . $e->getMessage());
            }


            // Clear session
            session()->forget(['pending_order_id', 'checkout_step']);

            return view('orders.success', compact('order'))
                ->with('success', 'Order confirmed! Shipping details saved.');

        } catch (\Exception $e) {
            \Log::error('Shipping update failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to save shipping details. Please contact support.');
        }
    }

    // Helper: Create initial order record (without shipping info)
    private function createOrderRecord($cart, $request)
    {
        try {
            $order = Order::create([
                'order_number' => 'ORD' . date('Ymd') . strtoupper(Str::random(6)),
                'product_id' => $cart['product_id'],
                'quantity' => $cart['quantity'],
                'unit_price' => $cart['unit_price'],
                'subtotal' => $cart['subtotal'],
                'discount' => $cart['discount'],
                'total' => $cart['total'],
                'referral_code' => $cart['referral_code'],
                'payment_id' => $request->razorpay_payment_id,
                'payment_status' => 'completed',
                'order_status' => 'pending_shipping' // Special status for shipping info pending
            ]);

            return $order;
        } catch (\Exception $e) {
            \Log::error('Order creation failed: ' . $e->getMessage());
            return null;
        }
    }

    // Helper: Create commission record
    private function createCommission($order)
    {
        try {
            $agent = Agent::where('referral_code', $order->referral_code)->first();
            if ($agent) {
                $commissionAmount = 50*$order->quantity; // 5% commission
                
                Commission::create([
                    'agent_id' => $agent->id,
                    'order_id' => $order->id,
                    'amount' => $commissionAmount,
                    'commission_percentage' => 10,
                    'status' => 'pending'
                ]);

                // Update agent earnings
                $agent->increment('total_earnings', $commissionAmount);
                $agent->increment('pending_amount', $commissionAmount);
            }
        } catch (\Exception $e) {
            \Log::error('Commission creation failed: ' . $e->getMessage());
        }
    }

    // Order success page
    public function success(Order $order)
    {
        // Verify order belongs to user (add authentication as needed)
        return view('orders.success', compact('order'));
    }
}