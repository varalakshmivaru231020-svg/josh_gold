<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Commission;
use App\Models\Settlement;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function index(Request $request)
    {
        $query = Agent::withCount(['referrals', 'commissions']);

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('referral_code', 'like', "%{$search}%");
            });
        }

        $agents = $query->latest()->paginate(20);

        return view('admin.agents.index', compact('agents'));
    }

    public function show(Agent $agent)
    {
        $agent->load(['referrals', 'commissions.order', 'settlements']);
        
        $stats = [
            'total_referrals' => $agent->referrals->count(),
            'total_earnings' => $agent->total_earnings,
            'settled_amount' => $agent->settled_amount,
            'pending_amount' => $agent->pending_amount,
            'total_commissions' => $agent->commissions->count(),
            'pending_commissions' => $agent->commissions->where('status', 'pending')->count()
        ];

        $recentCommissions = $agent->commissions()
            ->with('order.product')
            ->latest()
            ->take(10)
            ->get();

        $recentSettlements = $agent->settlements()
            ->latest()
            ->take(10)
            ->get();

        return view('admin.agents.show', compact('agent', 'stats', 'recentCommissions', 'recentSettlements'));
    }

    public function update(Request $request, Agent $agent)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:agents,email,' . $agent->id,
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        $agent->update($validated);

        return back()->with('success', 'Agent updated successfully.');
    }

    public function commissions(Request $request)
    {
        $query = Commission::with(['agent', 'order.product']);

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('agent', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('mobile', 'like', "%{$search}%");
                })
                ->orWhereHas('order', function($q2) use ($search) {
                    $q2->where('order_number', 'like', "%{$search}%");
                });
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by agent
        if ($request->has('agent_id')) {
            $query->where('agent_id', $request->agent_id);
        }

        $commissions = $query->latest()->paginate(20);
        $agents = Agent::all();

        return view('admin.commissions.index', compact('commissions', 'agents'));
    }

    public function updateCommissionStatus(Request $request, Commission $commission)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,settled',
            'notes' => 'nullable|string'
        ]);

        if ($validated['status'] === 'settled' && $commission->status === 'pending') {
            $commission->agent->increment('settled_amount', $commission->amount);
            $commission->agent->decrement('pending_amount', $commission->amount);
            $validated['settled_at'] = now();
        }

        $commission->update($validated);

        return back()->with('success', 'Commission status updated successfully.');
    }
    // Add this method to AgentController
        public function getPendingCommissions(Agent $agent)
        {
            $commissions = Commission::where('agent_id', $agent->id)
                ->where('status', 'pending')
                ->with('order')
                ->get()
                ->map(function($commission) {
                    return [
                        'order_number' => $commission->order->order_number,
                        'amount' => $commission->amount
                    ];
                });
            
            return response()->json($commissions);
        }
}