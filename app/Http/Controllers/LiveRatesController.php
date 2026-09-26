<?php

namespace App\Http\Controllers;

use App\Models\MetalRate;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class LiveRatesController extends Controller
{
    private const RANGES = ['1D', '1W', '1M', '3M', '6M', '1Y', '5Y', 'MAX'];

    // Longer ranges return one closing rate per day so the payload stays small
    private const DAILY_CLOSE_RANGES = ['3M', '6M', '1Y', '5Y', 'MAX'];

    public function index()
    {
        // ── Current (latest) session ──────────────────────────
        $current = MetalRate::orderByDesc('rate_date')
            ->orderByRaw("FIELD(session, 'evening', 'morning')")
            ->first();

        // ── Previous session (to compute ▲/▼) ─────────────────
        $previous = null;
        if ($current) {
            $previous = MetalRate::where(function ($q) use ($current) {
                $q->where('rate_date', '<', $current->rate_date)
                  ->orWhere(function ($inner) use ($current) {
                      $inner->where('rate_date', $current->rate_date)
                            ->where('id', '<', $current->id);
                  });
            })
            ->orderByDesc('rate_date')
            ->orderByRaw("CASE session WHEN 'evening' THEN 0 ELSE 1 END")
            ->first();
        }

        // ── Purity table (auto-computed from current) ──────────
        $goldTable  = $current ? $current->goldPurityTable() : [];

        // ── Silver rate table ──────────────────────────────────
        $silverTable = $current ? $current->silverPurityTable() : [];

        return view('live-rates', [
            'current'       => $current,
            'previous'      => $previous,
            'goldTable'     => $goldTable,
            'silverTable'   => $silverTable,
            'comparison'    => $this->comparison($current),
        ]);
    }

    // ── GET /live-rates/history ────────────────────────────────
    // Price history for the rate chart, straight from the published metal_rates rows.
    //   metal   gold | silver                 (default gold)
    //   purity  24K…10K | 999, 925, 900, 800  (default 24K / 999)
    //   unit    1gm | 10gm | 100gm | 1kg      (default 10gm gold / 1kg silver)
    //   range   1D 1W 1M 3M 6M 1Y 5Y MAX      (default 1D)
    // Ranges end at the latest published rate, so the chart is never empty just
    // because today's rate has not been entered yet.
    public function history(Request $request): JsonResponse
    {
        $validator = Validator::make($request->query(), [
            'metal'  => 'nullable|in:gold,silver',
            'purity' => 'nullable|string',
            'unit'   => 'nullable|in:' . implode(',', array_keys(MetalRate::UNIT_GRAMS)),
            'range'  => 'nullable|in:' . implode(',', self::RANGES),
        ]);

        $metal  = $request->query('metal', 'gold');
        $purity = $request->query('purity', $metal === 'gold' ? '24K' : '999');
        $unit   = $request->query('unit', $metal === 'gold' ? '10gm' : '1kg');
        $range  = $request->query('range', '1D');

        $validator->after(function ($validator) use ($metal, $purity) {
            $valid = $metal === 'gold' ? MetalRate::PURITY : MetalRate::SILVER_PURITY;
            if (! array_key_exists($purity, $valid)) {
                $validator->errors()->add('purity', "Unknown $metal purity.");
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid request.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $grams = MetalRate::UNIT_GRAMS[$unit];
        $price = fn (MetalRate $rate) => $rate->priceFor($metal, $purity, $grams);

        $payload = [
            'metal'         => $metal,
            'purity'        => $purity,
            'unit'          => $unit,
            'range'         => $range,
            'current_price' => null,
            'last_updated'  => null,
            'change'        => ['day' => null, 'week' => null],
            'data'          => [],
        ];

        $latest = $this->closingRate(MetalRate::query());
        if (! $latest) {
            return response()->json($payload);
        }

        $payload['current_price'] = $price($latest);
        $payload['last_updated']  = $latest->updated_at->copy()->setTimezone(MetalRate::TIMEZONE)->toIso8601String();
        $payload['change']        = $this->changeFor($latest, $price);
        $payload['data']          = $this->points($this->ratesForRange($latest, $range), $price);

        return response()->json($payload);
    }

    // ── "Price History" table: latest closing price vs 1 day / week / month / year ago ──
    private function comparison(?MetalRate $latest): ?array
    {
        if (! $latest) {
            return null;
        }

        $date    = $latest->rate_date->copy();
        $periods = [
            '1 Day Ago'   => $date->copy()->subDay(),
            '1 Week Ago'  => $date->copy()->subWeek(),
            '1 Month Ago' => $date->copy()->subMonthNoOverflow(),
            '1 Year Ago'  => $date->copy()->subYearNoOverflow(),
        ];

        // Closing rate on (or, if that day has none, just before) each comparison date
        $closes = [];
        foreach ($periods as $label => $day) {
            $closes[$label] = $this->closeOnOrBefore($day->toDateString(), true);
        }

        $metals = [
            ['label' => 'Gold 24K',   'unit' => '₹ / 10 gm', 'price' => fn (MetalRate $r) => $r->priceFor('gold', '24K', 10)],
            ['label' => 'Silver 999', 'unit' => '₹ / 1 kg',  'price' => fn (MetalRate $r) => $r->priceFor('silver', '999', 1000)],
        ];

        $rows = [];
        foreach ($metals as $metal) {
            $current = $metal['price']($latest);
            $cells   = [];

            foreach ($closes as $close) {
                if (! $close) {
                    $cells[] = null;
                    continue;
                }
                $price   = $metal['price']($close);
                $change  = $current - $price;
                $cells[] = [
                    'price'  => $price,
                    'change' => round($change, 2),
                    'pct'    => $price > 0 ? round($change / $price * 100, 2) : 0.0,
                ];
            }

            $rows[] = ['label' => $metal['label'], 'unit' => $metal['unit'], 'current' => $current, 'cells' => $cells];
        }

        $headings = [];
        foreach ($closes as $label => $close) {
            $headings[] = ['label' => $label, 'date' => $close?->rate_date];
        }

        return ['periods' => $headings, 'rows' => $rows];
    }

    // ── The rate that counts as "the price" for a day: evening, else morning ──
    private function closingRate($query): ?MetalRate
    {
        return $query->orderByDesc('rate_date')
            ->orderByRaw("CASE session WHEN 'evening' THEN 0 ELSE 1 END")
            ->first();
    }

    // ── Rows to plot for a range, ending at the latest published date ──
    private function ratesForRange(MetalRate $latest, string $range): Collection
    {
        $latestDate = $latest->rate_date->copy();

        $query = MetalRate::query()
            ->select(['id', 'rate_date', 'session', 'gold_999_rate', 'silver_999_rate', 'created_at'])
            ->orderBy('rate_date')
            ->orderByRaw("CASE session WHEN 'morning' THEN 0 ELSE 1 END");

        $start = match ($range) {
            '1D'    => $latestDate,
            '1W'    => $latestDate->copy()->subDays(6),
            '1M'    => $latestDate->copy()->subMonthNoOverflow(),
            '3M'    => $latestDate->copy()->subMonthsNoOverflow(3),
            '6M'    => $latestDate->copy()->subMonthsNoOverflow(6),
            '1Y'    => $latestDate->copy()->subYearNoOverflow(),
            '5Y'    => $latestDate->copy()->subYearsNoOverflow(5),
            default => null, // MAX
        };

        if ($start) {
            $query->where('rate_date', '>=', $start->toDateString());
        }

        $rates = $query->get();

        if (in_array($range, self::DAILY_CLOSE_RANGES, true)) {
            $rates = $rates
                ->groupBy(fn ($rate) => $rate->rate_date->toDateString())
                ->map(fn ($day) => $day->firstWhere('session', 'evening') ?? $day->first())
                ->values();
        }

        return $rates;
    }

    // ── Real rows → [{timestamp, price, session}] sorted by time ──
    private function points(Collection $rates, callable $price): array
    {
        return $rates
            ->sortBy(fn ($rate) => $rate->published_at->getTimestamp())
            ->map(fn ($rate) => [
                'timestamp' => $rate->published_at->toIso8601String(),
                'price'     => $price($rate),
                'session'   => $rate->session,
            ])
            ->values()
            ->all();
    }

    // ── Change of the latest price vs the previous day and vs 7+ days earlier ──
    private function changeFor(MetalRate $latest, callable $price): array
    {
        $current    = $price($latest);
        $latestDate = $latest->rate_date->toDateString();

        $baseline = function (string $cutoff, bool $inclusive) use ($price, $current) {
            $close = $this->closeOnOrBefore($cutoff, $inclusive);
            if (! $close) {
                return null;
            }

            $previous = $price($close);
            $change   = $current - $previous;

            return [
                'change' => round($change, 2),
                'pct'    => $previous > 0 ? round($change / $previous * 100, 2) : 0.0,
                'from'   => $close->rate_date->toDateString(),
            ];
        };

        return [
            'day'  => $baseline($latestDate, false),
            'week' => $baseline(Carbon::parse($latestDate)->subDays(7)->toDateString(), true),
        ];
    }

    // ── Closing rate of the last published date before / on $date ──
    private function closeOnOrBefore(string $date, bool $inclusive): ?MetalRate
    {
        $day = MetalRate::where('rate_date', $inclusive ? '<=' : '<', $date)->max('rate_date');

        return $day ? $this->closingRate(MetalRate::where('rate_date', $day)) : null;
    }
}
