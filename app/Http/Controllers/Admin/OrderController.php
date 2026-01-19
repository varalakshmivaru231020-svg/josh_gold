<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\WhatsAppService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Protect all admin order routes
     */
    public function __construct()
    {
        //$this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        $query = Order::with('product');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_mobile', 'like', "%{$search}%")
                  ->orWhere('tracking_id', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('order_status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->orderBy('id', 'desc')->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('product', 'commission.agent');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'tracking_id'  => 'nullable|string|max:100',
            'tracking_url' => 'nullable|string',
            'notes'        => 'nullable|string'
        ]);

        $order->update($validated);

        // ✅ FIX: comparison instead of assignment
        if ($request->order_status === 'shipped') {
            try {
                $wa = new WhatsAppService();
                $wa->sendTemplate(
                    $order->customer_mobile,
                    'order_shipped_final',
                    [
                        $order->customer_name,
                        'Order #' . $order->order_number,
                        $order->tracking_id ?? 'Not Assigned',
                        $order->tracking_url ?? 'Not Assigned'
                    ]
                );
            } catch (\Exception $e) {
                // Do not break order update if WhatsApp fails
            }
        }

        return back()->with('success', 'Order status updated successfully.');
    }

    public function export(Request $request)
    {
        $orders = Order::with('product')
            ->when($request->filled('date_from'), function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->date_to);
            })
            ->when($request->filled('status') && $request->status !== 'all', function ($q) use ($request) {
                $q->where('order_status', $request->status);
            })
            ->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=orders_' . date('Y-m-d') . '.csv',
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Order Number',
                'Date',
                'Customer',
                'Mobile',
                'Product',
                'Quantity',
                'Total',
                'Status',
                'Payment Status'
            ]);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->created_at->format('Y-m-d'),
                    $order->customer_name,
                    $order->customer_mobile,
                    optional($order->product)->name,
                    $order->quantity,
                    $order->total,
                    $order->order_status,
                    $order->payment_status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function invoice(Order $order)
    {
        $order->load('product');

        // ✅ Memory-safe & standard paper size
        $pdf = Pdf::loadView('admin.orders.invoice-pdf', compact('order'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }
}
