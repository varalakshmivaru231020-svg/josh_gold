<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Agent;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->paginate(12);

        return view('products.index', compact('products'));
    }

    public function show(Product $product)
    {
        if (!$product->is_active || $product->stock <= 0) {
            abort(404);
        }

        $relatedProducts = Product::where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where('stock', '>', 0)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
  
      // STEP 1: Create Razorpay order (payment initiation)
    public function checkReferral(Request $request)
    {
            $request->validate([
                'referral_code' => 'required|string'
            ]);
            $user=Agent::where('referral_code',$request->referral_code)->first();
            if($user)
            {
              return response()->json([
                  'success' => true,
                  'message' =>"Referral Code Applied successfully"
              ]);
            }
          else
          {
              return response()->json([
                  'success' => false,
                  'message' =>"Invalid Referral code"
              ]);
          }
    }

    public function trackOrder(Request $request)
    {
        $trackingId = $request->query('tracking_id');
        $order = null;

        if ($trackingId) {
            $order = \App\Models\Order::where('tracking_id', $trackingId)
                ->orWhere('order_number', $trackingId)
                ->first();
        }

        return view('orders.track', compact('order'));
    }
}