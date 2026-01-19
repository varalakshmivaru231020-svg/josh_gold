<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Agent;
use App\Models\Product;
use App\Models\Commission;
use App\Models\Settlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders' => Order::count(),
            'total_agents' => Agent::count(),
            'total_products' => Product::count(),
            'total_revenue' => Order::sum('total'),
            'total_commissions' => Commission::sum('amount'),
            'pending_settlements' => Commission::where('status', 'pending')->sum('amount')
        ];

        // Daily earnings for chart
        $dailyEarnings = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total) as earnings')
        )
        ->where('created_at', '>=', now()->subDays(30))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        $topAgents = Agent::withSum(['commissions' => function($q) {
                $q->where('status', 'settled');
            }], 'amount')
            ->orderBy('commissions_sum_amount', 'desc') // <-- USE THIS
            ->take(5)
            ->get();

        // Recent orders
        $recentOrders = Order::with('product')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'dailyEarnings', 'topAgents', 'recentOrders'));
    }

    public function getStats(Request $request)
    {
        $period = $request->get('period', 'monthly');

        switch ($period) {
            case 'daily':
                $query = Order::whereDate('created_at', today());
                break;
            case 'weekly':
                $query = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'monthly':
                $query = Order::whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year);
                break;
            default:
                $query = Order::whereYear('created_at', now()->year);
        }

        $stats = [
            'total_orders' => $query->count(),
            'total_revenue' => $query->sum('total'),
            'total_commissions' => Commission::whereIn('order_id', $query->pluck('id'))->sum('amount')
        ];

        return response()->json($stats);
    }
}