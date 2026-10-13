{{--
    Daily closing prices, newest first, with the change from the day before.
    Expects: $closes (MetalRate, newest first), $metal, $purity, $grams, $unit (e.g. 'per 10 g')
--}}
@php
    use App\Support\RateBook;
    $prices = $closes->map(fn ($r) => $r->priceFor($metal, $purity, $grams))->values();
    $anySample = $closes->contains(fn ($r) => $r->is_sample);
@endphp
@if($closes->isNotEmpty())
<div class="jb-table-wrap">
    <table class="jb-table">
        <thead>
            <tr><th scope="col">Date</th><th scope="col">{{ $metal === 'gold' ? $purity . ' gold' : 'Silver ' . $purity }}, {{ $unit }}</th><th scope="col">Change</th></tr>
        </thead>
        <tbody>
            @foreach($closes as $i => $close)
                @php
                    $price  = $prices[$i];
                    $before = $prices[$i + 1] ?? null;
                    $diff   = $before !== null ? $price - $before : null;
                @endphp
                <tr>
                    <td>
                        {{ $close->rate_date->format('j M Y') }}
                        @if($close->is_sample)<span class="jb-chip jb-chip--warn" style="margin-left: 6px;">sample</span>@endif
                    </td>
                    <td class="jb-num-gold">{{ RateBook::inr($price) }}</td>
                    <td>
                        @if($diff === null || round($diff) == 0)
                            <span class="jb-muted">—</span>
                        @else
                            <span class="jb-change jb-change--{{ $diff > 0 ? 'up' : 'down' }}">{{ $diff > 0 ? '▲' : '▼' }} {{ RateBook::inr(abs($diff)) }} ({{ number_format(abs(RateBook::change($price, $before)), 2) }}%)</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@if($anySample)
    <p class="jb-muted jb-note">Rows marked “sample” are demo prices for preview only, not market rates.</p>
@endif
@endif
