<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settlement;
use App\Models\Agent;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettlementController extends Controller
{
    public function index(Request $request)
    {
        $query = Settlement::with('agent');

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('agent', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('mobile', 'like', "%{$search}%");
                });
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $settlements = $query->latest()->paginate(20);

        return view('admin.settlements.index', compact('settlements'));
    }

    public function create()
    {
        $agents = Agent::where('pending_amount', '>', 0)->get();
        return view('admin.settlements.create', compact('agents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:bank_transfer,upi,cheque,cash',
            'notes' => 'required|string',
            'transaction_id' => 'nullable|string',
            'transaction_date' => 'nullable|date'
        ]);

        $agent = Agent::findOrFail($validated['agent_id']);

        if ($agent->pending_amount < $validated['amount']) {
            return back()->withErrors(['amount' => 'Amount cannot exceed pending amount: ' . $agent->pending_amount]);
        }

        $settlement = Settlement::create([
            'agent_id' => $agent->id,
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'notes' => $validated['notes'],
            'transaction_details' => [
                'transaction_id' => $validated['transaction_id'],
                'transaction_date' => $validated['transaction_date']
            ],
            'status' => 'completed',
            'processed_by' => Auth::guard('admin')->id(),
            'processed_at' => now()
        ]);

        // Update agent amounts
        $agent->decrement('pending_amount', $validated['amount']);
        $agent->increment('settled_amount', $validated['amount']);

        // Mark commissions as settled
        // Mark commissions as settled
        $commissions = Commission::where('agent_id', $agent->id)
            ->whereIn('status', ['pending', 'partial'])
            ->orderBy('created_at')
            ->get();

        $amountToSettle = $validated['amount'];

        foreach ($commissions as $commission) {
            if ($amountToSettle <= 0) break;

            // Use remaining_amount if exists, otherwise full amount
            $remaining = $commission->remaining_amount ?? $commission->amount;

            // FULL settlement
            if ($remaining <= $amountToSettle) {

                $commission->update([
                    'status' => 'settled',
                    'remaining_amount' => 0,
                    'settled_at' => now(),
                    'notes' => 'Settled via settlement ID: ' . $settlement->id
                ]);

                $amountToSettle -= $remaining;
            } 
            // PARTIAL settlement
            else {
                $commission->update([
                    'status' => 'partial',
                    'remaining_amount' => $remaining - $amountToSettle,
                    'notes' => 'Partially settled via settlement ID: ' . $settlement->id
                ]);

                $amountToSettle = 0;
            }
        }

        return redirect()->route('admin.settlements.index')
            ->with('success', 'Settlement processed successfully.');
    }

    public function show(Settlement $settlement)
    {
        $settlement->load(['agent', 'processor']);
        return view('admin.settlements.show', compact('settlement'));
    }

    public function updateStatus(Request $request, Settlement $settlement)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,failed'
        ]);

        $settlement->update($validated);

        return back()->with('success', 'Settlement status updated successfully.');
    }
}