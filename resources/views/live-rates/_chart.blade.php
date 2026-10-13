{{--
    Gold / Silver rate chart in the style of bullions.co.in (amCharts stock chart), on a white panel.
    Data: GET route('live-rates.history'); behaviour: public/js/rate-chart.js
    Expects: $key (gold|silver), $title, $purity, $unit, $line, $strip, $accent
--}}
@once
@push('styles')
<style>
    /* Box around the chart (same layout as the reference, on a white panel) */
    .rc { position: relative; border: 1px solid var(--jb-line); border-radius: 14px; background: #fff; box-shadow: var(--jb-shadow); padding: 0 0 10px; margin-top: 1.25rem; scroll-margin-top: calc(var(--jb-header-h) + 16px); }
    .rc-chart { width: 100%; height: 420px; font-size: 10px; }
    .rc .amChartsLegend { display: none; }

    /* Zoom buttons + From / To boxes */
    .rc .amcharts-period-selector-div { margin-left: 10px; margin-right: 10px; }
    .rc .amChartsPeriodSelector, .rc .amChartsDataSetSelector { font-size: 13px; color: var(--jb-muted); }
    /* amCharts sets these as inline styles, hence !important */
    .rc input.amChartsButtonSelected, .rc input[type="button"].amChartsButtonSelected, .rc .amChartsButtonSelected,
    .rc input[type="button"].amChartsButton, .rc .amChartsButton,
    .rc input.amChartsInputField, .rc .amChartsInputField {
        font-size: 12px !important; background: #fff !important; color: var(--jb-text) !important; opacity: 1 !important;
        border: 1px solid var(--jb-line) !important; border-radius: 8px !important;
        margin: 1px !important; outline: none !important; box-sizing: border-box !important; padding: 2px 6px !important;
        font-family: var(--jb-sans) !important; cursor: pointer;
    }
    .rc input[type="button"].amChartsButton:hover, .rc .amChartsButton:hover { border-color: var(--jb-line-strong) !important; background: #fffaf0 !important; color: var(--jb-ink) !important; }
    .rc input.amChartsButtonSelected, .rc input[type="button"].amChartsButtonSelected, .rc .amChartsButtonSelected {
        background: var(--jb-gold-band) !important; border-color: #b48b3c !important; color: #2a1d06 !important; font-weight: 700 !important;
    }
    .rc input.amChartsInputField { color: var(--jb-ink) !important; cursor: text; }
    .rc input.amChartsInputField:focus { border-color: var(--jb-gold) !important; box-shadow: 0 0 0 3px rgba(201, 163, 92, .18) !important; }
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
    .rc-skeleton span { width: 100%; height: 1px; background: rgba(0, 0, 0, .06); }
    .rc-skeleton::after { content: ''; position: absolute; inset: 1rem; background: linear-gradient(100deg, transparent 20%, rgba(214, 178, 95, .12) 45%, transparent 70%); background-size: 200% 100%; animation: rc-shimmer 1.4s ease-in-out infinite; }
    @keyframes rc-shimmer { from { background-position: 150% 0; } to { background-position: -50% 0; } }
    .rc-message { flex-direction: column; gap: .6rem; color: var(--jb-muted); text-align: center; padding: 0 1rem; }
    .rc-message p { margin: 0; font-size: .95rem; }
    .rc-retry { border: 1px solid var(--jb-line-strong); background: #fff; color: var(--jb-ink); font-size: .85rem; font-weight: 700; padding: .4rem 1rem; border-radius: 10px; cursor: pointer; }
    .rc-retry:hover { border-color: var(--jb-gold); background: #fffaf0; }

    @media (max-width: 575px) {
        .rc { padding-bottom: 32px; } /* the Zoom row wraps onto a second line on phones */
        .rc .amcharts-period-selector-div input[type="button"] { padding: 1px 4px !important; }
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
