{{--
    Collection tiles linking to /collections/{slug} (Admin → Collections).

    Home page "Shop by category":   @include('partials.collection-cards')
        → the active collections marked "Show on home page", in admin order (cached; cleared when a collection is saved or deleted).
        Tall editorial cards, four to a row on desktop (two on phones); each fades in as it scrolls into view.
    Any list, as a one-row strip:   @include('partials.collection-cards', ['items' => $others, 'variant' => 'strip'])

    Loads public/css/collections.css itself (once per page) through @stack('styles').
--}}
@php
    $items   = $items ?? \App\Models\Collection::forHome();
    $variant = $variant ?? 'grid';
@endphp

@if($items->isNotEmpty())
<div class="jb-cats {{ $variant === 'strip' ? 'jb-cats--strip' : 'jb-cats--collections' }}">
    @foreach($items as $item)
        <a class="jb-cat" href="{{ route('collections.show', $item->slug) }}" @if($variant !== 'strip') data-reveal style="--jb-delay: {{ $loop->index * 0.08 }}s" @endif>
            <img src="{{ $item->image_url }}" alt="" width="600" height="800" loading="lazy" decoding="async">
            <span class="jb-cat__label">
                <strong>{{ $item->name }}</strong>
                <span>{{ $item->tagline ?: 'View collection' }}</span>
            </span>
        </a>
    @endforeach
</div>
@endif

@pushOnce('styles', 'collections-css')
<link rel="stylesheet" href="{{ asset('css/collections.css') }}?v={{ filemtime(public_path('css/collections.css')) }}">
@endPushOnce
