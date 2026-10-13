{{--
    Price history comparison: latest closing price vs 1 day / week / month / year ago.
    Expects: $comparison (LiveRatesController::comparison)
--}}
@if($comparison)
@once
@push('styles')
<style>
    .lr-cmp { min-width: 640px; }
    .lr-cmp th, .lr-cmp td { border-right: 1px solid var(--jb-line-soft); vertical-align: top; }
    .lr-cmp th:last-child, .lr-cmp td:last-child { border-right: 0; }
    .lr-cmp th { vertical-align: bottom; }
    .lr-cmp__date { display: block; margin-top: 3px; color: var(--jb-muted); font-size: 11px; font-weight: 500; letter-spacing: .02em; text-transform: none; }
    .lr-cmp td.lr-cmp__metal--gold { color: var(--jb-gold-strong); }
    .lr-cmp td.lr-cmp__metal--silver { color: #4f5965; }
    .lr-cmp__unit { display: block; margin-top: 2px; color: var(--jb-muted); font-size: 12px; font-weight: 500; }
    .lr-cmp__current { font-family: var(--jb-serif); font-weight: 600; }
    .lr-cmp__current.lr-cmp__metal--gold { color: var(--jb-gold-strong); }
    .lr-cmp__current.lr-cmp__metal--silver { color: #4f5965; }
    .lr-cmp__price { color: var(--jb-ink); font-weight: 700; }
    .lr-cmp__change { display: block; margin-top: 2px; font-size: 12.5px; font-weight: 600; }
    .lr-cmp__change--up { color: var(--jb-up); }
    .lr-cmp__change--down { color: var(--jb-down); }
    .lr-cmp__change--flat { color: var(--jb-muted); }
    .lr-cmp__none { color: #b9ae98; }
    .lr-cmp-foot { margin: 10px 0 0; color: var(--jb-muted); font-size: 12.5px; }
</style>
@endpush
@endonce

<div class="lr-head lr-head--next" data-aos="fade-up">
    <h2 class="jb-section__title">Price History Comparison</h2>
    <p class="jb-section__sub">
        Latest closing rate against earlier days. Based on rates published by Josha Gold and Diamond Jewellery.
    </p>
</div>

<div class="jb-table-wrap" data-aos="fade-up">
    <table class="jb-table lr-cmp">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Current</th>
                @foreach($comparison['periods'] as $period)
                <th scope="col">
                    {{ $period['label'] }}
                    <span class="lr-cmp__date">{{ $period['date'] ? $period['date']->format('d M Y') : 'no data' }}</span>
                </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($comparison['rows'] as $row)
            @php $tone = str_starts_with($row['label'], 'Gold') ? 'lr-cmp__metal--gold' : 'lr-cmp__metal--silver'; @endphp
            <tr>
                <td class="{{ $tone }}">
                    {{ $row['label'] }}
                    <span class="lr-cmp__unit">{{ $row['unit'] }}</span>
                </td>
                <td class="lr-cmp__current {{ $tone }}">
                    {{ number_format($row['current']) }}
                </td>
                @foreach($row['cells'] as $cell)
                <td>
                    @if($cell)
                        @php
                            $dir = $cell['change'] > 0 ? 'up' : ($cell['change'] < 0 ? 'down' : 'flat');
                        @endphp
                        <span class="lr-cmp__price">{{ number_format($cell['price']) }}</span>
                        <span class="lr-cmp__change lr-cmp__change--{{ $dir }}">
                            {{ $dir === 'up' ? '▲' : ($dir === 'down' ? '▼' : '') }}
                            {{ $dir === 'up' ? '+' : ($dir === 'down' ? '−' : '') }}{{ number_format(abs($cell['pct']), 2) }}%
                        </span>
                    @else
                        <span class="lr-cmp__none">&mdash;</span>
                    @endif
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<p class="lr-cmp-foot">
    Change % shows how much today's rate has moved compared with the rate on that earlier date (or the last published rate before it).
</p>
@endif
