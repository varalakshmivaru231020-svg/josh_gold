<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MetalRate;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MetalRateController extends Controller
{
    // -------------------------------------------------------
    // Index: list recent rate entries + today's sessions
    // -------------------------------------------------------
    public function index()
    {
        $today = Carbon::today()->toDateString();

        $todayRates = MetalRate::where('rate_date', $today)
            ->orderByRaw("FIELD(session, 'morning', 'evening')")
            ->get();

        $history = MetalRate::orderByDesc('rate_date')
            ->orderByRaw("FIELD(session, 'evening', 'morning')")
            ->paginate(30);

        $sampleCount = MetalRate::where('is_sample', true)->count();

        return view('admin.metal-rates.index', compact('todayRates', 'history', 'today', 'sampleCount'));
    }

    // -------------------------------------------------------
    // Create / Store
    // -------------------------------------------------------
    public function create()
    {
        $today = Carbon::today()->toDateString();

        // Determine which sessions are already filled today
        $filled = MetalRate::where('rate_date', $today)
            ->pluck('session')
            ->toArray();

        return view('admin.metal-rates.create', compact('today', 'filled'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rate_date'       => 'required|date',
            'session'         => 'required|in:morning,evening',
            'gold_999_rate'   => 'required|numeric|min:0.01',
            'silver_999_rate' => 'required|numeric|min:0.01',
            'remarks'         => 'nullable|string|max:500',
        ]);

        // Store gold as per-1-gram rate, silver as per-1-gram rate
        MetalRate::updateOrCreate(
            [
                'rate_date' => $request->rate_date,
                'session'   => $request->session,
            ],
            [
                'gold_999_rate'   => $request->gold_999_rate,
                'silver_999_rate' => $request->silver_999_rate,
                'remarks'         => $request->remarks,
                'is_sample'       => false,
            ]
        );

        return redirect()->route('admin.metal-rates.index')
            ->with('success', ucfirst($request->session) . ' session rates saved successfully!');
    }

    // -------------------------------------------------------
    // Edit / Update
    // -------------------------------------------------------
    public function edit(MetalRate $metalRate)
    {
        return view('admin.metal-rates.edit', compact('metalRate'));
    }

    public function update(Request $request, MetalRate $metalRate)
    {
        $request->validate([
            'gold_999_rate'   => 'required|numeric|min:0.01',
            'silver_999_rate' => 'required|numeric|min:0.01',
            'remarks'         => 'nullable|string|max:500',
        ]);

        $metalRate->update([
            'gold_999_rate'   => $request->gold_999_rate,
            'silver_999_rate' => $request->silver_999_rate,
            'remarks'         => $request->remarks,
            'is_sample'       => false, // an edited rate is a real, admin-confirmed rate
        ]);

        return redirect()->route('admin.metal-rates.index')
            ->with('success', 'Rate entry updated successfully!');
    }

    // -------------------------------------------------------
    // Destroy
    // -------------------------------------------------------
    public function destroy(MetalRate $metalRate)
    {
        $metalRate->delete();
        return back()->with('success', 'Rate entry deleted.');
    }

    // -------------------------------------------------------
    // Remove every demo row created by `php artisan rates:sample`
    // -------------------------------------------------------
    public function clearSample()
    {
        $count = MetalRate::where('is_sample', true)->delete();

        return redirect()->route('admin.metal-rates.index')
            ->with('success', "Removed $count sample rate entries. Real rates were not touched.");
    }

    // -------------------------------------------------------
    // Rate History view (separate page with full pagination)
    // -------------------------------------------------------
    public function history()
    {
        $history = MetalRate::orderByDesc('rate_date')
            ->orderByRaw("FIELD(session, 'evening', 'morning')")
            ->paginate(50);

        return view('admin.metal-rates.history', compact('history'));
    }
}
