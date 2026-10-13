<?php

namespace App\Http\Controllers;

use App\Support\RateBook;

// Public rate pages behind the main menu: gold rate, silver rate, charts, Gold Pulse
// and the city pages. All figures come from our own published rates.
class RatePagesController extends Controller
{
    public function gold()
    {
        return view('rates.gold-today', $this->rates(10));
    }

    public function silver()
    {
        return view('rates.silver-today', $this->rates(10));
    }

    public function charts()
    {
        return view('rates.charts', $this->rates(0));
    }

    public function pulse()
    {
        $data = $this->rates(0);
        $data['past'] = $data['rate'] ? RateBook::pastCloses($data['rate']) : [];

        return view('rates.gold-pulse', $data);
    }

    public function cities()
    {
        return view('rates.cities', $this->rates(0));
    }

    public function city(string $state, string $city)
    {
        // Only active cities have a page (Admin → Cities)
        $key    = $state . '/' . $city;
        $cities = RateBook::cities();
        abort_unless(isset($cities[$key]), 404);

        [$name, $stateName] = $cities[$key];

        return view('rates.city', $this->rates(10) + [
            'cityKey'   => $key,
            'cityName'  => $name,
            'stateName' => $stateName,
        ]);
    }

    // Latest rate, plus the last $days daily closes for the trend table
    private function rates(int $days): array
    {
        $rate = RateBook::latest();

        return [
            'rate'   => $rate,
            'closes' => ($rate && $days) ? RateBook::dailyCloses($rate, $days) : collect(),
            'cities' => RateBook::cities(),
        ];
    }
}
