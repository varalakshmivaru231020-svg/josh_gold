{{--
    Live charts dashboard, laid out like allindiabullion.com/charts: four live candle charts drawn on our own page
    (rates/_chart-card, from the spot prices the server saves every minute) and a rates panel (rates/_live-panel).
    No buy/sell signals: we only show market prices and our own rates.
--}}
@php
    use App\Support\LiveCharts;
@endphp
@extends('layouts.bullion')

@section('title', 'Live Gold & Silver Charts | ' . config('company.name'))
@section('description', 'Live gold and silver charts (spot XAU/USD, XAG/USD and in rupees) with live spot prices and our published gold and silver rates.')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/live-charts.css') }}?v={{ filemtime(public_path('css/live-charts.css')) }}">
@endpush

@section('content')
<div class="lc lc-page" id="lcPage">
    <div class="lc-bar">
        <h1 class="lc-title">Live charts</h1>
        <a class="lc-btn lc-hide-sm" href="{{ route('live-rates') }}">Rate history</a>
        <span class="lc-spacer"></span>
        <button type="button" class="lc-btn lc-btn--light" id="lcToggleRates" aria-pressed="false">Hide rates</button>
        <button type="button" class="lc-btn lc-hide-sm" id="lcFullscreen">Full screen</button>
        <span class="lc-btn lc-status lc-hide-sm" title="Live international spot prices, saved every minute">
            <span class="lc-dot lc-dot--live" aria-hidden="true"></span>Charts live
        </span>
        <button type="button" class="lc-btn lc-btn--gold" id="lcRefresh">Refresh</button>
    </div>
    <div class="lc-main">
        <section class="lc-grid" id="lcGrid" aria-label="Live charts">
            @foreach(LiveCharts::CHARTS as $key => $c)
                @include('rates._chart-card', ['dashboard' => true])
            @endforeach
        </section>

        @include('rates._live-panel')
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/vendor/echarts.min.js') }}"></script>
<script src="{{ asset('js/live-charts.js') }}?v={{ filemtime(public_path('js/live-charts.js')) }}" defer></script>

    <script>
    (function () {
        var page = document.getElementById('lcPage'), grid = document.getElementById('lcGrid');

        // Hide rates: give the charts the full width
        var toggle = document.getElementById('lcToggleRates');
        toggle.addEventListener('click', function () {
            var hidden = page.classList.toggle('lc--norates');
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

        // Full screen (the dashboard, without the site header and footer)
        var fs = document.getElementById('lcFullscreen');
        if (!page.requestFullscreen) { fs.hidden = true; }
        fs.addEventListener('click', function () {
            if (document.fullscreenElement) { document.exitFullscreen(); } else { page.requestFullscreen(); }
        });
        document.addEventListener('fullscreenchange', function () {
            fs.textContent = document.fullscreenElement ? 'Exit full screen' : 'Full screen';
        });

        // Refresh: reload the page (charts, quotes and our latest published rates)
        document.getElementById('lcRefresh').addEventListener('click', function () { location.reload(); });
    })();
    </script>
@endpush
