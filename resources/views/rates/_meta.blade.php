{{-- When the shown rate was published, with a sample-data warning. Expects: $rate --}}
@php
    $published = $rate->published_at;
    $isStale   = $published->lt(now(\App\Models\MetalRate::TIMEZONE)->subHours(36));
@endphp
<div class="jb-ratemeta">
    <span class="jb-live" data-state="{{ $isStale ? 'stale' : 'live' }}">
        <span class="jb-live__dot"></span>
        {{ $isStale ? 'Last published' : 'Live' }} · {{ ucfirst($rate->session) }} rate, {{ $published->format('j M Y, g:i A') }} IST
    </span>
    @if($rate->is_sample)<span class="jb-chip jb-chip--warn">Sample data, not a live market rate</span>@endif
    <a class="jb-link" href="{{ route('live-rates') }}">Full rate table →</a>
</div>
