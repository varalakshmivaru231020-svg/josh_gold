{{--
    One live chart, full size (/charts/xauusd …), laid out like allindiabullion.com/charts/xauusd, with the live rates panel.
    No buy/sell signals: market prices and our own rates only.
--}}
@php
    use App\Support\LiveCharts;

    $spot = \Illuminate\Support\Facades\Cache::get('spot.prices.last', []);
    $spotTime = isset($spot['updated_at']) ? \Carbon\Carbon::parse($spot['updated_at'])->setTimezone(\App\Models\MetalRate::TIMEZONE)->format('g:i:s A') : '—';
@endphp
@extends('layouts.bullion')

@section('title', $chart['name'] . ' Live Chart (' . $chart['pair'] . ') | ' . config('company.name'))
@section('description', 'Live ' . strtolower($chart['name']) . ' chart (' . $chart['sub'] . ') with live spot prices and our published gold and silver rates.')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/live-charts.css') }}?v={{ filemtime(public_path('css/live-charts.css')) }}">
@endpush

@section('content')
<div class="lc lc-page" id="lcPage">
    <div class="lc-bar">
        <label class="jb-sr" for="lcSymbol">Chart</label>
        <select class="lc-select" id="lcSymbol" onchange="location.href = this.value">
            @foreach(LiveCharts::CHARTS as $key => $c)
                <option value="{{ route('rates.chart', $key) }}" @selected($key === $slug)>{{ $c['name'] }} · {{ $c['pair'] }}</option>
            @endforeach
        </select>
        <span class="lc-updated lc-hide-sm">Updated <b data-spot-time>{{ $spotTime }}</b></span>
        <span class="lc-spacer"></span>
        <a class="lc-btn" href="{{ route('rates.charts') }}">All charts</a>
        <button type="button" class="lc-btn lc-btn--light" id="lcRefresh">Refresh</button>
        <button type="button" class="lc-btn" id="lcToggleDetails" aria-pressed="false">Hide details</button>
        <button type="button" class="lc-btn lc-hide-sm" id="lcFullscreen">Full</button>
    </div>

    <div class="lc-main">
        <section class="lc-one" aria-label="{{ $chart['name'] }} live chart">
            @include('rates._chart-card', ['key' => $slug, 'c' => $chart, 'heading' => 'h1'])
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
        var page = document.getElementById('lcPage');

        var details = document.getElementById('lcToggleDetails');
        details.addEventListener('click', function () {
            var hidden = page.classList.toggle('lc--nodetails');
            details.textContent = hidden ? 'Show details' : 'Hide details';
            details.setAttribute('aria-pressed', hidden);
        });

        var fs = document.getElementById('lcFullscreen');
        if (!page.requestFullscreen) { fs.hidden = true; }
        fs.addEventListener('click', function () {
            if (document.fullscreenElement) { document.exitFullscreen(); } else { page.requestFullscreen(); }
        });
        document.addEventListener('fullscreenchange', function () { fs.textContent = document.fullscreenElement ? 'Exit full' : 'Full'; });

        document.getElementById('lcRefresh').addEventListener('click', function () { location.reload(); });
    })();
    </script>
@endpush
