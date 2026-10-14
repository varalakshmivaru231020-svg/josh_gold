<?php

namespace App\Support;

// The live charts on /charts and /charts/{slug}: drawn on our own pages (public/js/live-charts.js)
// from the saved spot prices (App\Support\SpotPrices::candles).
class LiveCharts
{
    public const CHARTS = [
        'xauusd' => ['name' => 'Spot gold',   'sub' => 'XAU/USD · per troy oz',              'series' => 'xau_usd',   'dp' => 2, 'pair' => 'XAU/USD'],
        'xagusd' => ['name' => 'Spot silver', 'sub' => 'XAG/USD · per troy oz',              'series' => 'xag_usd',   'dp' => 3, 'pair' => 'XAG/USD'],
        'xauinr' => ['name' => 'Gold',        'sub' => 'International spot in ₹ · per gram', 'series' => 'xau_inr_g', 'dp' => 2, 'pair' => 'XAU/INR'],
        'xaginr' => ['name' => 'Silver',      'sub' => 'International spot in ₹ · per gram', 'series' => 'xag_inr_g', 'dp' => 2, 'pair' => 'XAG/INR'],
    ];
}
