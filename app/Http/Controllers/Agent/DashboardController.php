<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show agent dashboard with stats and recent commissions
     */
    public function index()
    {
        $agent = Auth::guard('agent')->user();
        $referralsCount = $agent->referrals()->count();

        $stats = [
            'total_earnings' => $agent->total_earnings,
            'settled_amount' => $agent->settled_amount,
            'pending_amount' => $agent->pending_amount,
            'total_referrals' => $referralsCount,
            'pending_commissions' => $agent->commissions()->where('status', 'pending')->count(),
            'settled_commissions' => $agent->commissions()->where('status', 'settled')->count(),
        ];

        $recentCommissions = $agent->commissions()
            ->with('order.product')
            ->latest()
            ->take(10)
            ->get();

        return view('agent.dashboard', compact('stats', 'recentCommissions', 'agent'));
    }


    /**
     * Show agent commissions list
     */
    public function commissions(Request $request)
    {
        $agent = Auth::guard('agent')->user();
        
        $query = $agent->commissions()
            ->with('order.product')
            ->orderByDesc('created_at');

        if ($request->has('status') && in_array($request->status, ['pending', 'settled'])) {
            $query->where('status', $request->status);
        }

        $commissions = $query->paginate(15)->withQueryString();

        return view('agent.commissions', compact('commissions'));
    }

    /**
     * Share referral link
     */
    public function shareReferral()
    {
        $agent = Auth::guard('agent')->user();
        $shareUrl = route('agent.register') . '?ref=' . $agent->referral_code;

        return view('agent.share-referral', compact('shareUrl', 'agent'));
    }

    /**
     * Show agent profile
     */
    public function profile()
    {
        $agent = Auth::guard('agent')->user();
        return view('agent.profile', compact('agent'));
    }

    /**
     * Update agent profile
     */
    public function updateProfile(Request $request)
    {
        $agent = Auth::guard('agent')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:agents,email,' . $agent->id,
            'bank_details.account_number' => 'nullable|string',
            'bank_details.account_holder_name' => 'nullable|string',
            'bank_details.ifsc_code' => 'nullable|string',
            'bank_details.bank_name' => 'nullable|string',
            'upi_id' => 'nullable|string',
        ]);

        $agent->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }
}
