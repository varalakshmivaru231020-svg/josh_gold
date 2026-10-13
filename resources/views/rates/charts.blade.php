@extends('layouts.lawfirm')

@section('title', 'Gold & Silver Rate Charts | ' . config('company.name'))
@section('meta_description', 'Interactive charts of our published gold and silver rates, from one week to the full history.')

@php use App\Support\RateBook; @endphp

@section('content')
@include('layouts.partials.page-head', [
    'eyebrow' => 'Charts',
    'title'   => 'Gold & silver',
    'accent'  => 'rate charts',
    'sub'     => 'Every morning and evening rate we have published, on one chart per metal. Zoom from one week to the full history, or pick your own dates.',
    'crumbs'  => [['Charts']],
])

@if($rate)
<section class="jb-section">
    <div class="jb-wrap">
        @include('rates._meta')
        <div class="jb-pulse jb-pulse--3" style="margin-top: 18px;">
            <a class="jb-pulse__tile" href="{{ route('rates.gold') }}">
                <span class="jb-pulse__label">24K gold · 10 g</span>
                <span class="jb-pulse__value">{{ RateBook::inr($rate->goldRate('24K', 10)) }}</span>
            </a>
            <a class="jb-pulse__tile" href="{{ route('rates.gold') }}">
                <span class="jb-pulse__label">22K gold · 10 g</span>
                <span class="jb-pulse__value">{{ RateBook::inr($rate->goldRate('22K', 10)) }}</span>
            </a>
            <a class="jb-pulse__tile" href="{{ route('rates.silver') }}">
                <span class="jb-pulse__label">Silver 999 · 1 kg</span>
                <span class="jb-pulse__value">{{ RateBook::inr($rate->silverRate(1000)) }}</span>
            </a>
        </div>
    </div>
</section>

<section class="jb-section jb-section--white" id="gold-chart">
    <div class="jb-wrap">
        <p class="jb-eyebrow">Gold</p>
        <h2 class="jb-section__title" style="margin-bottom: 14px;">24K gold, per 10 grams</h2>
        @include('live-rates._chart', [
            'key' => 'gold', 'title' => 'Gold Rates in India (INR/10gm)', 'purity' => '24K', 'unit' => '10gm',
            'line' => '#B7932F', 'strip' => '#DDC37C', 'accent' => '#DDA20D',
        ])
    </div>
</section>

<section class="jb-section" id="silver-chart">
    <div class="jb-wrap">
        <p class="jb-eyebrow">Silver</p>
        <h2 class="jb-section__title" style="margin-bottom: 14px;">999 silver, per kilogram</h2>
        @include('live-rates._chart', [
            'key' => 'silver', 'title' => 'Silver Rates in India (INR/1kg)', 'purity' => '999', 'unit' => '1kg',
            'line' => '#7F7F7F', 'strip' => '#CECECE', 'accent' => '#7D7D7D',
        ])
    </div>
</section>
@include('rates._chart-scripts')
@else
<section class="jb-section"><div class="jb-wrap"><div class="jb-panel"><p class="jb-muted mb-0">Charts will appear here once rates are published.</p></div></div></section>
@endif

{{-- International spot charts from TradingView. Third-party iframes, so they load only with
     embedded-content consent or when the visitor presses "Load chart" (public/js/consent.js). --}}
@php
    $tvChart = fn (string $id, string $symbol) => 'https://s.tradingview.com/widgetembed/?' . http_build_query([
        'frameElementId' => $id, 'symbol' => $symbol, 'interval' => 'D', 'hidesidetoolbar' => 1, 'symboledit' => 0,
        'saveimage' => 0, 'toolbarbg' => 'f8f4ea', 'studies' => '[]', 'theme' => 'light', 'style' => 2,
        'timezone' => 'Asia/Kolkata', 'withdateranges' => 1, 'locale' => 'en',
    ], '', '&', PHP_QUERY_RFC3986);
    $usdCharts = [
        ['key' => 'xau', 'metal' => 'Gold',   'pair' => 'XAU/USD', 'src' => $tvChart('tv_xau', 'OANDA:XAUUSD'), 'link' => 'https://www.tradingview.com/symbols/XAUUSD/'],
        ['key' => 'xag', 'metal' => 'Silver', 'pair' => 'XAG/USD', 'src' => $tvChart('tv_xag', 'OANDA:XAGUSD'), 'link' => 'https://www.tradingview.com/symbols/XAGUSD/'],
    ];
