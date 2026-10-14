{{--
    Collection tiles linking to /collections/{slug} (Admin → Collections).

    Home page "Shop by category":   @include('partials.collection-cards')
        → the active collections marked "Show on home page", in admin order (cached; cleared when a collection is saved or deleted).
        Desktop lays out rows of four and stretches the last row to the full width (e.g. 7 tiles = 4 + 3).
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
        <a class="jb-cat" href="{{ route('collections.show', $item->slug) }}">
            <img src="{{ $item->image_url }}" alt="" width="500" height="375" loading="lazy">
            <span class="jb-cat__label">
                <strong>{{ $item->name }}</strong>
                <span>{{ $item->tagline ?: 'View collection' }}&nbsp;→</span>
            </span>
        </a>
    @endforeach
</div>
@endif

@pushOnce('styles', 'collections-css')
<link rel="stylesheet" href="{{ asset('css/collections.css') }}?v={{ filemtime(public_path('css/collections.css')) }}">
@endPushOnce
