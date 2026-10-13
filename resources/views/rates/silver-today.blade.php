@extends('layouts.lawfirm')

@section('title', 'Silver Rate Today in India — per gram and per kg | ' . config('company.name'))
@section('meta_description', "Today's 999 and 925 silver rate per gram, 10 grams, 100 grams and kilogram, published every morning and evening by " . config('company.name') . '.')

@section('content')
@include('layouts.partials.page-head', [
    'eyebrow' => 'Reference rates · India',
    'title'   => 'Silver rate today',
    'accent'  => $rate ? 'on ' . $rate->published_at->format('j F Y') : 'in India',
    'sub'     => 'Our latest 999 fine and 925 sterling silver rates per gram, 10 grams, 100 grams and kilogram, published every morning and evening.',
    'crumbs'  => [['Silver rate today']],
])

@if($rate)
<section class="jb-section">
    <div class="jb-wrap">
        @include('rates._meta')

        <div class="jb-section__head" style="margin-top: 22px;">
            <div>
                <p class="jb-eyebrow">Reference rates</p>
                <h2 class="jb-section__title">Silver price by grade</h2>
            </div>
            <a class="jb-link" href="{{ route('home') }}#by-weight">Coins &amp; bars by weight →</a>
        </div>
        @include('rates._table', [
            'metal' => 'silver',
            'rows' => ['999' => '999 fine', '925' => '925 sterling', '900' => '900 coin', '800' => '800'],
            'weights' => [1 => '1 g', 10 => '10 g', 100 => '100 g', 1000 => '1 kg'],
            'highlight' => 1000,
        ])
    </div>
</section>

<section class="jb-section">
    <div class="jb-wrap">
        <div class="jb-section__head">
            <div>
                <p class="jb-eyebrow">City by city</p>
                <h2 class="jb-section__title">Silver rate today in your city</h2>
            </div>
            <a class="jb-link" href="{{ route('rates.cities') }}">All cities →</a>
        </div>
        @include('rates._cities', ['metal' => 'silver'])
    </div>
</section>

<section class="jb-section">
    <div class="jb-wrap">
        <div class="jb-section__head">
            <div>
                <p class="jb-eyebrow">Recent trend</p>
                <h2 class="jb-section__title">999 silver, last 10 days</h2>
            </div>
            <a class="jb-link" href="{{ route('rates.pulse') }}">Gold Pulse →</a>
        </div>
        @include('rates._trend', ['metal' => 'silver', 'purity' => '999', 'grams' => 1000, 'unit' => 'per kg'])
    </div>
</section>

<section class="jb-section" id="chart">
    <div class="jb-wrap">
        <div class="jb-section__head">
            <div>
                <p class="jb-eyebrow">Chart</p>
                <h2 class="jb-section__title">Silver rate history</h2>
            </div>
            <a class="jb-link" href="{{ route('rates.charts') }}">All charts →</a>
        </div>
        @include('live-rates._chart', [
            'key' => 'silver', 'title' => 'Silver Rates in India (INR/1kg)', 'purity' => '999', 'unit' => '1kg',
            'line' => '#7F7F7F', 'strip' => '#CECECE', 'accent' => '#7D7D7D',
        ])
    </div>
</section>
@include('rates._chart-scripts')
@else
<section class="jb-section"><div class="jb-wrap"><div class="jb-panel"><p class="jb-muted mb-0">Today's silver rate will appear here as soon as it is published.</p></div></div></section>
@endif

<section class="jb-section">
    <div class="jb-wrap">
        <p class="jb-eyebrow">Good to know</p>
        <h2 class="jb-section__title">Silver rate questions</h2>
        <div class="jb-faq">
            <details>
                <summary>What is the difference between 999 and 925 silver?</summary>
                <p>999 silver is 99.9% pure and is used for bars and coins. 925 sterling is 92.5% silver mixed with other metals to make it harder, which suits jewellery and articles.</p>
            </details>
            <details>
                <summary>Why is silver quoted per kilogram?</summary>
                <p>Silver costs far less per gram than gold, so the trade quotes it per kilogram. The table also shows per gram, 10 grams and 100 grams.</p>
            </details>
            <details>
                <summary>Does silver move more than gold?</summary>
                <p>Usually, yes. Silver is also an industrial metal, so its price tends to swing more from day to day than gold. The 10-day table above shows recent moves.</p>
            </details>
            <details>
                <summary>Is this the final price?</summary>
                <p>No. It is the metal value. Making charges and 3% GST are added to your final quote.</p>
            </details>
        </div>
    </div>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/rate-pages.css') }}?v={{ filemtime(public_path('css/rate-pages.css')) }}">
@endpush
