<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MetalRate;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MetalRateController extends Controller
{
    // -------------------------------------------------------
    // GET /api/rates/latest — the rate currently shown on the site
    // -------------------------------------------------------
    public function latest(): JsonResponse
    {
        $rate = MetalRate::currentRate();

        if (! $rate) {
            return response()->json(['message' => 'No rates saved yet.'], 404);
        }

        return response()->json(['data' => $this->format($rate)]);
    }

    // -------------------------------------------------------
    // POST /api/rates — save the morning or afternoon/evening rate
    // Rates are per 1 gm (999 purity). Date and session default to now (IST):
    // before 1 PM is the morning session, after that the evening session.
    // -------------------------------------------------------
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'rate_date'       => 'nullable|date',
            'session'         => 'nullable|in:morning,afternoon,evening',
            'gold_999_rate'   => 'required|numeric|min:0.01',
            'silver_999_rate' => 'required|numeric|min:0.01',
            'remarks'         => 'nullable|string|max:500',
        ]);

        $now     = Carbon::now(MetalRate::TIMEZONE);
        $date    = $data['rate_date'] ?? $now->toDateString();
        $session = $data['session'] ?? ($now->hour < 13 ? 'morning' : 'evening');
        if ($session === 'afternoon') {
            $session = 'evening'; // the site's second daily session
        }

        $rate = MetalRate::updateOrCreate(
            ['rate_date' => $date, 'session' => $session],
            [
                'gold_999_rate'   => $data['gold_999_rate'],
                'silver_999_rate' => $data['silver_999_rate'],
                'remarks'         => $data['remarks'] ?? null,
                'is_sample'       => false,
            ]
        );

        return response()->json([
            'message' => ucfirst($session) . ' session rates saved.',
            'data'    => $this->format($rate),
        ], $rate->wasRecentlyCreated ? 201 : 200);
    }

    private function format(MetalRate $rate): array
    {
        return [
            'rate_date'       => $rate->rate_date->toDateString(),
            'session'         => $rate->session,
            'gold_999_rate'   => $rate->gold_999_rate,
            'silver_999_rate' => $rate->silver_999_rate,
            'gold_22k_rate'   => $rate->goldRate('22K', 1),
            'remarks'         => $rate->remarks,
            'updated_at'      => $rate->updated_at?->setTimezone(MetalRate::TIMEZONE)->toIso8601String(),
        ];
    }
}
