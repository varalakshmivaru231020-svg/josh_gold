<?php

namespace App\Support;

// The live charts on /charts and /charts/{slug}: TradingView symbols (international spot prices).
class LiveCharts
{
    public const CHARTS = [
        'xauusd' => ['name' => 'Spot gold',   'sub' => 'XAU/USD · per troy oz',              'symbol' => 'OANDA:XAUUSD'],
        'xagusd' => ['name' => 'Spot silver', 'sub' => 'XAG/USD · per troy oz',              'symbol' => 'OANDA:XAGUSD'],
        'xauinr' => ['name' => 'Gold',        'sub' => 'International spot in ₹ · per gram', 'symbol' => 'FX_IDC:XAUINRG'],
        'xaginr' => ['name' => 'Silver',      'sub' => 'International spot in ₹ · per gram', 'symbol' => 'FX_IDC:XAGINRG'],
    ];

    // TradingView chart embed: dark candles, 5-minute bars, Indian time
    public static function embedUrl(string $symbol, string $interval = '5'): string
    {
        return 'https://s.tradingview.com/widgetembed/?' . http_build_query([
            'symbol' => $symbol, 'interval' => $interval, 'theme' => 'dark', 'style' => 1, 'hidesidetoolbar' => 1,
            'hide_top_toolbar' => 1, 'hidetoptoolbar' => 1, 'symboledit' => 0, 'saveimage' => 0, 'toolbarbg' => '0b0a08',
            'studies' => '[]', 'timezone' => 'Asia/Kolkata', 'locale' => 'en',
        ], '', '&', PHP_QUERY_RFC3986);
    }
}
