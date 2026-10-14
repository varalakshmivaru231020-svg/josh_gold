{{--
    One live chart, full size (/charts/xauusd …), laid out like allindiabullion.com/charts/xauusd, with the live rates panel.
    No buy/sell signals: market prices and our own rates only.
--}}
@php
    use App\Support\LiveCharts;

    $spot = \Illuminate\Support\Facades\Cache::get('spot.prices.last', []);
    $spotTime = isset($spot['updated_at']) ? \Carbon\Carbon::parse($spot['updated_at'])->setTimezone(\App\Models\MetalRate::TIMEZONE)->format('g:i:s A') : '—';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0b0a08">
    <title>{{ $chart['name'] }} Live Chart ({{ strtok($chart['sub'], ' ·') }}) | {{ config('company.name') }}</title>
    <meta name="description" content="Live {{ strtolower($chart['name']) }} chart ({{ $chart['sub'] }}) with live spot prices and our published gold and silver rates.">
    @include('layouts.partials.jb-head')
    <link rel="stylesheet" href="{{ asset('css/live-charts.css') }}?v={{ filemtime(public_path('css/live-charts.css')) }}">
</head>
<body class="lc">
    <header class="lc-bar">
        <a class="lc-brand" href="{{ route('home') }}"><img src="{{ versioned_asset('images/brand/josha-logo-header.webp') }}" alt="{{ config('company.name') }}, home" width="68" height="56"></a>
        <label class="jb-sr" for="lcSymbol">Chart</label>
        <select class="lc-select" id="lcSymbol" onchange="location.href = this.value">
            @foreach(LiveCharts::CHARTS as $key => $c)
                <option value="{{ route('rates.chart', $key) }}" @selected($key === $slug)>{{ $c['name'] }} · {{ strtok($c['sub'], ' ·') }}</option>
            @endforeach
        </select>
        <span class="lc-updated lc-hide-sm">Updated <b data-spot-time>{{ $spotTime }}</b></span>
        <span class="lc-spacer"></span>
        <a class="lc-btn" href="{{ route('rates.charts') }}">All charts</a>
        <button type="button" class="lc-btn lc-btn--light" id="lcRefresh">Refresh</button>
        <button type="button" class="lc-btn" id="lcToggleDetails" aria-pressed="false">Hide details</button>
        <button type="button" class="lc-btn lc-hide-sm" id="lcFullscreen">Full</button>
    </header>

    <main class="lc-main" id="main-content">
        <section class="lc-one" aria-label="{{ $chart['name'] }} live chart">
            <article class="lc-chart">
                <div class="lc-chart__head">
                    <span class="lc-dot lc-dot--live" aria-hidden="true"></span>
                    <h1 class="lc-chart__name">{{ $chart['name'] }}<span class="lc-chart__sub">{{ $chart['sub'] }} · live</span></h1>
                </div>
                <div class="lc-chart__frame">
                    <iframe id="lcFrame" src="{{ LiveCharts::embedUrl($chart['symbol']) }}" title="{{ $chart['name'] }} live chart ({{ $chart['sub'] }})"></iframe>
                </div>
            </article>
        </section>

        @include('rates._live-panel')
    </main>

    <script>
    (function () {
        var body = document.body;

        var details = document.getElementById('lcToggleDetails');
        details.addEventListener('click', function () {
            var hidden = body.classList.toggle('lc--nodetails');
            details.textContent = hidden ? 'Show details' : 'Hide details';
            details.setAttribute('aria-pressed', hidden);
        });

        var fs = document.getElementById('lcFullscreen');
        if (!document.documentElement.requestFullscreen) { fs.hidden = true; }
        fs.addEventListener('click', function () {
            if (document.fullscreenElement) { document.exitFullscreen(); } else { document.documentElement.requestFullscreen(); }
        });
        document.addEventListener('fullscreenchange', function () { fs.textContent = document.fullscreenElement ? 'Exit full' : 'Full'; });

        document.getElementById('lcRefresh').addEventListener('click', function () { location.reload(); });
    })();
    </script>
</body>
</html>
