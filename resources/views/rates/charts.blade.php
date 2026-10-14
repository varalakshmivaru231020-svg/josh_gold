{{--
    Live charts dashboard, laid out like allindiabullion.com/charts: four live candle charts (TradingView, international
    spot prices) and a rates panel. The panel's spot quotes are live from TradingView; the gold and silver product rates
    are our own published rate (Admin → Live Rates), with 3% GST added where labelled.
    No buy/sell signals: we only show market prices and our own rates.
--}}
@php
    use App\Models\MetalRate;
    use App\Support\RateBook;

    $tv = fn (string $symbol) => 'https://s.tradingview.com/widgetembed/?' . http_build_query([
        'symbol' => $symbol, 'interval' => '5', 'theme' => 'dark', 'style' => 1, 'hidesidetoolbar' => 1, 'hide_top_toolbar' => 1, 'hidetoptoolbar' => 1,
        'symboledit' => 0, 'saveimage' => 0, 'toolbarbg' => '0b0a08', 'studies' => '[]', 'timezone' => 'Asia/Kolkata', 'locale' => 'en',
    ], '', '&', PHP_QUERY_RFC3986);

    $charts = [
        ['key' => 'xau',  'name' => 'Spot gold',   'sub' => 'XAU/USD · per troy oz',       'symbol' => 'OANDA:XAUUSD',   'page' => 'XAUUSD'],
        ['key' => 'xag',  'name' => 'Spot silver', 'sub' => 'XAG/USD · per troy oz',       'symbol' => 'OANDA:XAGUSD',   'page' => 'XAGUSD'],
        ['key' => 'gold', 'name' => 'Gold',        'sub' => 'International spot in ₹ · per gram', 'symbol' => 'FX_IDC:XAUINRG', 'page' => 'XAUINRG'],
        ['key' => 'silv', 'name' => 'Silver',      'sub' => 'International spot in ₹ · per gram', 'symbol' => 'FX_IDC:XAGINRG', 'page' => 'XAGINRG'],
    ];

    $spot = [
        ['label' => 'GLD $', 'symbol' => 'OANDA:XAUUSD'],
        ['label' => 'SLR $', 'symbol' => 'OANDA:XAGUSD'],
        ['label' => '$ vs ₹', 'symbol' => 'FX_IDC:USDINR'],
    ];

    $gst = 1.03;
    $products = [];
    if ($rate) {
        $g = $rate->gold_999_rate * 10;   // 999 per 10 g
        $s = $rate->silver_999_rate * 1000; // 999 per kg
        $products = [
            'Gold products · per 10 g' => [
                ['Retail 999', $g], ['999 with GST', $g * $gst],
                ['Retail 995', $g * .995], ['995 with GST', $g * .995 * $gst],
                ['22K (916)', $rate->goldRate('22K', 10)], ['22K with GST', $rate->goldRate('22K', 10) * $gst],
            ],
            'Silver products · per kg' => [
                ['Retail 999', $s], ['999 with GST', $s * $gst],
            ],
        ];
    }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0b0a08">
    <title>Live Gold &amp; Silver Charts | {{ config('company.name') }}</title>
    <meta name="description" content="Live gold and silver charts (spot XAU/USD, XAG/USD and in rupees) with our published gold and silver rates.">
    @include('layouts.partials.jb-head')
    <style>
        :root { --lc-bg: #050505; --lc-panel: #0b0a08; --lc-line: #26221b; --lc-gold: #d4a64a; --lc-text: #ece6da; --lc-muted: #8d877b; --lc-up: #22c55e; }
        html, body { background: var(--lc-bg); }
        body.lc { margin: 0; min-height: 100vh; color: var(--lc-text); font-family: var(--jb-sans); display: flex; flex-direction: column; }
        .lc-bar { display: flex; flex-wrap: wrap; align-items: center; gap: 10px; padding: 12px 16px; border-bottom: 1px solid var(--lc-line); }
        .lc-brand { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 8px; background: #fff; }
        .lc-brand img { display: block; height: 44px; width: auto; }
        .lc-spacer { flex: 1; }
        .lc-btn { display: inline-flex; align-items: center; gap: 8px; min-height: 38px; padding: 0 16px; border: 1px solid #3a352c; border-radius: 6px; background: #0f0e0b;
            color: var(--lc-text); font: 600 14px/1 var(--jb-sans); text-decoration: none; cursor: pointer; white-space: nowrap; transition: border-color .15s, background-color .15s; }
        .lc-btn:hover { border-color: var(--lc-gold); color: #fff; }
        .lc-btn:focus-visible { outline: 2px solid var(--lc-gold); outline-offset: 2px; }
        .lc-btn--gold { background: var(--lc-gold); border-color: var(--lc-gold); color: #17130b; }
        .lc-btn--gold:hover { background: #e2b65a; color: #17130b; }
        .lc-btn--light { background: #f1e3c2; border-color: #f1e3c2; color: #17130b; }
        .lc-btn--light:hover { color: #17130b; }
        .lc-status { cursor: default; }
        .lc-dot { width: 9px; height: 9px; border-radius: 50%; background: var(--lc-muted); flex: none; }
        .lc-dot--live { background: var(--lc-up); box-shadow: 0 0 0 3px rgba(34, 197, 94, .18); }

        .lc-main { flex: 1; display: grid; grid-template-columns: minmax(0, 1fr) 380px; gap: 12px; padding: 12px; min-height: 0; }
        .lc.lc--norates .lc-main { grid-template-columns: minmax(0, 1fr); }
        .lc.lc--norates .lc-rates { display: none; }
        .lc-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); grid-auto-rows: minmax(360px, calc((100vh - 120px) / 2)); gap: 12px; }
        .lc-grid.is-focused { grid-template-columns: minmax(0, 1fr); grid-auto-rows: minmax(480px, calc(100vh - 110px)); }
        .lc-grid.is-focused .lc-chart:not(.is-focus) { display: none; }

        .lc-chart { position: relative; display: flex; flex-direction: column; min-width: 0; border: 1px solid var(--lc-line); border-radius: 10px; background: var(--lc-panel); overflow: hidden; }
        .lc-chart:hover { border-color: #4a4235; }
        .lc-chart__head { display: flex; align-items: center; gap: 10px; padding: 10px 12px; }
        .lc-chart__name { margin: 0; font: 700 15px/1.2 var(--jb-sans); letter-spacing: .04em; text-transform: uppercase; }
        .lc-chart__sub { display: block; margin-top: 2px; color: var(--lc-muted); font: 500 11.5px/1.2 var(--jb-sans); letter-spacing: 0; text-transform: none; }
        .lc-chart__head .lc-btn { min-height: 32px; padding: 0 12px; font-size: 13px; }
        .lc-chart__frame { flex: 1; min-height: 0; }
        .lc-chart__frame iframe { display: block; width: 100%; height: 100%; border: 0; }

        .lc-rates { display: flex; flex-direction: column; gap: 10px; padding: 12px; border: 1px solid var(--lc-line); border-radius: 10px; background: var(--lc-panel); align-self: start; }
        .lc-rates__title { display: flex; align-items: center; gap: 10px; margin: 6px 0 2px; color: var(--lc-text); font: 800 12.5px/1 var(--jb-sans); letter-spacing: .22em; text-transform: uppercase; }
        .lc-rates__title::before, .lc-rates__title::after { content: ""; flex: 1; height: 1px; background: var(--lc-line); }
        .lc-tiles { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px; }
        .lc-tile { padding: 10px 12px; border: 1px solid #2c2820; border-radius: 8px; background: #0f0e0b; }
        .lc-tile__label { display: block; color: #cfc6b4; font: 700 11px/1.2 var(--jb-sans); letter-spacing: .06em; text-transform: uppercase; }
        .lc-tile__value { display: block; margin-top: 8px; padding: 8px 10px; border-radius: 6px; background: #050505; color: #fff; font: 800 21px/1 var(--jb-sans); font-variant-numeric: tabular-nums; text-align: center; }
        .lc-quote { border: 1px solid #2c2820; border-radius: 8px; overflow: hidden; }
        .lc-quote__label { display: block; padding: 8px 12px 0; color: #cfc6b4; font: 700 11px/1.2 var(--jb-sans); letter-spacing: .06em; }
        .lc-note { margin: 4px 2px 0; color: var(--lc-muted); font-size: 12px; line-height: 1.55; }
        .lc-note a { color: var(--lc-gold); }
        .lc-sample { display: inline-block; margin-left: 6px; padding: 2px 6px; border-radius: 4px; background: #5a4410; color: #ffd98a; font-size: 10.5px; letter-spacing: .04em; vertical-align: middle; }

        @media (max-width: 1100px) {
            .lc-main { grid-template-columns: minmax(0, 1fr); }
            .lc-rates { align-self: stretch; }
        }
        @media (max-width: 760px) {
            .lc-grid, .lc-grid.is-focused { grid-template-columns: minmax(0, 1fr); grid-auto-rows: 380px; }
            .lc-bar { padding: 10px 12px; }
            .lc-brand img { height: 36px; }
            .lc-spacer { display: none; }
            .lc-btn { min-height: 34px; padding: 0 12px; font-size: 13px; }
            .lc-hide-sm { display: none; }
            .lc-main { padding: 10px; }
        }
    </style>
</head>
<body class="lc">
    <header class="lc-bar">
        <a class="lc-brand" href="{{ route('home') }}"><img src="{{ versioned_asset('images/brand/josha-logo-header.webp') }}" alt="{{ config('company.name') }}, home" width="68" height="56"></a>
        <a class="lc-btn" href="{{ route('home') }}">Home</a>
        <a class="lc-btn lc-hide-sm" href="{{ route('live-rates') }}">Rate history</a>
        <span class="lc-spacer"></span>
        <button type="button" class="lc-btn lc-btn--light" id="lcToggleRates" aria-pressed="false">Hide rates</button>
        <button type="button" class="lc-btn lc-hide-sm" id="lcFullscreen">Full screen</button>
        @if($rate)
            <span class="lc-btn lc-status lc-hide-sm" title="Live market charts from TradingView">
                <span class="lc-dot lc-dot--live" aria-hidden="true"></span>Charts live
            </span>
        @endif
        <button type="button" class="lc-btn lc-btn--gold" id="lcRefresh">Refresh</button>
    </header>

    <main class="lc-main" id="main-content">
        <section class="lc-grid" id="lcGrid" aria-label="Live charts">
            @foreach($charts as $c)
                <article class="lc-chart" id="chart-{{ $c['key'] }}">
                    <div class="lc-chart__head">
                        <span class="lc-dot lc-dot--live" aria-hidden="true"></span>
                        <h2 class="lc-chart__name">{{ $c['name'] }}<span class="lc-chart__sub">{{ $c['sub'] }}</span></h2>
                        <span class="lc-spacer"></span>
                        <button type="button" class="lc-btn" data-focus="chart-{{ $c['key'] }}" aria-pressed="false">Focus</button>
                        <a class="lc-btn lc-btn--light" href="https://www.tradingview.com/symbols/{{ $c['page'] }}/" target="_blank" rel="noopener">Open full</a>
                    </div>
                    <div class="lc-chart__frame">
                        <iframe src="{{ $tv($c['symbol']) }}" title="{{ $c['name'] }} live chart ({{ $c['sub'] }})" loading="eager" allowtransparency="true"></iframe>
                    </div>
                </article>
            @endforeach
        </section>

        <aside class="lc-rates" aria-label="Rates">
            <h2 class="lc-rates__title">Spot products</h2>
            @foreach($spot as $q)
                <div class="lc-quote">
                    <span class="lc-quote__label">{{ $q['label'] }}</span>
                    <div class="tradingview-widget-container">
                        <div class="tradingview-widget-container__widget"></div>
                        <script src="https://s3.tradingview.com/external-embedding/embed-widget-single-quote.js" async>
                        {!! json_encode(['symbol' => $q['symbol'], 'width' => '100%', 'colorTheme' => 'dark', 'isTransparent' => true, 'locale' => 'en']) !!}
                        </script>
                    </div>
                </div>
            @endforeach

            @if($rate)
                @foreach($products as $title => $tiles)
                    <h2 class="lc-rates__title">{{ $title }}</h2>
                    <div class="lc-tiles">
                        @foreach($tiles as [$label, $value])
                            <div class="lc-tile">
                                <span class="lc-tile__label">{{ $label }}</span>
                                <span class="lc-tile__value">{{ RateBook::inr($value) }}</span>
                            </div>
                        @endforeach
                    </div>
                @endforeach
                <p class="lc-note">
                    Gold and silver products are our rates, published {{ $rate->published_at->format('d M Y, g:i A') }}@if($rate->is_sample)<span class="lc-sample">Sample data</span>@endif.
                    “With GST” adds 3%. Making charges are extra.
                    The charts show international spot prices from TradingView; spot in rupees excludes import duty and GST, so it is lower than Indian rates.
                    <a href="{{ route('contact') }}">Ask for a quote</a>.
                </p>
            @else
                <p class="lc-note">Our gold and silver rates will appear here once they are published.</p>
            @endif
        </aside>
    </main>

    <script>
    (function () {
        var body = document.body, grid = document.getElementById('lcGrid');

        // Hide rates: give the charts the full width
        var toggle = document.getElementById('lcToggleRates');
        toggle.addEventListener('click', function () {
            var hidden = body.classList.toggle('lc--norates');
            toggle.textContent = hidden ? 'Show rates' : 'Hide rates';
            toggle.setAttribute('aria-pressed', hidden);
        });

        // Focus: one chart fills the grid; press again to go back to all four
        document.querySelectorAll('[data-focus]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var chart = document.getElementById(btn.dataset.focus);
                var on = !chart.classList.contains('is-focus');
                document.querySelectorAll('.lc-chart').forEach(function (c) { c.classList.remove('is-focus'); });
                document.querySelectorAll('[data-focus]').forEach(function (b) { b.textContent = 'Focus'; b.setAttribute('aria-pressed', 'false'); });
                grid.classList.toggle('is-focused', on);
                if (on) {
                    chart.classList.add('is-focus');
                    btn.textContent = 'All charts';
                    btn.setAttribute('aria-pressed', 'true');
                }
                chart.scrollIntoView({ block: 'start' });
            });
        });

        // Full screen (the whole dashboard)
        var fs = document.getElementById('lcFullscreen');
        if (!document.documentElement.requestFullscreen) { fs.hidden = true; }
        fs.addEventListener('click', function () {
            if (document.fullscreenElement) { document.exitFullscreen(); } else { document.documentElement.requestFullscreen(); }
        });
        document.addEventListener('fullscreenchange', function () {
            fs.textContent = document.fullscreenElement ? 'Exit full screen' : 'Full screen';
        });

        // Refresh: reload the page (charts, quotes and our latest published rates)
        document.getElementById('lcRefresh').addEventListener('click', function () { location.reload(); });
    })();
    </script>
</body>
</html>
