<?php

namespace App\Support;

use App\Models\City;
use App\Models\MetalRate;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Read helpers for the public rate pages (gold rate, silver rate, charts, Gold Pulse, city pages).
 * Every figure comes from our own published metal_rates rows.
 */
class RateBook
{
    // Cities with a rate page, keyed by their URL path: ['state/city' => [city name, state name]].
    // Active cities in admin order (Admin → Cities); cached until a city is saved or deleted.
    // We publish one reference rate for all of them; the pages say so.
    public static function cities(): array
    {
        $cities = [];
        foreach (City::activeCached() as $city) {
            $cities[$city['key']] = [$city['name'], $city['state']];
        }

        return $cities;
    }

    // Cities linked from the header menu, same shape as cities()
    public static function menuCities(): array
    {
        $cities = [];
        foreach (City::activeCached() as $city) {
            if ($city['menu']) {
                $cities[$city['key']] = [$city['name'], $city['state']];
            }
        }

        return $cities;
    }

    public static function latest(): ?MetalRate
    {
        return MetalRate::currentRate();
    }

    // Closing rate (evening if published, otherwise morning) of each of the last $days days that have a rate, newest first
    public static function dailyCloses(MetalRate $latest, int $days): Collection
    {
        return MetalRate::whereBetween('rate_date', [
                $latest->rate_date->copy()->subDays($days * 3)->toDateString(),
                $latest->rate_date->toDateString(),
            ])
            ->orderByDesc('rate_date')
            ->orderByRaw("FIELD(session, 'evening', 'morning')")
            ->get()
            ->unique(fn (MetalRate $r) => $r->rate_date->toDateString())
            ->take($days)
            ->values();
    }

    public static function closeOnOrBefore(Carbon $date): ?MetalRate
    {
        return MetalRate::where('rate_date', '<=', $date->toDateString())
            ->orderByDesc('rate_date')
            ->orderByRaw("FIELD(session, 'evening', 'morning')")
            ->first();
    }

    // Closing rates a day, a week, a month and a year before the latest rate
    public static function pastCloses(MetalRate $latest): array
    {
        $date = $latest->rate_date;

        return [
            '1 day'   => self::closeOnOrBefore($date->copy()->subDay()),
            '1 week'  => self::closeOnOrBefore($date->copy()->subWeek()),
            '1 month' => self::closeOnOrBefore($date->copy()->subMonthNoOverflow()),
            '1 year'  => self::closeOnOrBefore($date->copy()->subYearNoOverflow()),
        ];
    }

    // Percentage change from $before to $now, or null when there is nothing to compare
    public static function change(?float $now, ?float $before): ?float
    {
        return ($now !== null && $before) ? ($now - $before) / $before * 100 : null;
    }

    // ₹ in the Indian grouping (1,48,000)
    public static function inr(float $value): string
    {
        $n   = (string) abs(round($value));
        $out = strlen($n) > 3
            ? preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', substr($n, 0, -3)) . ',' . substr($n, -3)
            : $n;

        return ($value < 0 ? '−' : '') . '₹' . $out;
    }
}
