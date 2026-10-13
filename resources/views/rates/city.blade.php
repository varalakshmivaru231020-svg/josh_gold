@extends('layouts.lawfirm')

@section('title', 'Gold Rate Today in ' . $cityName . ' — 24K, 22K & silver | ' . config('company.name'))
@section('meta_description', "Today's 24K and 22K gold and 999 silver reference rate for {$cityName}, {$stateName}, published every morning and evening by " . config('company.name') . '.')

@section('content')
@include('layouts.partials.page-head', [
    'eyebrow' => $stateName . ' · reference rates',
    'title'   => 'Gold rate today in',
    'accent'  => $cityName,
    'sub'     => "Our latest 24K, 22K and 18K gold and 999 silver rates for {$cityName}, published every morning and evening.",
    'crumbs'  => [['City rates', route('rates.cities')], [$cityName]],
])

@if($rate)
<section class="jb-section">
    <div class="jb-wrap">
        @include('rates._meta')

        <div class="jb-receipt" style="margin-top: 18px;">
            <p class="jb-muted mb-0">
                {{ config('company.name') }} publishes one reference rate for every city, including {{ $cityName }}.
                Jewellers in {{ $cityName }} add their own local premium, making charges and 3% GST, so a shop's price will be higher than the metal value below.
            </p>
        </div>

        <div class="jb-section__head" style="margin-top: 26px;">
            <div>
                <p class="jb-eyebrow">Gold</p>
                <h2 class="jb-section__title">Gold rate in {{ $cityName }} by purity</h2>
            </div>
            <a class="jb-link" href="{{ route('rates.gold') }}">India gold rate →</a>
        </div>
        @include('rates._table', [
            'metal' => 'gold',
            'rows' => ['24K' => '24K (999)', '22K' => '22K (916)', '18K' => '18K (750)'],
            'weights' => [1 => '1 g', 8 => '8 g', 10 => '10 g', '11.6638' => '1 tola', 100 => '100 g'],
            'highlight' => 10,
        ])

        <div class="jb-section__head" style="margin-top: 26px;">
            <div>
                <p class="jb-eyebrow">Silver</p>
                <h2 class="jb-section__title">Silver rate in {{ $cityName }}</h2>
            </div>
            <a class="jb-link" href="{{ route('rates.silver') }}">India silver rate →</a>
        </div>
        @include('rates._table', [
            'metal' => 'silver',
            'rows' => ['999' => '999 fine', '925' => '925 sterling'],
            'weights' => [1 => '1 g', 10 => '10 g', 100 => '100 g', 1000 => '1 kg'],
            'highlight' => 1000,
        ])
    </div>
</section>

<section class="jb-section">
    <div class="jb-wrap">
        <div class="jb-section__head">
            <div>
                <p class="jb-eyebrow">Recent trend</p>
                <h2 class="jb-section__title">24K gold in {{ $cityName }}, last 10 days</h2>
            </div>
        </div>
        @include('rates._trend', ['metal' => 'gold', 'purity' => '24K', 'grams' => 10, 'unit' => 'per 10 g'])
    </div>
</section>

<section class="jb-section">
    <div class="jb-wrap">
        <div class="jb-section__head">
            <div>
                <p class="jb-eyebrow">Other cities</p>
                <h2 class="jb-section__title">Gold rate in other cities</h2>
            </div>
            <a class="jb-link" href="{{ route('rates.cities') }}">All cities →</a>
        </div>
        @include('rates._cities', ['metal' => 'gold', 'except' => $cityKey])
    </div>
</section>
@else
<section class="jb-section"><div class="jb-wrap"><div class="jb-panel"><p class="jb-muted mb-0">Rates for {{ $cityName }} will appear here as soon as they are published.</p></div></div></section>
@endif

<section class="jb-section">
    <div class="jb-wrap">
        <p class="jb-eyebrow">Good to know</p>
        <h2 class="jb-section__title">Gold rate in {{ $cityName }}: questions</h2>
        <div class="jb-faq">
            <details>
                <summary>What moves the gold rate in {{ $cityName }}?</summary>
                <p>Mainly the international gold price, the rupee–dollar exchange rate and India's import duty, which affect every city alike. Local demand and each jeweller's costs explain most of the difference between shops.</p>
            </details>
            <details>
                <summary>Why might a jeweller in {{ $cityName }} quote more?</summary>
                <p>The rates here are the value of the metal only. A shop adds its premium, making charges and 3% GST, and stones are priced separately.</p>
            </details>
            <details>
                <summary>Which purity should I compare?</summary>
                <p>Compare like with like: 24K for coins and bars, 22K (916) for most jewellery. Check the hallmark and HUID on gold jewellery before you buy.</p>
            </details>
        </div>
    </div>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/rate-pages.css') }}?v={{ filemtime(public_path('css/rate-pages.css')) }}">
@endpush
