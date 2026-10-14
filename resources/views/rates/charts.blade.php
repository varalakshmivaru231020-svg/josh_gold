{{--
    Live charts dashboard, laid out like allindiabullion.com/charts: four live candle charts (TradingView, international
    spot prices) and a rates panel. The panel (rates/_live-panel) shows live spot prices and our own published rates
    in blue (up) and red (down) boxes.
    No buy/sell signals: we only show market prices and our own rates.
--}}
@php
    use App\Support\LiveCharts;
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
    <link rel="stylesheet" href="{{ asset('css/live-charts.css') }}?v={{ filemtime(public_path('css/live-charts.css')) }}">
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
            @foreach(LiveCharts::CHARTS as $key => $c)
                <article class="lc-chart" id="chart-{{ $key }}">
                    <div class="lc-chart__head">
                        <span class="lc-dot lc-dot--live" aria-hidden="true"></span>
                        <h2 class="lc-chart__name">{{ $c['name'] }}<span class="lc-chart__sub">{{ $c['sub'] }}</span></h2>
                        <span class="lc-spacer"></span>
                        <button type="button" class="lc-btn" data-focus="chart-{{ $key }}" aria-pressed="false">Focus</button>
                        <a class="lc-btn lc-btn--light" href="{{ route('rates.chart', $key) }}">Open full</a>
                    </div>
                    <div class="lc-chart__frame">
                        <iframe src="{{ LiveCharts::embedUrl($c['symbol']) }}" title="{{ $c['name'] }} live chart ({{ $c['sub'] }})" loading="eager" allowtransparency="true"></iframe>
                    </div>
                </article>
            @endforeach
        </section>

        @include('rates._live-panel')
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
