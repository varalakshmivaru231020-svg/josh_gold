@extends('layouts.lawfirm')

@section('title', 'Gold Pulse — gold & silver market board | ' . config('company.name'))
@section('meta_description', 'Gold and silver prices with their daily, weekly, monthly and yearly change, and the gold–silver ratio, from our published rates.')

@php
    use App\Models\MetalRate;
    use App\Support\RateBook;

    // [label, price closure] for each board row
    $goldRows = [
        ['24K gold · 10 g', fn ($r) => $r->goldRate('24K', 10)],
        ['22K gold · 10 g', fn ($r) => $r->goldRate('22K', 10)],
        ['18K gold · 10 g', fn ($r) => $r->goldRate('18K', 10)],
    ];
    $silverRows = [
        ['Silver 999 · 1 kg', fn ($r) => $r->silverRate(1000)],
        ['Silver 925 · 1 kg', fn ($r) => $r->priceFor('silver', '925', 1000)],
    ];
    $ratio = fn ($r) => $r && $r->silver_999_rate > 0 ? $r->gold_999_rate / $r->silver_999_rate : null;
@endphp

@section('content')
@include('layouts.partials.page-head', [
    'eyebrow' => 'Market board',
    'title'   => 'Gold',
    'accent'  => 'Pulse',
    'sub'     => 'Where gold and silver stand today and how far they have moved over a day, a week, a month and a year, all from our own published rates.',
    'crumbs'  => [['Gold Pulse']],
])

@if($rate)
<section class="jb-section">
    <div class="jb-wrap">
        @include('rates._meta')

        @foreach([['Indian gold', $goldRows], ['Indian silver', $silverRows]] as [$heading, $rows])
            <h2 class="jb-section__title jb-pulse__heading">{{ $heading }}</h2>
            <div class="jb-pulse">
                @foreach($rows as [$label, $price])
                    @php $now = $price($rate); @endphp
                    <div class="jb-pulse__tile">
                        <span class="jb-pulse__label">{{ $label }}</span>
                        <span class="jb-pulse__value">{{ RateBook::inr($now) }}</span>
                        <span class="jb-pulse__changes">
                            @foreach($past as $period => $then)
                                @php $pct = $then ? RateBook::change($now, $price($then)) : null; @endphp
                                <span>
                                    <em>{{ $period }}</em>
                                    @if($pct === null)
                                        <b class="jb-muted">—</b>
                                    @else
                                        <b class="jb-change jb-change--{{ $pct >= 0 ? 'up' : 'down' }}">{{ $pct >= 0 ? '▲' : '▼' }} {{ number_format(abs($pct), 2) }}%</b>
                                    @endif
                                </span>
                            @endforeach
                        </span>
                    </div>
                @endforeach
            </div>
        @endforeach

        <h2 class="jb-section__title jb-pulse__heading">Gold–silver ratio</h2>
        <div class="jb-pulse jb-pulse--4">
            <div class="jb-pulse__tile">
                <span class="jb-pulse__label">Today</span>
                <span class="jb-pulse__value">{{ $ratio($rate) ? number_format($ratio($rate), 1) : '—' }}</span>
                <span class="jb-pulse__note">grams of silver for 1 g of gold</span>
            </div>
            @foreach(['1 week', '1 month', '1 year'] as $period)
                <div class="jb-pulse__tile">
                    <span class="jb-pulse__label">{{ $period }} ago</span>
                    <span class="jb-pulse__value">{{ $ratio($past[$period] ?? null) ? number_format($ratio($past[$period]), 1) : '—' }}</span>
                    @if($past[$period] ?? null)<span class="jb-pulse__note">{{ $past[$period]->rate_date->format('j M Y') }}</span>@endif
                </div>
            @endforeach
        </div>

        <h2 class="jb-section__title jb-pulse__heading">Publishing sessions</h2>
        <div class="jb-pulse jb-pulse--3">
            @foreach(MetalRate::SESSION_TIMES as $session => $time)
                <div class="jb-pulse__tile">
                    <span class="jb-pulse__label">{{ ucfirst($session) }} rate</span>
                    <span class="jb-pulse__value">{{ \Carbon\Carbon::createFromFormat('H:i', $time)->format('g:i A') }}</span>
                    <span class="jb-pulse__note">usual publishing time, IST</span>
                </div>
            @endforeach
            <div class="jb-pulse__tile">
                <span class="jb-pulse__label">Latest on this board</span>
                <span class="jb-pulse__value">{{ $rate->published_at->format('j M, g:i A') }}</span>
                <span class="jb-pulse__note">{{ ucfirst($rate->session) }} rate</span>
            </div>
        </div>

        @if(collect($past)->filter()->contains(fn ($r) => $r->is_sample))
            <p class="jb-muted jb-note">Some comparison points are demo prices for preview only, not market rates.</p>
        @endif
    </div>
</section>
@else
<section class="jb-section"><div class="jb-wrap"><div class="jb-panel"><p class="jb-muted mb-0">The board will fill in as soon as rates are published.</p></div></div></section>
@endif

<section class="jb-section jb-section--white">
    <div class="jb-wrap">
        <div class="jb-receipt">
            <p class="jb-eyebrow">How to read this board</p>
            <h2 class="jb-section__title" style="margin-bottom: 10px;">What the numbers mean</h2>
            <p class="jb-muted mb-2">Each tile shows a price at our latest published rate. Underneath, the arrows compare it with our closing rate one day, one week, one month and one year earlier; green means the price is higher now, red means lower.</p>
            <p class="jb-muted mb-0">The gold–silver ratio is how many grams of silver one gram of 24K gold buys. A high ratio means silver is cheap compared with gold; a low ratio means it is dear.</p>
        </div>
    </div>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/rate-pages.css') }}?v={{ filemtime(public_path('css/rate-pages.css')) }}">
@endpush
