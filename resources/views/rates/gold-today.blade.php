@extends('layouts.lawfirm')

@section('title', 'Gold Rate Today in India — 24K, 22K, 18K | ' . config('company.name'))
@section('meta_description', "Today's 24K, 22K and 18K gold rate per gram, 8 grams and 10 grams, published every morning and evening by " . config('company.name') . '.')

@php use App\Support\RateBook; @endphp

@section('content')
@include('layouts.partials.page-head', [
    'eyebrow' => 'Reference rates · India',
    'title'   => 'Gold rate today',
    'accent'  => $rate ? 'on ' . $rate->published_at->format('j F Y') : 'in India',
    'sub'     => 'Our latest 24K, 22K and 18K gold rates per gram, 8 grams (one sovereign), 10 grams and tola, published every morning and evening.',
    'crumbs'  => [['Gold rate today']],
])

@if($rate)
<section class="jb-section">
    <div class="jb-wrap">
        @include('rates._meta')

        <div class="jb-section__head" style="margin-top: 22px;">
            <div>
                <p class="jb-eyebrow">Reference rates</p>
                <h2 class="jb-section__title">Gold price by purity</h2>
            </div>
            <a class="jb-link" href="{{ route('home') }}#by-weight">Coins &amp; bars by weight →</a>
        </div>
        @include('rates._table', [
            'metal' => 'gold',
            'rows' => ['24K' => '24K (999)', '22K' => '22K (916)', '20K' => '20K (833)', '18K' => '18K (750)', '14K' => '14K (585)'],
            'weights' => [1 => '1 g', 8 => '8 g', 10 => '10 g', '11.6638' => '1 tola', 100 => '100 g'],
            'highlight' => 10,
        ])
    </div>
</section>

<section class="jb-section">
    <div class="jb-wrap">
        <div class="jb-section__head">
            <div>
                <p class="jb-eyebrow">City by city</p>
                <h2 class="jb-section__title">Gold rate today in your city</h2>
            </div>
            <a class="jb-link" href="{{ route('rates.cities') }}">All cities →</a>
        </div>
        @include('rates._cities', ['metal' => 'gold'])
    </div>
</section>

<section class="jb-section">
    <div class="jb-wrap">
        <div class="jb-section__head">
            <div>
                <p class="jb-eyebrow">Recent trend</p>
                <h2 class="jb-section__title">24K gold, last 10 days</h2>
            </div>
            <a class="jb-link" href="{{ route('rates.pulse') }}">Gold Pulse →</a>
        </div>
        @include('rates._trend', ['metal' => 'gold', 'purity' => '24K', 'grams' => 10, 'unit' => 'per 10 g'])
    </div>
</section>

<section class="jb-section" id="chart">
    <div class="jb-wrap">
        <div class="jb-section__head">
            <div>
                <p class="jb-eyebrow">Chart</p>
                <h2 class="jb-section__title">Gold rate history</h2>
            </div>
            <a class="jb-link" href="{{ route('rates.charts') }}">All charts →</a>
        </div>
        @include('live-rates._chart', [
            'key' => 'gold', 'title' => 'Gold Rates in India (INR/10gm)', 'purity' => '24K', 'unit' => '10gm',
            'line' => '#B7932F', 'strip' => '#DDC37C', 'accent' => '#DDA20D',
        ])
    </div>
</section>
@include('rates._chart-scripts')
@else
<section class="jb-section"><div class="jb-wrap"><div class="jb-panel"><p class="jb-muted mb-0">Today's gold rate will appear here as soon as it is published.</p></div></div></section>
@endif

<section class="jb-section">
    <div class="jb-wrap">
        <p class="jb-eyebrow">Good to know</p>
        <h2 class="jb-section__title">Gold rate questions</h2>
        <div class="jb-faq">
            <details>
                <summary>What does the 24K gold rate mean?</summary>
                <p>It is the price of 99.9% pure gold. Lower purities are worked out from it by their gold content, so 22K is 22/24 of the 24K price and 18K is 18/24.</p>
            </details>
            <details>
                <summary>Why do we show 8 grams?</summary>
                <p>Eight grams is one sovereign (pavan), the unit many families in South India use when they buy gold coins and jewellery.</p>
            </details>
            <details>
                <summary>Is this the price I pay for jewellery?</summary>
                <p>No. This is the value of the metal. Jewellery adds making charges, and 3% GST applies on gold. Ask our desk for a full quote before you pay.</p>
            </details>
            <details>
                <summary>When is the rate updated?</summary>
                <p>We publish a morning rate and an evening rate on working days. The time of the rate on this page is shown above the table.</p>
            </details>
        </div>
    </div>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/rate-pages.css') }}?v={{ filemtime(public_path('css/rate-pages.css')) }}">
@endpush
