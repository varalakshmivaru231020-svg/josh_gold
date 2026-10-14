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
 *   - addSample() / clearSample(): flagged sample history before the first real price, so new charts are not empty;
 *     removed automatically once 24 hours of real prices exist (record())
 */
class SpotPrices
{
    private const SOURCE   = 'https://api.gold-api.com/price/';
    public const OZ_GRAMS  = 31.1034768;
    public const KEEP_DAYS = 120;
    public const SAMPLE_HOURS = 24;

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
            if ($minute->minute === 0 && static::hasSample()) {
                $firstReal = DB::table('spot_prices')->where('is_sample', false)->min('recorded_at');
                if ($firstReal && CarbonImmutable::parse($firstReal, 'UTC')->lte(now('UTC')->subHours(self::SAMPLE_HOURS))) {
                    static::clearSample();
                }
            }
        } catch (\Throwable $e) {
            report($e); // e.g. the table is not migrated yet; live prices still work
        }
    }

    public static function prune(): int
    {
        return DB::table('spot_prices')->where('recorded_at', '<', now('UTC')->subDays(self::KEEP_DAYS))->delete();
    }

    /**
     * Candles for one series, oldest first: ['candles' => [[time ms, open, close, low, high], …], 'sample' => bool]
     * (the candle order ECharts expects; 'sample' is true when flagged sample rows are in the range).
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
                    ->get(['xau_usd', 'xag_usd', 'usd_inr', 'is_sample', 'recorded_at']);
            } catch (\Throwable $e) {
                report($e);
                return ['candles' => [], 'sample' => false];
            }
            $sample = false;

            $buckets = [];
            foreach ($rows as $r) {
                $sample = $sample || (bool) $r->is_sample;
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
            return ['sample' => $sample, 'candles' => array_values(array_map(
                fn ($c) => [$c[0], round($c[1], $dp), round($c[2], $dp), round($c[3], $dp), round($c[4], $dp)],
                $buckets
            ))];
        });
    }

    public static function hasSample(): bool
    {
        return DB::table('spot_prices')->where('is_sample', true)->exists();
    }

    /**
     * Flagged sample history: one row a minute for SAMPLE_HOURS before the first real price, as a small random walk
     * that ends at that real price (so the line joins up). Returns the number of rows added.
     */
    public static function addSample(): int
    {
        static::clearSample();
        $first = DB::table('spot_prices')->where('is_sample', false)->orderBy('recorded_at')->first();
        $p = $first ? ['xau_usd' => (float) $first->xau_usd, 'xag_usd' => (float) $first->xag_usd, 'usd_inr' => (float) $first->usd_inr] : static::fetch();
        if (! $p) {
            return 0;
        }
        $end = $first ? CarbonImmutable::parse($first->recorded_at, 'UTC') : CarbonImmutable::now('UTC')->startOfMinute()->addMinute();

        $g = $p['xau_usd'];
        $s = $p['xag_usd'];
        $fx = $p['usd_inr'];
        $step = fn (float $v, float $vol) => $v * (1 + $vol * (mt_rand(-1000, 1000) / 1000));
        $rows = [];
        for ($i = 1; $i <= self::SAMPLE_HOURS * 60; $i++) {
            $g = $step($g, 0.0004);
            $s = $step($s, 0.0007);
            $fx = $step($fx, 0.00003);
            $rows[] = ['xau_usd' => round($g, 2), 'xag_usd' => round($s, 3), 'usd_inr' => round($fx, 3), 'is_sample' => true,
                       'recorded_at' => $end->subMinutes($i)->toDateTimeString()];
        }
        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('spot_prices')->insert($chunk);
        }
        static::forgetCandles();

        return count($rows);
    }

    public static function clearSample(): int
    {
        $n = DB::table('spot_prices')->where('is_sample', true)->delete();
        static::forgetCandles();

        return $n;
    }

    private static function forgetCandles(): void
    {
        foreach (LiveCharts::CHARTS as $chart) {
            foreach (array_keys(self::TIMEFRAMES) as $tf) {
                Cache::forget("spot.candles.{$chart['series']}.$tf");
            }
        }
    }
}
