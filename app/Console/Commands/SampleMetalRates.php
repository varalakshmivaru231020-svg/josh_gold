<?php

namespace App\Console\Commands;

use App\Models\MetalRate;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SampleMetalRates extends Command
{
    protected $signature = 'rates:sample
                            {--years=10 : How many years of history to create}
                            {--clear : Remove all sample rates instead of creating them}';

    protected $description = 'Fill metal_rates with static, deterministic SAMPLE gold & silver history for demos (flagged is_sample, removable)';

    // Approximate 24K gold, INR per 10 gm, at the start of each period. Sample only:
    // shaped after the general 10-year Indian gold trend, not actual daily prices.
    private const GOLD = [
        '2016-09-26' => 31000, '2016-12-26' => 27800, '2017-03-27' => 29200, '2017-09-25' => 29800,
        '2017-12-25' => 28900, '2018-03-26' => 30500, '2018-09-24' => 31000, '2018-12-24' => 32000,
        '2019-03-25' => 33000, '2019-06-24' => 34500, '2019-09-23' => 38500, '2019-12-23' => 38300,
        '2020-03-23' => 42800, '2020-06-22' => 47500, '2020-08-10' => 56000, '2020-12-21' => 50200,
        '2021-03-22' => 44500, '2021-09-27' => 46500, '2021-12-27' => 48000, '2022-03-07' => 53000,
        '2022-09-26' => 49500, '2022-12-26' => 54800, '2023-03-27' => 59500, '2023-06-26' => 59500,
        '2023-09-25' => 59000, '2023-12-27' => 63000, '2024-03-25' => 66500, '2024-05-06' => 70900,
        '2024-09-23' => 76500, '2024-12-23' => 77500, '2025-03-24' => 89500, '2025-06-23' => 98500,
        '2025-09-22' => 112000, '2025-12-22' => 133000, '2026-02-02' => 163000, '2026-04-20' => 150500,
        '2026-06-22' => 146500, '2026-08-24' => 153500, '2026-09-23' => 151450,
    ];

    // Approximate 999 silver, INR per 1 kg. Same caveat as above.
    private const SILVER = [
        '2016-09-26' => 44000, '2016-12-26' => 40500, '2017-03-27' => 43500, '2017-09-25' => 40000,
        '2017-12-25' => 38500, '2018-03-26' => 39500, '2018-09-24' => 40500, '2018-12-24' => 39500,
        '2019-03-25' => 39500, '2019-06-24' => 39000, '2019-09-23' => 47500, '2019-12-23' => 47000,
        '2020-03-23' => 39500, '2020-06-22' => 48000, '2020-08-10' => 76000, '2020-12-21' => 68000,
        '2021-03-22' => 63000, '2021-09-27' => 61500, '2021-12-27' => 62000, '2022-03-07' => 72000,
        '2022-09-26' => 55500, '2022-12-26' => 69000, '2023-03-27' => 69500, '2023-06-26' => 70000,
        '2023-09-25' => 72500, '2023-12-27' => 78000, '2024-03-25' => 75500, '2024-05-06' => 82500,
        '2024-09-23' => 90500, '2024-12-23' => 89500, '2025-03-24' => 100000, '2025-06-23' => 107000,
        '2025-09-22' => 132000, '2025-12-22' => 195000, '2026-02-02' => 260000, '2026-04-20' => 232000,
        '2026-06-22' => 222000, '2026-08-24' => 238000, '2026-09-23' => 235030,
    ];

    public function handle(): int
    {
        if ($this->option('clear')) {
            $count = MetalRate::where('is_sample', true)->delete();
            $this->info("Removed $count sample rate entries. Real rates were not touched.");
            return self::SUCCESS;
        }

        // Stop the day before the first real rate so sample history flows into real data;
        // with no real rates yet, stop yesterday.
        $firstReal = MetalRate::where('is_sample', false)->min('rate_date');
        $end   = $firstReal ? Carbon::parse($firstReal)->subDay() : Carbon::yesterday();
        $start = Carbon::parse(array_key_first(self::GOLD));
        $start = $start->max($end->copy()->subYears((int) $this->option('years')));

        $goldAnchors   = $this->anchors(self::GOLD);
        $silverAnchors = $this->anchors(self::SILVER);

        $rows = [];
        for ($day = $start->copy(); $day->lte($end); $day->addDay()) {
            if ($day->isSunday()) {
                continue; // no rates are published on Sundays
            }

            $date   = $day->toDateString();
            $ts     = Carbon::parse($date, 'UTC')->getTimestamp();
            $gold   = $this->level($goldAnchors, $ts, 1.0, 'g' . $date);
            $silver = $this->level($silverAnchors, $ts, 2.2, 's' . $date);

            // Evening moves slightly from the morning rate, as a real session would
            $drift = $this->noise('e' . $date) * 0.003;

            foreach (['morning' => 1.0, 'evening' => 1.0 + $drift] as $session => $factor) {
                // Stamp each row with its own session time (stored in UTC) so "last updated" is coherent
                $stamp = Carbon::parse("$date " . MetalRate::SESSION_TIMES[$session], MetalRate::TIMEZONE)
                    ->utc()->format('Y-m-d H:i:s');

                $rows[] = [
                    'rate_date'       => $date,
                    'session'         => $session,
                    'gold_999_rate'   => round($gold * $factor / 10, 2),      // stored per 1 gm
                    'silver_999_rate' => round($silver * $factor / 1000, 2),  // stored per 1 gm
                    'remarks'         => null,
                    'is_sample'       => true,
                    'created_at'      => $stamp,
                    'updated_at'      => $stamp,
                ];
            }
        }

        // insertOrIgnore: the (date, session) unique key means a real rate is never overwritten
        $added = 0;
        foreach (array_chunk($rows, 500) as $chunk) {
            $added += DB::table('metal_rates')->insertOrIgnore($chunk);
        }

        $this->info("Added $added sample rate entries ({$start->toDateString()} to {$end->toDateString()}).");
        $this->line('They are flagged as sample data. Remove them with: php artisan rates:sample --clear');

        return self::SUCCESS;
    }

    // [[timestamp, price], ...] sorted by date
    private function anchors(array $anchors): array
    {
        $list = [];
        foreach ($anchors as $date => $price) {
            $list[] = [Carbon::parse($date, 'UTC')->getTimestamp(), $price];
        }
        return $list;
    }

    // Price level at $ts: log-linear between the surrounding anchors (flat outside them),
    // plus smooth deterministic wiggles so the chart looks like a market.
    // $volatility scales the wiggle.
    private function level(array $anchors, int $ts, float $volatility, string $seed): float
    {
        [$t0, $p0] = $anchors[0];
        [$t1, $p1] = $anchors[0];

        foreach ($anchors as [$t, $price]) {
            [$t0, $p0] = [$t1, $p1];
            [$t1, $p1] = [$t, $price];
            if ($t >= $ts) {
                break;
            }
        }

        $frac = $t1 > $t0 ? min(1, max(0, ($ts - $t0) / ($t1 - $t0))) : 0;
        $base = exp(log($p0) + (log($p1) - log($p0)) * $frac);

        $days   = $ts / 86400;
        $wiggle = sin($days / 6.3) * 0.010 + sin($days / 2.1 + 1.3) * 0.005 + $this->noise($seed) * 0.004;

        return $base * (1 + $wiggle * $volatility);
    }

    // Deterministic pseudo-random value in [-1, 1) from a string
    private function noise(string $seed): float
    {
        return (crc32($seed) % 2000 - 1000) / 1000;
    }
}
