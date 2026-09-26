{{--
    Price history comparison: latest closing price vs 1 day / week / month / year ago.
    Expects: $comparison (LiveRatesController::comparison)
--}}
@if($comparison)
<div class="row align-items-end mt-5 mb-4" data-aos="fade-up">
    <div class="col">
        <h2 class="section-title" style="color: var(--navy); font-size: 1.8rem;">Price History Comparison</h2>
        <p class="section-sub" style="font-style: italic; color: #64748b; font-size: 0.85rem;">
            Latest closing rate against earlier days. Based on rates published by Josha Infinity.
        </p>
    </div>
</div>

<div class="table-responsive" style="border: 1px solid #e2e8f0; background: #fff;" data-aos="fade-up">
    <table class="table mb-0" style="width: 100%; font-size: 0.95rem; min-width: 640px;">
        <thead>
            <tr>
                <th style="color: #94a3b8; font-weight: 500; text-align: left; border-right: 1px solid #e2e8f0; border-bottom: 2px solid #e2e8f0; padding: 10px 15px;">Name</th>
                <th style="color: #94a3b8; font-weight: 500; text-align: center; border-right: 1px solid #e2e8f0; border-bottom: 2px solid #e2e8f0; padding: 10px;">Current</th>
                @foreach($comparison['periods'] as $period)
                <th style="color: #94a3b8; font-weight: 500; text-align: center; border-bottom: 2px solid #e2e8f0; padding: 10px; {{ $loop->last ? '' : 'border-right: 1px solid #e2e8f0;' }}">
                    {{ $period['label'] }}
                    <span style="display:block; font-size:.72rem; color:#cbd5e1;">{{ $period['date'] ? $period['date']->format('d M Y') : 'no data' }}</span>
                </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($comparison['rows'] as $row)
            @php $isGold = str_starts_with($row['label'], 'Gold'); $tone = $isGold ? '#b45309' : '#475569'; @endphp
            <tr>
                <td style="color: {{ $tone }}; font-weight: 700; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; padding: 12px 15px;">
                    {{ $row['label'] }}
                    <span style="display:block; font-size:.75rem; font-weight:500; color:#94a3b8;">{{ $row['unit'] }}</span>
                </td>
                <td style="color: {{ $tone }}; font-weight: 700; text-align: right; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; padding: 12px 15px; font-variant-numeric: tabular-nums;">
                    {{ number_format($row['current']) }}
                </td>
                @foreach($row['cells'] as $cell)
                <td style="text-align: right; border-bottom: 1px solid #e2e8f0; padding: 12px 15px; font-variant-numeric: tabular-nums; {{ $loop->last ? '' : 'border-right: 1px solid #e2e8f0;' }}">
                    @if($cell)
                        @php
                            $dir = $cell['change'] > 0 ? 'up' : ($cell['change'] < 0 ? 'down' : 'flat');
                            $color = $dir === 'up' ? '#16a34a' : ($dir === 'down' ? '#e60000' : '#64748b');
                        @endphp
                        <span style="color: #1e293b; font-weight: 700;">{{ number_format($cell['price']) }}</span>
                        <span style="display:block; font-size:.78rem; font-weight:600; color: {{ $color }};">
                            {{ $dir === 'up' ? '▲' : ($dir === 'down' ? '▼' : '') }}
                            {{ $dir === 'up' ? '+' : ($dir === 'down' ? '−' : '') }}{{ number_format(abs($cell['pct']), 2) }}%
                        </span>
                    @else
                        <span style="color: #cbd5e1;">&mdash;</span>
                    @endif
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<p class="text-muted mt-2 mb-0" style="font-size:.78rem;">
    Change % shows how much today's rate has moved compared with the rate on that earlier date (or the last published rate before it).
</p>
@endif
