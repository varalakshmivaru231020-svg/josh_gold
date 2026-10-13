@extends('layouts.lawfirm')
@section('title', 'Live Gold & Silver Rates Today — Josha Gold and Diamond Jewellery')
@section('meta_description', 'Today\'s gold and silver rates updated twice daily by Josha Gold and Diamond Jewellery. View 24K, 22K, 18K gold rates per gram, 10gm, 100gm, 1kg with trend chart.')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* ═══ Sections ═══════════════════════════════════════ */
    .lr-section { padding: 28px 0 0; }
    .lr-section--tables { padding-bottom: 8px; }
    @media (min-width: 768px) { .lr-section { padding-top: 40px; } }

    /* Section headings (#gold-rate / #silver-rate are linked from the header and footer) */
    .lr-head { scroll-margin-top: calc(var(--jb-header-h) + 16px); }
    .lr-head--next { margin-top: 48px; }
    @media (min-width: 768px) { .lr-head--next { margin-top: 64px; } }
    .lr-head .jb-section__title { margin: 0; }
    .lr-head .jb-section__sub { margin-bottom: 0; }

    /* ═══ Notices ════════════════════════════════════════ */
    .lr-note {
        display: flex; align-items: center; gap: 12px;
        margin-bottom: 16px; padding: 13px 16px;
        border: 1px solid var(--jb-line-strong); border-radius: 14px;
        background: #fffaf0;
        color: var(--jb-gold-deep); font-size: 14px; line-height: 1.5;
    }
    .lr-note i { flex: none; }
    .lr-note--sample { border-color: #f2c4bd; background: #fbe9e7; color: var(--jb-down); }
    .lr-note--sample strong { color: var(--jb-ink); }

    /* ═══ Today's rate: gold | silver ════════════════════ */
    .lr-now {
        display: grid; grid-template-columns: repeat(2, minmax(0, 1fr));
        position: relative; overflow: hidden;
        border: 1px solid var(--jb-line); border-radius: var(--jb-r-card);
        background: radial-gradient(520px 200px at 50% 0%, rgba(214, 178, 95, .16), transparent 70%), linear-gradient(180deg, #fffaf0, #fff 60%);
        box-shadow: var(--jb-shadow-lg);
        text-align: center;
    }
    .lr-now__cell { min-width: 0; padding: 24px 14px 22px; }
    .lr-now__cell + .lr-now__cell { border-left: 1px solid var(--jb-line); }
    .lr-now__metal { margin: 0 0 12px; color: var(--jb-gold-strong); font: 700 14px/1 var(--jb-sans); letter-spacing: .18em; text-transform: uppercase; }
    .lr-now__rate {
        display: flex; justify-content: center; align-items: center; gap: 10px;
        font: 600 clamp(22px, 4.4vw, 44px)/1.1 var(--jb-serif);
        font-variant-numeric: tabular-nums; letter-spacing: -.01em;
    }
    .lr-now__rate i { font-size: .6em; }
    .lr-now__change { margin-top: 10px; font-size: 14.5px; font-weight: 700; font-variant-numeric: tabular-nums; }
    .lr-now__unit { margin-top: 6px; color: var(--jb-muted); font-size: 13px; font-weight: 600; }
    .lr-up { color: var(--jb-up); }
    .lr-down { color: var(--jb-down); }
    .lr-flat { color: var(--jb-ink); }
    @media (max-width: 575px) {
        .lr-now__cell { padding: 18px 8px 16px; }
        .lr-now__metal { font-size: 12px; margin-bottom: 10px; }
        .lr-now__rate { gap: 6px; }
        .lr-now__change { font-size: 12px; }
        .lr-now__unit { font-size: 12px; }
    }

    /* ═══ Rate tables (home-page table style) ═════════════ */
    .lr-table th, .lr-table td { border-right: 1px solid var(--jb-line-soft); }
    .lr-table th:last-child, .lr-table td:last-child { border-right: 0; }
    .lr-table td.lr-num { color: var(--jb-gold-strong); font-family: var(--jb-serif); font-weight: 600; }
    .lr-table--silver td.lr-num { color: #4f5965; }  /* silver grey, readable on white */
    .lr-table .lr-cur { color: var(--jb-muted); font-size: .85em; font-weight: 500; }

    /* ═══ No data ════════════════════════════════════════ */
    .no-data-state {
        padding: 48px 20px; text-align: center; color: var(--jb-muted);
        border: 1px dashed var(--jb-line-strong); border-radius: var(--jb-r-card); background: #fff; box-shadow: var(--jb-shadow);
    }
    .no-data-state i { display: block; margin-bottom: 14px; font-size: 2.6rem; color: var(--jb-gold); }
    .no-data-state h4 { color: var(--jb-ink); font-size: 20px; }
    .no-data-state p { margin: 8px 0 0; }
</style>
@endpush

@section('content')

{{-- ─── PAGE HEADING ─────────────────────────────────────── --}}
@include('layouts.partials.page-head', [
    'eyebrow' => 'Updated twice daily',
    'title'   => "Today's Gold & Silver",
    'accent'  => 'Rates in India',
    'sub'     => $current ? null : 'Rates will appear once published by Josha Gold and Diamond Jewellery.',
    'crumbs'  => [['Live rates']],
])

{{-- ─── MAIN RATE CARDS ──────────────────────────────────── --}}
<section class="lr-section">
    <div class="jb-wrap">

        @if($current)

        {{-- Demo-data notice: shown while the latest rate is a sample row --}}
        @if($current->is_sample)
        <div class="lr-note lr-note--sample">
            <i class="fas fa-flask fa-lg"></i>
            <span><strong>Sample data.</strong> These are demo prices for preview only, not live market rates.</span>
        </div>
        @endif

        {{-- Remarks strip --}}
        @if($current->remarks)
        <div class="lr-note">
            <i class="fas fa-info-circle fa-lg"></i>
            <span>{{ $current->remarks }}</span>
        </div>
        @endif

        <div class="lr-now">
            {{-- GOLD COLUMN --}}
            @php
                $goldRate   = $current->gold_999_rate * 10; // stored per 1 gm, shown per 10 gm
                $goldPrev   = $previous ? ($previous->gold_999_rate * 10) : null;
                $goldChange = $previous ? ($goldRate - $goldPrev) : null;
                $goldPct    = ($goldPrev && $goldPrev > 0)
                    ? (($goldChange / $goldPrev) * 100)
                    : null;
                $goldDir  = $goldChange > 0 ? 'up' : ($goldChange < 0 ? 'down' : 'flat');
                $goldIcon = $goldChange > 0 ? 'fa-arrow-up' : ($goldChange < 0 ? 'fa-arrow-down' : 'fa-minus');
            @endphp
            <div class="lr-now__cell">
                <h4 class="lr-now__metal">GOLD</h4>
                <div class="lr-now__rate lr-{{ $goldDir }}">
                    @if($goldChange !== null && $goldChange != 0)
                    <i class="fas {{ $goldIcon }}"></i>
                    @endif
                    <span>{{ number_format($goldRate, 2) }}</span>
                </div>
                @if($goldChange !== null)
                <div class="lr-now__change lr-{{ $goldDir }}">
                    {{ $goldChange > 0 ? '+' : '' }}{{ number_format($goldChange, 2) }} ({{ $goldChange > 0 ? '+' : '' }}{{ number_format($goldPct, 3) }}%)
                </div>
                @endif
                <div class="lr-now__unit">Rs ₹ / 10gm</div>
            </div>

            {{-- SILVER COLUMN --}}
            @php
                $silverRate = $current->silver_999_rate * 1000;
                $silverPrev = $previous ? ($previous->silver_999_rate * 1000) : null;
                $silverChange = $previous ? ($silverRate - $silverPrev) : null;
                $silverPct    = ($silverPrev && $silverPrev > 0)
                    ? (($silverChange / $silverPrev) * 100)
                    : null;
                $silverDir  = $silverChange > 0 ? 'up' : ($silverChange < 0 ? 'down' : 'flat');
                $silverIcon = $silverChange > 0 ? 'fa-arrow-up' : ($silverChange < 0 ? 'fa-arrow-down' : 'fa-minus');
            @endphp
            <div class="lr-now__cell">
                <h4 class="lr-now__metal">SILVER</h4>
                <div class="lr-now__rate lr-{{ $silverDir }}">
                    @if($silverChange !== null && $silverChange != 0)
                    <i class="fas {{ $silverIcon }}"></i>
                    @endif
                    <span>{{ number_format($silverRate, 2) }}</span>
                </div>
                @if($silverChange !== null)
                <div class="lr-now__change lr-{{ $silverDir }}">
                    {{ $silverChange > 0 ? '+' : '' }}{{ number_format($silverChange, 2) }} ({{ $silverChange > 0 ? '+' : '' }}{{ number_format($silverPct, 3) }}%)
                </div>
                @endif
                <div class="lr-now__unit">Rs ₹ / 1kg</div>
            </div>
        </div>

        @else
        <div class="no-data-state">
            <i class="fas fa-coins"></i>
            <h4>Today's rates have not been published yet.</h4>
            <p>Check back shortly. Rates are updated every morning and evening by Josha Gold and Diamond Jewellery.</p>
        </div>
        @endif
    </div>
</section>

{{-- ─── GOLD PURITY TABLE + CHART ────────────────────────── --}}
<section class="lr-section lr-section--tables">
    <div class="jb-wrap">
        <div class="lr-head" id="gold-rate" data-aos="fade-up">
            <h2 class="jb-section__title">Gold Rate Today in India</h2>
        </div>

        @if($current && count($goldTable))
        <div class="jb-table-wrap" data-aos="fade-up" data-aos-delay="80">
            <table class="jb-table lr-table">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">1 Gram</th>
                        <th scope="col">10 Gram</th>
                        <th scope="col">100 Gram</th>
                        <th scope="col">1 Kilogram</th>
                        <th scope="col">1 Ounce</th>
                        <th scope="col">1 Tola</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($goldTable as $row)
                    @php
                        $karat = $row['purity'];
                    @endphp
                    <tr>
                        <td>Gold {{ str_replace('K', ' Karat', $karat) }} <span class="lr-cur">(Rs ₹)</span></td>
                        <td class="lr-num">{{ number_format($row['weights'][1]) }}</td>
                        <td class="lr-num">{{ number_format($row['weights'][10]) }}</td>
                        <td class="lr-num">{{ number_format($row['weights'][100]) }}</td>
                        <td class="lr-num">{{ number_format($row['weights'][1000]) }}</td>
                        <td class="lr-num">{{ number_format($row['weights']['ounce'] ?? 0) }}</td>
                        <td class="lr-num">{{ number_format($row['weights']['tola'] ?? 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ─── GOLD CHART ─────────────────────────────── --}}
        @include('live-rates._chart', [
            'key' => 'gold', 'title' => 'Gold Rates in India (INR/10gm)', 'purity' => '24K', 'unit' => '10gm',
            'line' => '#B7932F', 'strip' => '#DDC37C', 'accent' => '#DDA20D',
        ])

        {{-- Silver Table --}}
        <div class="lr-head lr-head--next" id="silver-rate" data-aos="fade-up">
            <h2 class="jb-section__title">Silver Rate Today in India</h2>
        </div>

        @if($current && count($silverTable))
        <div class="jb-table-wrap" data-aos="fade-up">
            <table class="jb-table lr-table lr-table--silver">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">1 Gram</th>
                        <th scope="col">10 Gram</th>
                        <th scope="col">100 Gram</th>
                        <th scope="col">1 Kilogram</th>
                        <th scope="col">1 Ounce</th>
                        <th scope="col">1 Tola</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($silverTable as $row)
                    <tr>
                        <td>{{ $row['name'] }} <span class="lr-cur">(Rs ₹)</span></td>
                        <td class="lr-num">{{ number_format($row['weights'][1]) }}</td>
                        <td class="lr-num">{{ number_format($row['weights'][10]) }}</td>
                        <td class="lr-num">{{ number_format($row['weights'][100]) }}</td>
                        <td class="lr-num">{{ number_format($row['weights'][1000]) }}</td>
                        <td class="lr-num">{{ number_format($row['weights']['ounce'] ?? 0) }}</td>
                        <td class="lr-num">{{ number_format($row['weights']['tola'] ?? 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- ─── SILVER CHART ───────────────────────────── --}}
        @include('live-rates._chart', [
            'key' => 'silver', 'title' => 'Silver Rates in India (INR/1kg)', 'purity' => '999', 'unit' => '1kg',
            'line' => '#7F7F7F', 'strip' => '#CECECE', 'accent' => '#7D7D7D',
        ])

        {{-- ─── PRICE HISTORY COMPARISON ─────────────── --}}
        @include('live-rates._comparison')

        @else
        <div class="no-data-state mt-3">
            <i class="fas fa-table"></i>
            <p>Purity table will appear once rates are published.</p>
        </div>
        @endif
    </div>
</section>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/amcharts3@3.21.15/amcharts/amcharts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/amcharts3@3.21.15/amcharts/serial.js"></script>
<script src="https://cdn.jsdelivr.net/npm/amstock3@3.21.15/amcharts/amstock.js"></script>
<script src="{{ asset('js/rate-chart.js') }}?v={{ filemtime(public_path('js/rate-chart.js')) }}"></script>
@endpush
