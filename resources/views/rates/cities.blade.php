@extends('layouts.lawfirm')

@section('title', 'Gold & Silver Rates by City | ' . config('company.name'))
@section('meta_description', 'Gold and silver reference rates for Mumbai, Delhi, Chennai, Bengaluru, Hyderabad, Ahmedabad, Kolkata and Jaipur.')

@section('content')
@include('layouts.partials.page-head', [
    'eyebrow' => 'Rates by state & city',
    'title'   => 'Gold rate in',
    'accent'  => 'your city',
    'sub'     => 'Pick a city to see its 24K and 22K gold and 999 silver rates, the last 10 days and answers to common questions.',
    'crumbs'  => [['City rates']],
])

<section class="jb-section">
    <div class="jb-wrap">
        @if($rate)
            @include('rates._meta')
            <div style="margin-top: 18px;">
                @include('rates._cities', ['metal' => 'gold'])
            </div>
        @else
            <div class="jb-panel"><p class="jb-muted mb-0">City rates will appear here as soon as rates are published.</p></div>
        @endif

        <h2 class="jb-section__title" style="margin: 30px 0 14px;">Browse by state</h2>
        <div class="jb-statelist">
            @foreach(collect($cities)->groupBy(fn ($c) => $c[1], true)->sortKeys() as $state => $stateCities)
                <div>
                    <h3>{{ $state }}</h3>
                    @foreach($stateCities as $key => [$name])
                        @php [$stateSlug, $citySlug] = explode('/', $key); @endphp
                        <a class="jb-link" href="{{ route('rates.city', ['state' => $stateSlug, 'city' => $citySlug]) }}">Gold rate in {{ $name }} →</a>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/rate-pages.css') }}?v={{ filemtime(public_path('css/rate-pages.css')) }}">
@endpush
