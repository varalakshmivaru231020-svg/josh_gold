{{--
    One live candle chart card (drawn by public/js/live-charts.js from /live-rates/candles).
    Expects: $key (LiveCharts::CHARTS key), $c (its definition); optional $heading ('h1'|'h2'), $dashboard (Focus / Open full buttons).
--}}
@php
    $heading   = $heading ?? 'h2';
    $dashboard = $dashboard ?? false;
@endphp
<article class="lc-chart" id="chart-{{ $key }}">
    <div class="lc-chart__head">
        <span class="lc-dot lc-dot--live" aria-hidden="true"></span>
        <{{ $heading }} class="lc-chart__name">{{ $c['name'] }}<span class="lc-chart__sub">{{ $c['sub'] }} · live <span class="lc-sample lc-sample--chart" data-lc-sample hidden title="The earlier candles are sample data while real price history builds up; they are removed automatically after 24 hours of real prices.">Sample data</span></span></{{ $heading }}>
        <span class="lc-spacer"></span>
        <div class="lc-tfs" role="group" aria-label="{{ $c['name'] }} timeframe">
            @foreach([1 => '1m', 5 => '5m', 15 => '15m', 60 => '1h'] as $tf => $label)
                <button type="button" class="lc-tf" data-tf-btn="{{ $tf }}" aria-pressed="{{ $tf === 5 ? 'true' : 'false' }}">{{ $label }}</button>
            @endforeach
        </div>
        @if($dashboard)
            <span class="lc-chart__actions">
                <button type="button" class="lc-btn lc-hide-sm" data-focus="chart-{{ $key }}" aria-pressed="false">Focus</button>
                <a class="lc-btn lc-btn--light" href="{{ route('rates.chart', $key) }}">Open full</a>
            </span>
        @endif
    </div>
    <p class="lc-ohlc" data-lc-ohlc aria-live="off"></p>
    <div class="lc-chart__frame">
        <div class="lc-canvas" data-lc-chart="{{ $key }}" data-dp="{{ $c['dp'] }}" data-tf="5" role="img" aria-label="{{ $c['name'] }} live candle chart ({{ $c['sub'] }})"></div>
        <p class="lc-empty" data-lc-empty hidden>Building this chart from live prices: a new point is saved every minute, so candles fill in over the next hours.</p>
    </div>
</article>