@endphp
<section class="jb-section jb-section--white" id="usd-charts" aria-labelledby="usd-charts-title">
    <div class="jb-wrap">
        <p class="jb-eyebrow">International</p>
        <h2 class="jb-section__title" id="usd-charts-title">Gold &amp; silver in US dollars</h2>
        <p class="jb-section__sub">Live international spot prices per troy ounce (XAU/USD and XAG/USD), from TradingView. These are global market prices, not our published rates.</p>

        <div class="jb-usd-charts">
            @foreach($usdCharts as $chart)
                <div class="jb-usd-chart">
                    <h3 class="jb-usd-chart__title" id="usd-{{ $chart['key'] }}-title">{{ $chart['metal'] }} <span>{{ $chart['pair'] }} · per troy ounce</span></h3>
                    <div class="jb-embed" data-consent-embed
                         data-embed-src="{{ $chart['src'] }}"
                         data-embed-title="{{ $chart['metal'] }} spot price in US dollars ({{ $chart['pair'] }}), chart by TradingView"
                         data-embed-height="420">
                        <div class="jb-embed__placeholder">
                            <span class="jb-embed__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" focusable="false"><path d="M3 3v18h18"/><path d="m7 15 4-5 3 3 5-7"/></svg></span>
                            <p class="jb-embed__title">{{ $chart['metal'] }} · {{ $chart['pair'] }}</p>
                            <p>This chart is provided by TradingView, which may set cookies.</p>
                            <button type="button" class="jb-btn jb-btn--primary jb-btn--sm" data-embed-load aria-describedby="usd-{{ $chart['key'] }}-title">Load chart</button>
                        </div>
                    </div>
                    <p class="jb-usd-chart__credit">
                        <a href="{{ $chart['link'] }}" target="_blank" rel="noopener">{{ $chart['pair'] }} chart by TradingView<span class="jbc-visually-hidden"> (opens in a new tab)</span></a>
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="jb-section">
    <div class="jb-wrap">
        <p class="jb-eyebrow">Good to know</p>
        <h2 class="jb-section__title">About these charts</h2>
        <div class="jb-faq">
            <details>
                <summary>What do the charts show?</summary>
                <p>The gold and silver rate charts show the rates we have published ourselves: 24K gold per 10 grams and 999 silver per kilogram. They are not exchange or international spot prices. The two US dollar charts are international spot prices from TradingView.</p>
            </details>
            <details>
                <summary>How does the US dollar spot price relate to the Indian rate?</summary>
                <p>Gold and silver are traded worldwide in US dollars per troy ounce. Prices in India broadly follow that international price, converted into rupees at the current exchange rate, with import duty and GST added on top and local premiums or discounts depending on demand. So the Indian rate can move when the rupee moves, even on a day the dollar price is flat, and it is normally higher than a straight conversion of the spot price.</p>
            </details>
            <details>
                <summary>How do I change the period?</summary>
                <p>Use the zoom buttons above each chart, type dates into the From and To boxes, or drag the handles on the small strip under the chart.</p>
            </details>
            <details>
                <summary>How often do they update?</summary>
                <p>Each time we publish a rate, normally every morning and evening on working days.</p>
            </details>
        </div>
    </div>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/rate-pages.css') }}?v={{ filemtime(public_path('css/rate-pages.css')) }}">
<style>
    /* US dollar spot charts: side by side on desktop, stacked on phones */
    .jb-usd-charts { display: grid; gap: 22px; margin-top: 20px; }
    @media (min-width: 992px) { .jb-usd-charts { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 24px; } }
    .jb-usd-chart { min-width: 0; }
    .jb-usd-chart__title { margin: 0 0 10px !important; font-size: 18px; font-weight: 600; }
    .jb-usd-chart__title span { margin-left: 6px; color: var(--jb-muted); font: 600 11px/1 var(--jb-sans); letter-spacing: .12em; text-transform: uppercase; }
    .jb-usd-chart__credit { margin: 10px 2px 0 !important; font-size: 12.5px; }
    .jb-usd-chart__credit a { color: var(--jb-gold-strong); font-weight: 600; text-decoration: underline; text-underline-offset: 3px; }
    .jb-usd-chart__credit a:hover { color: var(--jb-gold-deep); }
</style>
@endpush
