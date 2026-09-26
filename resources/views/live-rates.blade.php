@extends('layouts.lawfirm')
@section('title', 'Live Gold & Silver Rates Today — Josha Infinity')
@section('meta_description', 'Today\'s gold and silver rates updated twice daily by Josha Infinity. View 24K, 22K, 18K gold rates per gram, 10gm, 100gm, 1kg with trend chart.')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* ═══ Page Hero ═══════════════════════════════════════ */
    .rates-hero {
        background: linear-gradient(135deg, var(--navy) 0%, #0d1f3c 50%, var(--navy-mid) 100%);
        padding: 4rem 0 3rem;
        position: relative;
        overflow: hidden;
    }
    .rates-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23c9a84c' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        pointer-events: none;
    }
    .rates-hero::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0; right: 0;
        height: 60px;
        background: var(--cream);
        clip-path: ellipse(55% 100% at 50% 100%);
    }
    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        background: rgba(201,168,76,0.15);
        border: 1px solid rgba(201,168,76,0.4);
        color: var(--gold-light);
        padding: .5rem 1.25rem;
        border-radius: 30px;
        font-size: .8rem;
        font-weight: 600;
        letter-spacing: .05em;
        text-transform: uppercase;
        margin-bottom: 1rem;
    }
    .hero-badge .live-dot {
        width: 8px; height: 8px;
        background: #22c55e;
        border-radius: 50%;
        animation: pulse-dot 2s infinite;
    }
    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: .4; transform: scale(1.4); }
    }
    .hero-title {
        font-family: var(--font-display);
        font-size: clamp(2rem, 5vw, 3.5rem);
        font-weight: 700;
        color: #fff;
        line-height: 1.15;
        margin-bottom: .75rem;
    }
    .hero-title span { color: var(--gold); }
    .hero-meta {
        color: rgba(255,255,255,.55);
        font-size: .9rem;
    }
    .hero-meta strong { color: var(--gold-light); }

    /* ═══ RATE CARDS ════════════════════════════════════════ */
    .rates-section { background: #fff; }
    .rate-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 4px 30px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: transform .3s, box-shadow .3s;
    }
    .rate-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(0,0,0,0.12); }
    .rate-card-header {
        padding: 1.5rem 2rem 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .rate-card.gold .rate-card-header { border-bottom: 3px solid var(--gold); }
    .rate-card.silver .rate-card-header { border-bottom: 3px solid #94a3b8; }
    .metal-name {
        font-family: var(--font-display);
        font-size: 1.1rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .12em;
    }
    .rate-card.gold .metal-name { color: var(--gold-dark); }
    .rate-card.silver .metal-name { color: #475569; }
    .purity-badge {
        font-size: .72rem; font-weight: 700;
        padding: 3px 10px; border-radius: 20px;
        text-transform: uppercase; letter-spacing: .06em;
    }
    .rate-card.gold .purity-badge { background: #fef3c7; color: #92400e; }
    .rate-card.silver .purity-badge { background: #f1f5f9; color: #475569; }
    .rate-main {
        padding: 1rem 2rem 1.5rem;
    }
    .rate-big {
        font-family: var(--font-display);
        font-size: clamp(2.4rem, 5vw, 3.2rem);
        font-weight: 700;
        line-height: 1;
        margin-bottom: .4rem;
    }
    .rate-card.gold .rate-big { color: #92400e; }
    .rate-card.silver .rate-big { color: #334155; }
    .rate-change {
        display: inline-flex; align-items: center; gap: .35rem;
        padding: .3rem .8rem; border-radius: 30px;
        font-size: .82rem; font-weight: 700;
        margin-bottom: .5rem;
    }
    .rate-change.up   { background: #dcfce7; color: #166534; }
    .rate-change.down { background: #fee2e2; color: #991b1b; }
    .rate-change.flat { background: #f1f5f9; color: #64748b; }
    .rate-unit { color: var(--text-muted); font-size: .82rem; font-weight: 500; }
    .session-badge-pub {
        display: inline-flex; align-items: center; gap: .35rem;
        padding: .25rem .7rem; border-radius: 20px;
        font-size: .72rem; font-weight: 600;
    }
    .session-badge-pub.morning { background: #fef9eb; color: #d97706; }
    .session-badge-pub.evening { background: #ede9fe; color: #6d28d9; }

    /* ═══ REMARKS STRIP ══════════════════════════════════════ */
    .remarks-strip {
        background: linear-gradient(90deg, rgba(201,168,76,0.12), rgba(201,168,76,0.04), rgba(201,168,76,0.12));
        border: 1px solid rgba(201,168,76,0.25);
        border-radius: 12px;
        padding: .875rem 1.5rem;
        display: flex;
        align-items: center;
        gap: .75rem;
        color: var(--gold-dark);
        font-size: .9rem;
    }

    .remarks-strip--sample {
        background: #fef2f2;
        border-color: #fecaca;
        color: #991b1b;
    }

    /* ═══ PURITY TABLE ═══════════════════════════════════════ */
    .purity-section { background: #fff; padding: 3.5rem 0; }
    .section-eyebrow {
        font-size: .75rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .12em;
        color: var(--gold);
        margin-bottom: .5rem;
    }
    .section-title {
        font-family: var(--font-display);
        font-size: clamp(1.6rem, 3vw, 2.2rem);
        font-weight: 700;
        color: var(--navy);
        margin-bottom: 0;
    }
    .section-sub { color: var(--text-muted); font-size: .9rem; margin-top: .5rem; }
    .purity-table { border-collapse: separate; border-spacing: 0; width: 100%; }
    .purity-table thead tr { background: var(--navy); }
    .purity-table thead th {
        color: var(--gold-light);
        font-size: .78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        padding: .9rem 1.25rem;
        white-space: nowrap;
    }
    .purity-table thead th:first-child { border-radius: 12px 0 0 0; }
    .purity-table thead th:last-child  { border-radius: 0 12px 0 0; }
    .purity-table tbody tr:nth-child(even) { background: var(--cream); }
    .purity-table tbody td {
        padding: .85rem 1.25rem;
        font-size: .9rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .purity-table tbody tr:last-child td { border-bottom: none; }
    .purity-name { font-weight: 700; color: var(--navy); font-size: .92rem; }
    .purity-name small { display:block; font-size:.7rem; color:var(--text-muted); font-weight:500; }
    .rate-cell { font-weight: 600; color: #92400e; text-align: right; }
    .purity-table .highlighted-row { background: linear-gradient(90deg, #fffbeb, #fff9f0) !important; }
    .purity-table .highlighted-row td { border-top: 2px solid var(--gold-light); border-bottom: 2px solid var(--gold-light); }

    /* Silver table */
    .silver-table tbody .rate-cell { color: #475569; }

    /* ═══ CHART SECTION ══════════════════════════════════════ */
    .chart-section { background: var(--cream); padding: 3.5rem 0; }
    .chart-wrapper {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.07);
        padding: 2rem;
    }
    .chart-tabs { display: flex; gap: .5rem; }
    .chart-tab {
        padding: .4rem 1.25rem; border-radius: 30px;
        border: 2px solid #e2e8f0;
        background: transparent;
        font-size: .82rem; font-weight: 600; color: #64748b;
        cursor: pointer; transition: all .2s;
    }
    .chart-tab.active { background: var(--navy); border-color: var(--navy); color: #fff; }
    .chart-tab:hover:not(.active) { border-color: var(--gold); color: var(--gold-dark); }
    .chart-legend { display: flex; gap: 1.5rem; align-items: center; font-size: .82rem; color: var(--text-muted); }
    .legend-dot { width: 12px; height: 4px; border-radius: 2px; }
    .legend-dot.gold-line   { background: #c9a84c; }
    .legend-dot.silver-line { background: #94a3b8; }

    /* ═══ DISCLAIMER ══════════════════════════════════════════ */
    .disclaimer-box {
        background: #fffbeb;
        border: 1px solid rgba(201,168,76,0.3);
        border-left: 4px solid var(--gold);
        border-radius: 10px;
        padding: 1.25rem 1.5rem;
        font-size: .82rem;
        color: #78350f;
        margin-top: 2.5rem;
    }
    .disclaimer-box strong { display: block; margin-bottom: .3rem; }

    /* ═══ NO DATA STATE ══════════════════════════════════════ */
    .no-data-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-muted);
    }
    .no-data-state i { font-size: 3rem; color: var(--gold-light); margin-bottom: 1rem; }
</style>
@endpush

@section('content')

{{-- ─── HERO ─────────────────────────────────────────────── --}}
<section class="rates-hero">
    <div class="container" style="position:relative;z-index:1;">
        <div class="text-center text-white">
            <div class="hero-badge">
                <span class="live-dot"></span>
                Updated Twice Daily
            </div>
            <h1 class="hero-title">
                Today's <span>Gold & Silver</span><br>Rates in India
            </h1>
            @unless($current)
            <p class="hero-meta">Rates will appear once published by Josha Infinity.</p>
            @endunless
        </div>
    </div>
</section>

{{-- ─── MAIN RATE CARDS ──────────────────────────────────── --}}
<section class="rates-section">
    <div class="container">

        @if($current)

        {{-- Demo-data notice: shown while the latest rate is a sample row --}}
        @if($current->is_sample)
        <div class="remarks-strip remarks-strip--sample mb-4">
            <i class="fas fa-flask fa-lg"></i>
            <span><strong>Sample data.</strong> These are demo prices for preview only, not live market rates.</span>
        </div>
        @endif

        {{-- Remarks strip --}}
        @if($current->remarks)
        <div class="remarks-strip mb-4">
            <i class="fas fa-info-circle fa-lg"></i>
            <span>{{ $current->remarks }}</span>
        </div>
        @endif

        <div class="row text-center mb-4 border-bottom pb-4 border-2">
            {{-- GOLD COLUMN --}}
            @php
                $goldRate   = $current->gold_999_rate * 10; // stored per 1 gm, shown per 10 gm
                $goldPrev   = $previous ? ($previous->gold_999_rate * 10) : null;
                $goldChange = $previous ? ($goldRate - $goldPrev) : null;
                $goldPct    = ($goldPrev && $goldPrev > 0)
                    ? (($goldChange / $goldPrev) * 100)
                    : null;
                $goldColor = $goldChange > 0 ? '#16a34a' : ($goldChange < 0 ? '#e60000' : '#475569');
                $goldIcon = $goldChange > 0 ? 'fa-arrow-up' : ($goldChange < 0 ? 'fa-arrow-down' : 'fa-minus');
            @endphp
            <div class="col-6 border-end border-2">
                <h4 class="fw-bold mb-3" style="font-size: 1.2rem; color: #000; letter-spacing: 1px; font-family: Arial, sans-serif;">GOLD</h4>
                <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
                    @if($goldChange !== null && $goldChange != 0)
                    <i class="fas {{ $goldIcon }}" style="color: {{ $goldColor }}; font-size: 1.8rem; text-shadow: 1px 1px 1px rgba(0,0,0,0.1);"></i>
                    @endif
                    <span class="fw-bold" style="font-size: 2.8rem; color: {{ $goldColor }}; font-family: Arial, sans-serif; letter-spacing: -1px;">{{ number_format($goldRate, 2) }}</span>
                </div>
                @if($goldChange !== null)
                <div class="fw-bold mb-1" style="color: {{ $goldColor }}; font-size: 1rem; font-family: Arial, sans-serif;">
                    {{ $goldChange > 0 ? '+' : '' }}{{ number_format($goldChange, 2) }} ({{ $goldChange > 0 ? '+' : '' }}{{ number_format($goldPct, 3) }}%)
                </div>
                @endif
                <div class="text-secondary fw-bold" style="font-size: 0.95rem; font-family: Arial, sans-serif;">Rs ₹ / 10gm</div>
            </div>

            {{-- SILVER COLUMN --}}
            @php
                $silverRate = $current->silver_999_rate * 1000;
                $silverPrev = $previous ? ($previous->silver_999_rate * 1000) : null;
                $silverChange = $previous ? ($silverRate - $silverPrev) : null;
                $silverPct    = ($silverPrev && $silverPrev > 0)
                    ? (($silverChange / $silverPrev) * 100)
                    : null;
                $silverColor = $silverChange > 0 ? '#16a34a' : ($silverChange < 0 ? '#e60000' : '#475569');
                $silverIcon = $silverChange > 0 ? 'fa-arrow-up' : ($silverChange < 0 ? 'fa-arrow-down' : 'fa-minus');
            @endphp
            <div class="col-6">
                <h4 class="fw-bold mb-3" style="font-size: 1.2rem; color: #000; letter-spacing: 1px; font-family: Arial, sans-serif;">SILVER</h4>
                <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
                    @if($silverChange !== null && $silverChange != 0)
                    <i class="fas {{ $silverIcon }}" style="color: {{ $silverColor }}; font-size: 1.8rem; text-shadow: 1px 1px 1px rgba(0,0,0,0.1);"></i>
                    @endif
                    <span class="fw-bold" style="font-size: 2.8rem; color: {{ $silverColor }}; font-family: Arial, sans-serif; letter-spacing: -1px;">{{ number_format($silverRate, 2) }}</span>
                </div>
                @if($silverChange !== null)
                <div class="fw-bold mb-1" style="color: {{ $silverColor }}; font-size: 1rem; font-family: Arial, sans-serif;">
                    {{ $silverChange > 0 ? '+' : '' }}{{ number_format($silverChange, 2) }} ({{ $silverChange > 0 ? '+' : '' }}{{ number_format($silverPct, 3) }}%)
                </div>
                @endif
                <div class="text-secondary fw-bold" style="font-size: 0.95rem; font-family: Arial, sans-serif;">Rs ₹ / 1kg</div>
            </div>
        </div>

        @else
        <div class="no-data-state">
            <i class="fas fa-coins"></i>
            <h4>Today's rates have not been published yet.</h4>
            <p>Check back shortly. Rates are updated every morning and evening by Josha Infinity.</p>
        </div>
        @endif
    </div>
</section>

{{-- ─── GOLD PURITY TABLE + CHART ────────────────────────── --}}
<section class="purity-section">
    <div class="container">
        <div class="row align-items-end mb-4" data-aos="fade-up">
            <div class="col">
                <h2 class="section-title" style="color: #b45309; font-size: 1.8rem;">Gold Rate Today in India</h2>
            </div>
        </div>

        @if($current && count($goldTable))
        <div class="table-responsive" style="border: 1px solid #e2e8f0; background: #fff;" data-aos="fade-up" data-aos-delay="80">
            <table class="table mb-0" style="width: 100%; font-size: 0.95rem;">
                <thead style="background: #fff;">
                    <tr>
                        <th style="color: #94a3b8; font-weight: 500; text-align: center; border-right: 1px solid #e2e8f0; border-bottom: 2px solid #e2e8f0; padding: 10px;">Name</th>
                        <th style="color: #94a3b8; font-weight: 500; text-align: center; border-right: 1px solid #e2e8f0; border-bottom: 2px solid #e2e8f0; padding: 10px;">1 Gram</th>
                        <th style="color: #94a3b8; font-weight: 500; text-align: center; border-right: 1px solid #e2e8f0; border-bottom: 2px solid #e2e8f0; padding: 10px;">10 Gram</th>
                        <th style="color: #94a3b8; font-weight: 500; text-align: center; border-right: 1px solid #e2e8f0; border-bottom: 2px solid #e2e8f0; padding: 10px;">100 Gram</th>
                        <th style="color: #94a3b8; font-weight: 500; text-align: center; border-right: 1px solid #e2e8f0; border-bottom: 2px solid #e2e8f0; padding: 10px;">1 Kilogram</th>
                        <th style="color: #94a3b8; font-weight: 500; text-align: center; border-right: 1px solid #e2e8f0; border-bottom: 2px solid #e2e8f0; padding: 10px;">1 Ounce</th>
                        <th style="color: #94a3b8; font-weight: 500; text-align: center; border-bottom: 2px solid #e2e8f0; padding: 10px;">1 Tola</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($goldTable as $row)
                    @php
                        $karat = $row['purity'];
                    @endphp
                    <tr>
                        <td style="color: #b45309; font-weight: 700; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; padding: 10px 15px;">
                            Gold {{ str_replace('K', ' Karat', $karat) }} <span style="color: #475569; font-size: 0.85em;">(Rs ₹)</span>
                        </td>
                        <td style="color: #b45309; font-weight: 700; text-align: right; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; padding: 10px 15px;">{{ number_format($row['weights'][1]) }}</td>
                        <td style="color: #b45309; font-weight: 700; text-align: right; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; padding: 10px 15px;">{{ number_format($row['weights'][10]) }}</td>
                        <td style="color: #b45309; font-weight: 700; text-align: right; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; padding: 10px 15px;">{{ number_format($row['weights'][100]) }}</td>
                        <td style="color: #b45309; font-weight: 700; text-align: right; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; padding: 10px 15px;">{{ number_format($row['weights'][1000]) }}</td>
                        <td style="color: #b45309; font-weight: 700; text-align: right; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; padding: 10px 15px;">{{ number_format($row['weights']['ounce'] ?? 0) }}</td>
                        <td style="color: #b45309; font-weight: 700; text-align: right; border-bottom: 1px solid #e2e8f0; padding: 10px 15px;">{{ number_format($row['weights']['tola'] ?? 0) }}</td>
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
        <div class="row align-items-end mt-5 mb-4" data-aos="fade-up">
            <div class="col">
                <h2 class="section-title" style="color: #475569; font-size: 1.8rem;">Silver Rate Today in India</h2>
            </div>
        </div>
        
        @if($current && count($silverTable))
        <div class="table-responsive" style="border: 1px solid #e2e8f0; background: #fff;" data-aos="fade-up">
            <table class="table mb-0" style="width: 100%; font-size: 0.95rem;">
                <thead style="background: #fff;">
                    <tr>
                        <th style="color: #94a3b8; font-weight: 500; text-align: center; border-right: 1px solid #e2e8f0; border-bottom: 2px solid #e2e8f0; padding: 10px;">Name</th>
                        <th style="color: #94a3b8; font-weight: 500; text-align: center; border-right: 1px solid #e2e8f0; border-bottom: 2px solid #e2e8f0; padding: 10px;">1 Gram</th>
                        <th style="color: #94a3b8; font-weight: 500; text-align: center; border-right: 1px solid #e2e8f0; border-bottom: 2px solid #e2e8f0; padding: 10px;">10 Gram</th>
                        <th style="color: #94a3b8; font-weight: 500; text-align: center; border-right: 1px solid #e2e8f0; border-bottom: 2px solid #e2e8f0; padding: 10px;">100 Gram</th>
                        <th style="color: #94a3b8; font-weight: 500; text-align: center; border-right: 1px solid #e2e8f0; border-bottom: 2px solid #e2e8f0; padding: 10px;">1 Kilogram</th>
                        <th style="color: #94a3b8; font-weight: 500; text-align: center; border-right: 1px solid #e2e8f0; border-bottom: 2px solid #e2e8f0; padding: 10px;">1 Ounce</th>
                        <th style="color: #94a3b8; font-weight: 500; text-align: center; border-bottom: 2px solid #e2e8f0; padding: 10px;">1 Tola</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($silverTable as $row)
                    <tr>
                        <td style="color: #475569; font-weight: 700; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; padding: 10px 15px;">
                            {{ $row['name'] }} <span style="color: #64748b; font-size: 0.85em;">(Rs ₹)</span>
                        </td>
                        <td style="color: #475569; font-weight: 700; text-align: right; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; padding: 10px 15px;">{{ number_format($row['weights'][1]) }}</td>
                        <td style="color: #475569; font-weight: 700; text-align: right; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; padding: 10px 15px;">{{ number_format($row['weights'][10]) }}</td>
                        <td style="color: #475569; font-weight: 700; text-align: right; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; padding: 10px 15px;">{{ number_format($row['weights'][100]) }}</td>
                        <td style="color: #475569; font-weight: 700; text-align: right; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; padding: 10px 15px;">{{ number_format($row['weights'][1000]) }}</td>
                        <td style="color: #475569; font-weight: 700; text-align: right; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; padding: 10px 15px;">{{ number_format($row['weights']['ounce'] ?? 0) }}</td>
                        <td style="color: #475569; font-weight: 700; text-align: right; border-bottom: 1px solid #e2e8f0; padding: 10px 15px;">{{ number_format($row['weights']['tola'] ?? 0) }}</td>
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
        <div class="no-data-state">
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
