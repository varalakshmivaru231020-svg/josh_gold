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

<section class="jb-section jb-section--white">
    <div class="jb-wrap">
        <p class="jb-eyebrow">Good to know</p>
        <h2 class="jb-section__title">About these charts</h2>
        <div class="jb-faq">
            <details>
                <summary>What do the charts show?</summary>
                <p>The rates we have published ourselves: 24K gold per 10 grams and 999 silver per kilogram. They are not exchange or international spot prices.</p>
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
@endpush
