<?php

namespace App\Http\Controllers;

use App\Support\LiveCharts;
use App\Support\SpotPrices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

// Live international spot prices (ticker, charts panel) and the candles for our own live charts.
class SpotPriceController extends Controller
{
    public function show(): JsonResponse
    {
        $data = SpotPrices::latest();
        if (! $data) {
            return response()->json(['message' => 'Live prices are unavailable right now.'], 503);
        }

        return response()->json($data)->header('Cache-Control', 'public, max-age=10');
    }

    // GET /live-rates/candles?chart=xauusd&tf=5
    public function candles(Request $request): JsonResponse
    {
        $chart = LiveCharts::CHARTS[(string) $request->query('chart')] ?? null;
        $tf    = (int) $request->query('tf', 5);
        if (! $chart || ! isset(SpotPrices::TIMEFRAMES[$tf])) {
            return response()->json(['message' => 'Unknown chart or timeframe.'], 422);
        }

        SpotPrices::latest(); // keeps the history recording while people watch

        return response()->json(['tf' => $tf, 'candles' => SpotPrices::candles($chart['series'], $tf)])
            ->header('Cache-Control', 'public, max-age=10');
    }
}
