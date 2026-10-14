<?php

namespace App\Support;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

/**
 * Live international spot prices (gold-api.com, free, no key) and their saved history.
 *   - latest(): the current prices, fetched at most every 15 s for all visitors
 *   - record(): saves one row a minute to spot_prices (called by `spot:record` and on live fetches)
 *   - candles(): OHLC candles for the charts, built from the saved rows
 */
class SpotPrices
{
    private const SOURCE   = 'https://api.gold-api.com/price/';
    public const OZ_GRAMS  = 31.1034768;
    public const KEEP_DAYS = 120;

    // Timeframe in minutes => how many candles to send
    public const TIMEFRAMES = [1 => 240, 5 => 240, 15 => 240, 60 => 240];

    public static function latest(): ?array
    {
        return Cache::remember('spot.prices', 15, function () {
            $fresh = static::fetch();
            if ($fresh) {
                Cache::forever('spot.prices.last', $fresh);
                static::record($fresh);
            }

            return $fresh ?? Cache::get('spot.prices.last');
        });
    }

    public static function fetch(): ?array
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
            'xau_usd'       => round($xauUsd, 2),                                 // per troy ounce
            'xag_usd'       => round($xagUsd, 3),                                 // per troy ounce
            'usd_inr'       => round($usdInr, 3),
            'gold_inr_10g'  => round($xauUsd * $usdInr / self::OZ_GRAMS * 10),    // spot, no duty or GST
            'silver_inr_kg' => round($xagUsd * $usdInr / self::OZ_GRAMS * 1000),  // spot, no duty or GST
            'updated_at'    => $gold['updatedAt'] ?? now()->toIso8601String(),
        ];
    }

    // Save the prices, at most one row a minute
    public static function record(array $p): void
    {
        $minute = CarbonImmutable::now('UTC')->startOfMinute();
        if (! Cache::add('spot.recorded.' . $minute->timestamp, true, 120)) {
            return;
        }
        try {
            DB::table('spot_prices')->insert([
                'xau_usd' => $p['xau_usd'], 'xag_usd' => $p['xag_usd'], 'usd_inr' => $p['usd_inr'],
                'recorded_at' => $minute->toDateTimeString(),
            ]);
        } catch (\Throwable $e) {
            report($e); // e.g. the table is not migrated yet; live prices still work
        }
    }

    public static function prune(): int
    {
        return DB::table('spot_prices')->where('recorded_at', '<', now('UTC')->subDays(self::KEEP_DAYS))->delete();
    }

    /**
     * Candles for one series, oldest first: [[time ms, open, close, low, high], …] (the order ECharts expects).
     * Series: xau_usd, xag_usd, xau_inr_g, xag_inr_g (see App\Support\LiveCharts).
     */
    public static function candles(string $series, int $minutes): array
    {
        $count = self::TIMEFRAMES[$minutes];

        return Cache::remember("spot.candles.$series.$minutes", 15, function () use ($series, $minutes, $count) {
            $span  = $minutes * 60;
            $since = CarbonImmutable::now('UTC')->subSeconds($span * $count);
            try {
                $rows = DB::table('spot_prices')->where('recorded_at', '>=', $since)->orderBy('recorded_at')
                    ->get(['xau_usd', 'xag_usd', 'usd_inr', 'recorded_at']);
            } catch (\Throwable $e) {
                report($e);
                return [];
            }

            $buckets = [];
            foreach ($rows as $r) {
                $v = match ($series) {
                    'xau_usd'   => (float) $r->xau_usd,
                    'xag_usd'   => (float) $r->xag_usd,
                    'xau_inr_g' => (float) $r->xau_usd * (float) $r->usd_inr / self::OZ_GRAMS,
                    'xag_inr_g' => (float) $r->xag_usd * (float) $r->usd_inr / self::OZ_GRAMS,
                };
                $t = intdiv(CarbonImmutable::parse($r->recorded_at, 'UTC')->timestamp, $span) * $span;
                if (! isset($buckets[$t])) {
                    $buckets[$t] = [$t * 1000, $v, $v, $v, $v];
                } else {
                    $buckets[$t][2] = $v;                        // close
                    $buckets[$t][3] = min($buckets[$t][3], $v);  // low
                    $buckets[$t][4] = max($buckets[$t][4], $v);  // high
                }
            }

            $dp = $series === 'xag_usd' ? 3 : 2;
            return array_values(array_map(
                fn ($c) => [$c[0], round($c[1], $dp), round($c[2], $dp), round($c[3], $dp), round($c[4], $dp)],
                $buckets
            ));
        });
    }
}
