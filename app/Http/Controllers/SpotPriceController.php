<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

// Live international spot prices for the ticker and the home page (gold-api.com, free, no key).
// Fetched at most every 15 seconds for all visitors; the last good prices are kept if the source is down.
class SpotPriceController extends Controller
{
    private const SOURCE  = 'https://api.gold-api.com/price/';
    private const OZ_GRAMS = 31.1034768;

    public function show(): JsonResponse
    {
        $data = Cache::remember('spot.prices', 15, function () {
            $fresh = $this->fetch();
            if ($fresh) {
                Cache::forever('spot.prices.last', $fresh);
            }

            return $fresh ?? Cache::get('spot.prices.last');
        });

        if (! $data) {
            return response()->json(['message' => 'Live prices are unavailable right now.'], 503);
        }

        return response()->json($data)->header('Cache-Control', 'public, max-age=10');
    }

    private function fetch(): ?array
    {
        try {
            $gold   = Http::timeout(5)->get(self::SOURCE . 'XAU/INR')->throw()->json();
            $silver = Http::timeout(5)->get(self::SOURCE . 'XAG')->throw()->json();
        } catch (\Throwable $e) {
            report($e);
            return null;
        }

        $usdInr = (float) ($gold['exchangeRate'] ?? 0);
        $xauUsd = $usdInr > 0 ? (float) $gold['price'] / $usdInr : 0;
        $xagUsd = (float) ($silver['price'] ?? 0);
        if ($usdInr <= 0 || $xauUsd <= 0 || $xagUsd <= 0) {
            return null;
        }

        return [
            'xau_usd'       => round($xauUsd, 2),                                  // per troy ounce
            'xag_usd'       => round($xagUsd, 3),                                  // per troy ounce
            'usd_inr'       => round($usdInr, 3),
            'gold_inr_10g'  => round($xauUsd * $usdInr / self::OZ_GRAMS * 10),     // spot, no duty or GST
            'silver_inr_kg' => round($xagUsd * $usdInr / self::OZ_GRAMS * 1000),   // spot, no duty or GST
            'updated_at'    => $gold['updatedAt'] ?? now()->toIso8601String(),
        ];
    }
}
