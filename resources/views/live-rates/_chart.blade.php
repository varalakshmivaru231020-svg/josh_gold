{{--
    Gold / Silver rate chart in the style of bullions.co.in (amCharts stock chart).
    Data: GET route('live-rates.history'); behaviour: public/js/rate-chart.js
    Expects: $key (gold|silver), $title, $purity, $unit, $line, $strip, $accent
--}}
@once
@push('styles')
<style>
    /* Box around the chart (same as the reference) */
    .rc { position: relative; border: 1px solid #e5e5e5; background: #fff; padding: 0 0 10px; margin-top: 1.25rem; }
    .rc-chart { width: 100%; height: 420px; font-size: 10px; }
    .rc .amChartsLegend { display: none; }

    /* Zoom buttons + From / To boxes */
    .rc .amcharts-period-selector-div { margin-left: 10px; margin-right: 10px; }
    .rc .amChartsPeriodSelector, .rc .amChartsDataSetSelector { font-size: 13px; }
    /* amCharts sets these as inline styles, hence !important */
    .rc input.amChartsButtonSelected, .rc input[type="button"].amChartsButtonSelected, .rc .amChartsButtonSelected,
    .rc input[type="button"].amChartsButton, .rc .amChartsButton,
    .rc input.amChartsInputField, .rc .amChartsInputField {
        font-size: 12px !important; background: #fff !important; color: #333 !important; opacity: 1 !important;
        border: 1px solid #ccc !important; border-radius: 5px !important;
        margin: 1px !important; outline: none !important; box-sizing: border-box !important; padding: 1px 4px !important;
    }
    .rc input.amChartsButtonSelected, .rc input[type="button"].amChartsButtonSelected, .rc .amChartsButtonSelected {
        background-color: var(--rc-accent) !important; color: #fff !important;
    }
    .rc .amChartsButton, .rc input[type="button"].amChartsButton, .rc .amChartsInputField,
    .rc .amcharts-end-date-input, .rc .amcharts-start-date-input { color: var(--rc-accent) !important; }
    .rc .amChartsInputField, .rc .amcharts-end-date-input, .rc .amcharts-start-date-input { width: 80px !important; max-width: 80px !important; }

    /* Loading / empty / error */
    .rc-view { display: none; }
    .rc[data-state="ready"] .rc-chart { display: block; }
    .rc:not([data-state="ready"]) .rc-chart { display: none; }
    .rc[data-state="loading"] .rc-skeleton,
    .rc[data-state="empty"] .rc-message--empty,
    .rc[data-state="error"] .rc-message--error { display: flex; }
    .rc-skeleton, .rc-message { height: 420px; align-items: center; justify-content: center; }
    .rc-skeleton { flex-direction: column; gap: 1.5rem; padding: 2rem; }
    .rc-skeleton span { width: 100%; height: 2px; background: #f1f1f1; }
    .rc-skeleton::after { content: ''; position: absolute; inset: 1rem; background: linear-gradient(100deg, transparent 20%, rgba(226,232,240,.6) 45%, transparent 70%); background-size: 200% 100%; animation: rc-shimmer 1.4s ease-in-out infinite; }
    @keyframes rc-shimmer { from { background-position: 150% 0; } to { background-position: -50% 0; } }
    .rc-message { flex-direction: column; gap: .6rem; color: #64748b; text-align: center; padding: 0 1rem; }
    .rc-message p { margin: 0; font-size: .95rem; }
    .rc-retry { border: 1px solid #ccc; background: #fff; color: #333; font-size: .85rem; padding: .35rem 1rem; border-radius: 5px; cursor: pointer; }
    .rc-retry:hover { border-color: var(--rc-accent); color: var(--rc-accent); }

    @media (max-width: 575px) {
        .rc { padding-bottom: 32px; } /* the Zoom row wraps onto a second line on phones */
        .rc-chart, .rc-skeleton, .rc-message { height: 380px; }
    }
    @media (prefers-reduced-motion: reduce) { .rc-skeleton::after { animation: none; } }
</style>
@endpush
@endonce

<div class="rc" data-rate-chart data-state="loading"
     id="chart_{{ $key }}_box"
     style="--rc-accent: {{ $accent }};"
     data-endpoint="{{ route('live-rates.history', ['metal' => $key, 'purity' => $purity, 'unit' => $unit, 'range' => 'MAX']) }}"
     data-title="{{ $title }}"
     data-line="{{ $line }}"
     data-strip="{{ $strip }}"
     data-images="https://cdn.jsdelivr.net/npm/amcharts3@3.21.15/amcharts/images/">

    <div class="rc-chart" id="chart_{{ $key }}" title="India {{ ucfirst($key) }} Rate Chart"></div>

    <div class="rc-view rc-skeleton" aria-hidden="true"><span></span><span></span><span></span><span></span></div>

    <div class="rc-view rc-message rc-message--empty" role="status">
        <p>No historical price data available for this period.</p>
    </div>

    <div class="rc-view rc-message rc-message--error" role="alert">
        <p>Unable to load historical rates. Please try again.</p>
        <button type="button" class="rc-retry">Try again</button>
    </div>
</div>
