@extends('admin.layouts.app')

@section('title', 'Dashboard')

@use('App\Support\RateBook')

@php
    $latest    = $rate['latest'];
    $published = $rate['published'];

    // "18 days", "5 hours", "under an hour"
    $ageText = function (?float $hours) {
        if ($hours === null) { return null; }
        if ($hours < 1)  { return 'under an hour'; }
        if ($hours < 24) { $h = (int) floor($hours); return $h . ' ' . ($h === 1 ? 'hour' : 'hours'); }
        $d = (int) floor($hours / 24);
        return $d . ' ' . ($d === 1 ? 'day' : 'days');
    };

    $changeText = function (?array $change) {
        if (!$change) { return null; }
        $up = $change['diff'] >= 0;
        return [
            'up'   => $up,
            'text' => ($up ? '+' : '−') . RateBook::inr(abs($change['diff'])) . ' (' . ($up ? '+' : '−') . number_format(abs($change['pct']), 2) . '%)',
        ];
    };

    $goldChange   = $changeText($rate['gold_change']);
    $silverChange = $changeText($rate['silver_change']);
    $trendChange  = $changeText($trend['change']);

    // Things that need the admin's attention, most urgent first
    $attention = [];
    if (!$latest) {
        $attention[] = ['level' => 'critical', 'icon' => 'fa-triangle-exclamation', 'title' => 'No rate published yet',
            'text' => 'The website has no gold or silver rate to show.', 'action' => ['Add rate', route('admin.metal-rates.create')]];
    } elseif ($rate['stale']) {
        $attention[] = ['level' => 'critical', 'icon' => 'fa-clock', 'title' => 'Rates not updated for ' . $ageText($rate['age_hours']),
            'text' => 'Last published ' . $published->format('d M, g:i a') . '. Publish today\'s rate.', 'action' => ['Publish rate', route('admin.metal-rates.create')]];
    }
    if ($sampleCount > 0) {
        $attention[] = ['level' => 'warning', 'icon' => 'fa-flask', 'title' => number_format($sampleCount) . ' sample rate rows',
            'text' => 'Demo prices are mixed into the website\'s charts and rate history. Remove them before going live.', 'clear_sample' => true];
    }
    if ($missingContact) {
        $attention[] = ['level' => 'warning', 'icon' => 'fa-address-card', 'title' => 'Contact details incomplete',
            'text' => 'Missing: ' . implode(', ', $missingContact) . '.', 'action' => ['Open Site Settings', route('admin.site-content.settings', ['tab' => 'contact'])]];
    }
    if ($enquiries['unread'] > 0) {
        $attention[] = ['level' => 'info', 'icon' => 'fa-envelope', 'title' => $enquiries['unread'] . ' unread ' . ($enquiries['unread'] === 1 ? 'enquiry' : 'enquiries'),
            'text' => 'Reply to visitors who used the Contact Us form.', 'action' => ['Read enquiries', route('admin.site-content.contact-messages')]];
    }

    $kpiCol = count($kpis) > 6 ? 'col-xl-3' : 'col-xl-2';
@endphp

@push('styles')
<style>
    .dash { --ok: #1f8a4c; --ok-bg: #e5f5ec; --warn: #9a6200; --warn-bg: #fdf1d8; --crit: #b42318; --crit-bg: #fde8e6; --info: #2a5b8a; --info-bg: #e8f0f8; }
    .dash .tnum { font-variant-numeric: tabular-nums; }

    /* Header */
    .dash-head { display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem; }
    .dash-eyebrow { font-size: .75rem; font-weight: 600; letter-spacing: .12em; text-transform: uppercase; color: var(--gold-ink); margin-bottom: .35rem; }
    .dash-head h1 { margin-bottom: .2rem; }
    .dash-head p { color: var(--muted); margin: 0; }
    .dash-head-actions { display: flex; gap: .6rem; flex-wrap: wrap; }
    .btn-quiet { background: #fff; border: 1px solid var(--line); color: var(--ink); }
    .btn-quiet:hover { background: #faf8f3; border-color: #d9d4c8; color: var(--ink); }

    /* Rate hero */
    .rate-hero { position: relative; height: 100%; display: flex; flex-direction: column; gap: 1.35rem; padding: 1.5rem 1.6rem; border-radius: 16px; color: #efe9dd; overflow: hidden;
        background: radial-gradient(120% 90% at 100% 0%, rgba(201,163,92,.20) 0%, rgba(201,163,92,0) 55%), linear-gradient(160deg, #1d1a14 0%, #110f0b 100%);
        border: 1px solid rgba(201,163,92,.28); box-shadow: 0 10px 30px rgba(23,20,15,.12); }
    .rate-hero-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; flex-wrap: wrap; }
    .rate-hero .eyebrow { display: block; font-size: .72rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: var(--gold); margin-bottom: .3rem; }
    .rate-hero .meta { font-size: .85rem; color: #b8b09f; }
    .rate-hero .meta strong { color: #efe9dd; font-weight: 600; }
    .chip { display: inline-flex; align-items: center; gap: .4rem; padding: .32rem .7rem; border-radius: 999px; font-size: .78rem; font-weight: 600; white-space: nowrap; border: 1px solid transparent; }
    .chip-stale { background: rgba(244,99,82,.14); color: #ffab9f; border-color: rgba(244,99,82,.35); }
    .chip-fresh { background: rgba(52,199,113,.14); color: #8fe3b1; border-color: rgba(52,199,113,.32); }
    .chip-sample { background: rgba(245,180,60,.14); color: #f7cf86; border-color: rgba(245,180,60,.35); }
    .rate-figures { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; }
    .rate-fig { padding: 1rem 1.1rem; border-radius: 12px; background: rgba(255,255,255,.035); border: 1px solid rgba(255,255,255,.07); min-width: 0; }
    .rate-fig-label { display: flex; align-items: center; gap: .45rem; font-size: .74rem; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: #b8b09f; }
    .rate-fig-label .dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
    .rate-fig-value { display: block; margin: .35rem 0 .25rem; font-size: clamp(1.55rem, 2.4vw, 2.15rem); font-weight: 700; line-height: 1.1; letter-spacing: -.01em; white-space: nowrap; }
    .rate-fig-value.gold { color: #f2d89c; }
    .rate-fig-value.silver { color: #eef0f2; }
    .rate-fig-change { display: block; font-size: .8rem; color: #a9a190; line-height: 1.5; }
    .rate-fig-change .vs { display: block; font-size: .76rem; color: #a49b89; }
    .rate-fig-change .up { color: #7ddba3; font-weight: 600; }
    .rate-fig-change .down { color: #ff9d90; font-weight: 600; }
    .rate-warning { display: flex; align-items: center; gap: .9rem; flex-wrap: wrap; padding: .85rem 1rem; border-radius: 12px; background: rgba(245,158,11,.11); border: 1px solid rgba(245,158,11,.32); color: #f6dcae; }
    .rate-warning > i { font-size: 1.15rem; color: #f5b54a; }
    .rate-warning .msg { flex: 1 1 220px; font-size: .86rem; line-height: 1.45; }
    .rate-warning strong { display: block; color: #fff; font-size: .95rem; }
    .rate-foot { display: flex; justify-content: space-between; align-items: center; gap: .75rem; flex-wrap: wrap; margin-top: auto; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,.08); }
    .today-label { font-size: .78rem; color: #b8b09f; margin-right: .2rem; }
    .session-pill { display: inline-flex; align-items: center; gap: .35rem; font-size: .76rem; font-weight: 600; padding: .25rem .65rem; border-radius: 999px; }
    .session-pill.done { background: rgba(52,199,113,.14); color: #8fe3b1; }
    .session-pill.todo { background: rgba(255,255,255,.06); color: #b8b09f; }
    .rate-hero .link-light-gold { color: var(--gold-light); text-decoration: none; font-size: .85rem; font-weight: 500; }
    .rate-hero .link-light-gold:hover { color: #fff; }

    /* Generic dashboard card */
    .dash-card { background: #fff; border: 1px solid var(--line); border-radius: 16px; box-shadow: 0 1px 2px rgba(23,20,15,.04); height: 100%; display: flex; flex-direction: column; overflow: hidden; }
    .dash-card + .dash-card { margin-top: 1.5rem; }
    .dash-card-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; padding: 1.15rem 1.35rem; border-bottom: 1px solid var(--line); }
    .dash-card-head h2 { font-size: 1rem; font-weight: 700; margin: 0; color: var(--ink); }
    .dash-card-head p { margin: .15rem 0 0; font-size: .8rem; color: var(--muted); }
    .dash-card-head .head-link { font-size: .82rem; font-weight: 500; text-decoration: none; white-space: nowrap; }
    .dash-card-body { padding: 1.15rem 1.35rem; flex: 1; }
    .stack > .dash-card { height: auto; }

    /* Trend */
    .trend-stats { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: .75rem; margin-bottom: 1rem; }
    .trend-stat { padding: .6rem .8rem; border-radius: 10px; background: #faf9f6; border: 1px solid #f0ede6; min-width: 0; }
    .trend-stat span { display: block; font-size: .7rem; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: var(--muted); }
    .trend-stat strong { display: block; font-size: 1rem; font-weight: 700; color: var(--ink); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .trend-stat strong.up { color: var(--ok); }
    .trend-stat strong.down { color: var(--crit); }
    .trend-chart { position: relative; height: 210px; }
    .trend-note { font-size: .76rem; color: var(--muted); margin-top: .65rem; display: flex; align-items: center; gap: .4rem; }
    .trend-empty { height: 210px; display: grid; place-items: center; text-align: center; color: var(--muted); font-size: .9rem; }

    /* KPI tiles */
    .kpi { position: relative; display: flex; align-items: flex-start; gap: .9rem; height: 100%; padding: 1rem 1.1rem; background: #fff; border: 1px solid var(--line); border-radius: 14px; text-decoration: none; color: var(--ink); transition: border-color .2s, box-shadow .2s, transform .2s; }
    a.kpi:hover { border-color: var(--gold-line); box-shadow: 0 8px 22px rgba(23,20,15,.07); transform: translateY(-2px); color: var(--ink); }
    .kpi-icon { width: 40px; height: 40px; border-radius: 11px; display: grid; place-items: center; flex-shrink: 0; background: var(--gold-soft); color: var(--gold-ink); font-size: .95rem; }
    .kpi-text { display: flex; flex-direction: column; min-width: 0; flex: 1; }
    .kpi.is-alert .kpi-icon { background: var(--crit-bg); color: var(--crit); }
    .kpi-arrow { position: absolute; top: 1rem; right: 1rem; color: #c5bfb2; font-size: .72rem; transition: color .2s, transform .2s; }
    a.kpi:hover .kpi-arrow { color: var(--gold-ink); transform: translateX(2px); }
    .kpi-value { font-size: 1.55rem; font-weight: 700; line-height: 1.1; letter-spacing: -.01em; }
    .kpi-label { font-size: .84rem; font-weight: 600; color: var(--ink-2); margin-top: .15rem; }
    .kpi-note { font-size: .75rem; color: var(--muted); margin-top: .1rem; }

    /* Tables */
    .dash-table { margin: 0; }
    .dash-table thead th { background: #faf9f6; }
    .dash-table tbody td { padding: .8rem 1rem; }
    .dash-table th:first-child, .dash-table td:first-child { padding-left: 1.35rem; }
    .dash-table th:last-child, .dash-table td:last-child { padding-right: 1.35rem; }
    .dash-table tbody tr:last-child td { border-bottom: 0; }
    .msg-from strong { display: block; font-size: .88rem; }
    .msg-from span, .msg-preview span { display: block; font-size: .78rem; color: var(--muted); }
    .msg-cell { width: 100%; max-width: 0; }
    .msg-preview strong { display: block; font-size: .86rem; font-weight: 600; }
    .msg-preview strong, .msg-preview span { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .status-badge { display: inline-flex; align-items: center; gap: .35rem; font-size: .74rem; font-weight: 600; padding: .22rem .6rem; border-radius: 999px; white-space: nowrap; }
    .status-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .status-badge.unread { background: var(--crit-bg); color: var(--crit); }
    .status-badge.read { background: #f1efe9; color: #6f695e; }
    .status-badge.replied { background: var(--ok-bg); color: var(--ok); }
    .sample-badge { display: inline-block; margin-left: .45rem; font-size: .66rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; padding: .15rem .45rem; border-radius: 5px; background: var(--warn-bg); color: var(--warn); vertical-align: middle; }
    .session-tag { display: inline-flex; align-items: center; gap: .35rem; font-size: .8rem; color: var(--ink-2); text-transform: capitalize; }
    .session-tag i { color: var(--gold-600); font-size: .8rem; }
    .row-link { color: #a49e91; padding: .25rem .4rem; border-radius: 6px; }
    .row-link:hover { color: var(--gold-ink); background: var(--gold-soft); }
    .empty-state { text-align: center; padding: 2.25rem 1rem; color: var(--muted); }
    .empty-state i { font-size: 1.6rem; color: #d3cdbf; margin-bottom: .6rem; }
    .empty-state strong { display: block; color: var(--ink-2); font-size: .92rem; margin-bottom: .15rem; }
    .empty-state span { font-size: .82rem; }

    /* Needs attention */
    .attn-list { list-style: none; margin: 0; padding: 0; }
    .attn-item { display: flex; gap: .85rem; padding: .95rem 1.35rem; border-bottom: 1px solid #f1efe9; }
    .attn-item:last-child { border-bottom: 0; }
    .attn-icon { width: 34px; height: 34px; border-radius: 10px; display: grid; place-items: center; flex-shrink: 0; font-size: .88rem; }
    .attn-critical .attn-icon { background: var(--crit-bg); color: var(--crit); }
    .attn-warning .attn-icon { background: var(--warn-bg); color: var(--warn); }
    .attn-info .attn-icon { background: var(--info-bg); color: var(--info); }
    .attn-body { min-width: 0; flex: 1; }
    .attn-level { font-size: .66rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
    .attn-critical .attn-level { color: var(--crit); }
    .attn-warning .attn-level { color: var(--warn); }
    .attn-info .attn-level { color: var(--info); }
    .attn-body strong { display: block; font-size: .9rem; color: var(--ink); margin-top: .05rem; }
    .attn-body p { font-size: .8rem; color: var(--muted); margin: .15rem 0 .55rem; line-height: 1.45; }
    .attn-ok { display: flex; align-items: center; gap: .85rem; padding: 1.1rem 1.35rem; }
    .attn-ok .attn-icon { background: var(--ok-bg); color: var(--ok); }

    /* Quick actions */
    .qa-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(96px, 1fr)); gap: .6rem; }
    .qa { display: flex; flex-direction: column; align-items: center; justify-content: flex-start; gap: .5rem; padding: .85rem .5rem .75rem; text-align: center; border: 1px solid var(--line); border-radius: 12px; text-decoration: none; color: var(--ink); background: #fff; transition: border-color .2s, background .2s; min-width: 0; }
    .qa:hover { border-color: var(--gold-line); background: #fcfaf5; color: var(--ink); }
    .qa i { width: 36px; height: 36px; border-radius: 10px; display: grid; place-items: center; background: var(--gold-soft); color: var(--gold-ink); font-size: .85rem; flex-shrink: 0; }
    .qa.primary { background: var(--charcoal); border-color: var(--charcoal); color: #f4e2bb; }
    .qa.primary:hover { background: #26221b; }
    .qa.primary i { background: var(--gold); color: #1a1408; }
    .qa strong { display: block; font-size: .8rem; font-weight: 600; line-height: 1.25; }

    @media (max-width: 575.98px) {
        .rate-hero { padding: 1.2rem; }
        .rate-figures { grid-template-columns: 1fr; gap: .75rem; }
        .trend-stats { gap: .5rem; }
        .trend-stat { padding: .5rem .6rem; }
        .trend-stat strong { font-size: .88rem; }
        .dash-head-actions { width: 100%; }
        .dash-head-actions .btn { flex: 1; white-space: nowrap; font-size: .875rem; padding: .55rem .6rem; }
        .kpi { gap: .7rem; padding: .9rem .85rem; }
        .kpi-icon { width: 34px; height: 34px; font-size: .85rem; }
        .kpi-label { font-size: .8rem; }
        .dash-table tbody td, .dash-table thead th { padding-left: .55rem; padding-right: .55rem; }
        .dash-table th:first-child, .dash-table td:first-child { padding-left: 1rem; }
        .dash-table th:last-child, .dash-table td:last-child { padding-right: .85rem; }
        .sample-badge { display: table; margin: .25rem 0 0; }
        .dash-table thead th { white-space: normal; }
        .kpi-value { font-size: 1.45rem; }
    }
</style>
@endpush

@section('content')
<div class="dash">

    {{-- Header --}}
    <div class="dash-head">
        <div>
            <div class="dash-eyebrow">{{ $now->format('l, j F Y') }}</div>
            <h1 class="h3">Dashboard</h1>
            <p>Today's rate, enquiries and website content at a glance.</p>
        </div>
        <div class="dash-head-actions">
            <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-quiet">
                <i class="fas fa-up-right-from-square me-2"></i>View website
            </a>
            <a href="{{ route('admin.metal-rates.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add today's rate
            </a>
        </div>
    </div>

    {{-- Rate status + trend --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-6">
            <section class="rate-hero" aria-labelledby="rateHeroTitle">
                <div class="rate-hero-top">
                    <div>
                        <span class="eyebrow" id="rateHeroTitle">Rate on the website</span>
                        @if($latest)
                            <div class="meta">
                                Published <strong>{{ $published->format('d M Y, g:i a') }}</strong> IST
                                &middot; {{ ucfirst($latest->session) }} session
                            </div>
                        @else
                            <div class="meta">No rate has been published yet.</div>
                        @endif
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        @if($latest && $latest->is_sample)
                            <span class="chip chip-sample"><i class="fas fa-flask"></i>Sample row</span>
                        @endif
                        @if($latest && !$rate['stale'])
                            <span class="chip chip-fresh"><i class="fas fa-circle-check"></i>Up to date</span>
                        @else
                            <span class="chip chip-stale"><i class="fas fa-triangle-exclamation"></i>{{ $latest ? 'Out of date' : 'Not published' }}</span>
                        @endif
                    </div>
                </div>

                <div class="rate-figures">
                    <div class="rate-fig">
                        <span class="rate-fig-label"><span class="dot" style="background:#e2bd6d;"></span>Gold 24K &middot; per 10 g</span>
                        <span class="rate-fig-value gold tnum">{{ $rate['gold'] !== null ? RateBook::inr($rate['gold']) : '—' }}</span>
                        <span class="rate-fig-change">
                            @if($goldChange)
                                <span class="{{ $goldChange['up'] ? 'up' : 'down' }}">{{ $goldChange['up'] ? '▲' : '▼' }} {{ $goldChange['text'] }}</span>
                                <span class="vs">vs {{ $rate['previous']->rate_date->format('d M') }} close</span>
                            @else
                                {{ $latest ? 'No earlier close to compare' : 'Add the first rate' }}
                            @endif
                        </span>
                    </div>
                    <div class="rate-fig">
                        <span class="rate-fig-label"><span class="dot" style="background:#c9ced6;"></span>Silver 999 &middot; per kg</span>
                        <span class="rate-fig-value silver tnum">{{ $rate['silver'] !== null ? RateBook::inr($rate['silver']) : '—' }}</span>
                        <span class="rate-fig-change">
                            @if($silverChange)
                                <span class="{{ $silverChange['up'] ? 'up' : 'down' }}">{{ $silverChange['up'] ? '▲' : '▼' }} {{ $silverChange['text'] }}</span>
                                <span class="vs">vs {{ $rate['previous']->rate_date->format('d M') }} close</span>
                            @else
                                {{ $latest ? 'No earlier close to compare' : 'Add the first rate' }}
                            @endif
                        </span>
                    </div>
                </div>

                @if(!$latest || $rate['stale'])
                    <div class="rate-warning" role="alert">
                        <i class="fas fa-triangle-exclamation" aria-hidden="true"></i>
                        <div class="msg">
                            <strong>Publish today's rate</strong>
                            @if($latest)
                                The website still shows the rate from {{ $published->format('d M') }} ({{ $ageText($rate['age_hours']) }} ago). Visitors see old prices until you add today's rate.
                            @else
                                Gold and silver prices appear on the website once you add a rate.
                            @endif
                        </div>
                        <a href="{{ route('admin.metal-rates.create') }}" class="btn btn-primary btn-sm px-3">
                            <i class="fas fa-plus me-1"></i>Add rate
                        </a>
                    </div>
                @endif

                <div class="rate-foot">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="today-label">Today</span>
                        <span class="session-pill {{ in_array('morning', $rate['today']) ? 'done' : 'todo' }}">
                            <i class="fas {{ in_array('morning', $rate['today']) ? 'fa-check' : 'fa-sun' }}"></i>Morning
                        </span>
                        <span class="session-pill {{ in_array('evening', $rate['today']) ? 'done' : 'todo' }}">
                            <i class="fas {{ in_array('evening', $rate['today']) ? 'fa-check' : 'fa-moon' }}"></i>Evening
                        </span>
                    </div>
                    @if($latest && !$rate['stale'])
                        <a href="{{ route('admin.metal-rates.create') }}" class="btn btn-primary btn-sm px-3"><i class="fas fa-plus me-1"></i>Add rate</a>
                    @else
                        <a href="{{ route('admin.metal-rates.index') }}" class="link-light-gold">Manage rates <i class="fas fa-arrow-right ms-1"></i></a>
                    @endif
                </div>
            </section>
        </div>

        <div class="col-xl-6">
            <section class="dash-card" aria-labelledby="trendTitle">
                <div class="dash-card-head">
                    <div>
                        <h2 id="trendTitle">Gold 24K &middot; 30-day trend</h2>
                        <p>Daily closing rate per 10 g (evening, or morning if no evening rate)</p>
                    </div>
                    @if(Route::has('admin.metal-rates.history'))
                        <a href="{{ route('admin.metal-rates.history') }}" class="head-link">History <i class="fas fa-arrow-right ms-1"></i></a>
                    @endif
                </div>
                <div class="dash-card-body">
                    @if(count($trend['values']) > 1)
                        <div class="trend-stats">
                            <div class="trend-stat">
                                <span>High</span>
                                <strong class="tnum">{{ RateBook::inr($trend['high']) }}</strong>
                            </div>
                            <div class="trend-stat">
                                <span>Low</span>
                                <strong class="tnum">{{ RateBook::inr($trend['low']) }}</strong>
                            </div>
                            <div class="trend-stat">
                                <span>{{ $trend['days'] }}-day change</span>
                                @if($trendChange)
                                    <strong class="tnum {{ $trendChange['up'] ? 'up' : 'down' }}">{{ $trendChange['up'] ? '+' : '−' }}{{ number_format(abs($trend['change']['pct']), 2) }}%</strong>
                                @else
                                    <strong>—</strong>
                                @endif
                            </div>
                        </div>
                        <div class="trend-chart">
                            <canvas id="goldTrendChart" role="img" aria-label="Line chart of the 24K gold closing rate per 10 g over the last {{ $trend['days'] }} days, from {{ RateBook::inr($trend['values'][0]) }} to {{ RateBook::inr(end($trend['values'])) }}"></canvas>
                        </div>
                        @if($trend['has_sample'])
                            <div class="trend-note"><i class="fas fa-flask"></i>Includes sample (demo) rate rows.</div>
                        @endif
                    @else
                        <div class="trend-empty">
                            <div>
                                <i class="fas fa-chart-line d-block mb-2" style="font-size:1.5rem;color:#d3cdbf;"></i>
                                The trend appears once rates for two or more days are published.
                            </div>
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>

    {{-- KPI tiles --}}
    <div class="row g-3 mb-4">
        @foreach($kpis as $kpi)
            <div class="col-6 col-md-4 {{ $kpiCol }}">
                <{{ $kpi['url'] ? 'a' : 'div' }} @if($kpi['url']) href="{{ $kpi['url'] }}" @endif class="kpi {{ !empty($kpi['alert']) ? 'is-alert' : '' }}">
                    <span class="kpi-icon"><i class="fas {{ $kpi['icon'] }}"></i></span>
                    <span class="kpi-text">
                        <span class="kpi-value tnum">{{ number_format($kpi['value']) }}</span>
                        <span class="kpi-label">{{ $kpi['label'] }}</span>
                        <span class="kpi-note">{{ $kpi['note'] }}</span>
                    </span>
                    @if($kpi['url'])<i class="fas fa-arrow-right kpi-arrow" aria-hidden="true"></i>@endif
                </{{ $kpi['url'] ? 'a' : 'div' }}>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        {{-- Left: recent enquiries + recent rates --}}
        <div class="col-xl-8 stack">
            <section class="dash-card" aria-labelledby="enquiriesTitle">
                <div class="dash-card-head">
                    <div>
                        <h2 id="enquiriesTitle">Recent enquiries</h2>
                        <p>Latest messages from the Contact Us form</p>
                    </div>
                    <a href="{{ route('admin.site-content.contact-messages') }}" class="head-link">View all <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
                @if($enquiries['recent']->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-inbox d-block"></i>
                        <strong>No enquiries yet</strong>
                        <span>Messages sent through the Contact Us form will appear here.</span>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table dash-table align-middle">
                            <thead>
                                <tr>
                                    <th>From</th>
                                    <th class="d-none d-md-table-cell">Message</th>
                                    <th>Received</th>
                                    <th>Status</th>
                                    <th class="text-end d-none d-sm-table-cell"><span class="visually-hidden">Open</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($enquiries['recent'] as $message)
                                    <tr>
                                        <td class="msg-from">
                                            <strong><a href="{{ route('admin.site-content.contact-messages.view', $message->id) }}" class="text-reset text-decoration-none">{{ $message->name }}</a></strong>
                                            <span>{{ $message->email ?: $message->phone }}</span>
                                        </td>
                                        <td class="d-none d-md-table-cell msg-cell">
                                            <div class="msg-preview">
                                                <strong>{{ $message->subject ?: 'No subject' }}</strong>
                                                <span>{{ \Illuminate\Support\Str::limit(strip_tags((string) $message->message), 90) }}</span>
                                            </div>
                                        </td>
                                        <td class="text-nowrap">
                                            <span class="d-block" style="font-size:.85rem;">{{ $message->created_at?->copy()->timezone(\App\Models\MetalRate::TIMEZONE)->format('d M Y') }}</span>
                                            <span class="d-block text-muted" style="font-size:.75rem;">{{ $message->created_at?->diffForHumans() }}</span>
                                        </td>
                                        <td>
                                            @php $status = in_array($message->status, ['unread', 'read', 'replied'], true) ? $message->status : 'read'; @endphp
                                            <span class="status-badge {{ $status }}">{{ $status === 'unread' ? 'Unread' : ucfirst($status) }}</span>
                                        </td>
                                        <td class="text-end d-none d-sm-table-cell">
                                            <a href="{{ route('admin.site-content.contact-messages.view', $message->id) }}" class="row-link" title="Open enquiry from {{ $message->name }}">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>

            <section class="dash-card" aria-labelledby="ratesTitle">
                <div class="dash-card-head">
                    <div>
                        <h2 id="ratesTitle">Recent rate entries</h2>
                        <p>Last {{ $recentRates->count() }} sessions published</p>
                    </div>
                    <a href="{{ route('admin.metal-rates.index') }}" class="head-link">Manage <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
                @if($recentRates->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-coins d-block"></i>
                        <strong>No rate entries yet</strong>
                        <span>Add the morning or evening rate to get started.</span>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table dash-table align-middle">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th class="d-none d-sm-table-cell">Session</th>
                                    <th class="text-end">Gold 24K / 10 g</th>
                                    <th class="text-end">Silver / kg</th>
                                    <th class="text-end d-none d-sm-table-cell"><span class="visually-hidden">Edit</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentRates as $row)
                                    <tr>
                                        <td class="text-nowrap fw-semibold" style="font-size:.88rem;">
                                            {{ $row->rate_date->format('d M Y') }}
                                            @if($row->is_sample)<span class="sample-badge">Sample</span>@endif
                                            <span class="d-block d-sm-none fw-normal text-muted text-capitalize" style="font-size:.75rem;">{{ $row->session }}</span>
                                        </td>
                                        <td class="d-none d-sm-table-cell">
                                            <span class="session-tag"><i class="fas {{ $row->session === 'evening' ? 'fa-moon' : 'fa-sun' }}"></i>{{ $row->session }}</span>
                                        </td>
                                        <td class="text-end tnum fw-semibold">{{ RateBook::inr($row->goldRate('24K', 10)) }}</td>
                                        <td class="text-end tnum">{{ RateBook::inr($row->silverRate(1000)) }}</td>
                                        <td class="text-end d-none d-sm-table-cell">
                                            @if(Route::has('admin.metal-rates.edit'))
                                                <a href="{{ route('admin.metal-rates.edit', $row) }}" class="row-link" title="Edit this rate"><i class="fas fa-pen"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        </div>

        {{-- Right: alerts + quick actions --}}
        <div class="col-xl-4 stack">
            <section class="dash-card" aria-labelledby="attentionTitle">
                <div class="dash-card-head">
                    <div>
                        <h2 id="attentionTitle">Needs attention</h2>
                        <p>{{ count($attention) ? count($attention) . ' ' . (count($attention) === 1 ? 'item' : 'items') . ' to review' : 'Nothing to fix right now' }}</p>
                    </div>
                </div>
                @if(empty($attention))
                    <div class="attn-ok">
                        <span class="attn-icon"><i class="fas fa-check"></i></span>
                        <div>
                            <strong style="font-size:.9rem;">All clear</strong>
                            <div class="text-muted" style="font-size:.8rem;">Rates are current and contact details are complete.</div>
                        </div>
                    </div>
                @else
                    <ul class="attn-list">
                        @foreach($attention as $item)
                            <li class="attn-item attn-{{ $item['level'] }}">
                                <span class="attn-icon"><i class="fas {{ $item['icon'] }}"></i></span>
                                <div class="attn-body">
                                    <span class="attn-level">{{ ['critical' => 'Urgent', 'warning' => 'Check', 'info' => 'New'][$item['level']] }}</span>
                                    <strong>{{ $item['title'] }}</strong>
                                    <p>{{ $item['text'] }}</p>
                                    @if(!empty($item['clear_sample']))
                                        <div class="d-flex gap-2 flex-wrap">
                                            @if(Route::has('admin.metal-rates.clear-sample'))
                                                <form method="POST" action="{{ route('admin.metal-rates.clear-sample') }}"
                                                      onsubmit="return confirm('Delete all {{ number_format($sampleCount) }} sample rate rows? Real rates are not touched. This cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash-can me-1"></i>Remove sample data
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('admin.metal-rates.index') }}" class="btn btn-sm btn-quiet">Review</a>
                                        </div>
                                    @elseif(!empty($item['action']))
                                        <a href="{{ $item['action'][1] }}" class="btn btn-sm btn-outline-primary">{{ $item['action'][0] }}</a>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            <section class="dash-card" aria-labelledby="quickTitle">
                <div class="dash-card-head">
                    <div>
                        <h2 id="quickTitle">Quick actions</h2>
                        <p>Jump straight to common tasks</p>
                    </div>
                </div>
                <div class="dash-card-body">
                    <div class="qa-grid">
                        @foreach($quickActions as $i => $action)
                            <a href="{{ $action['url'] }}" class="qa {{ $i === 0 ? 'primary' : '' }}" title="{{ $action['hint'] }}" @if($action['external']) target="_blank" rel="noopener" @endif>
                                <i class="fas {{ $action['icon'] }}" aria-hidden="true"></i>
                                <strong>{{ $action['label'] }}</strong>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection

@if(count($trend['values']) > 1)
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    (function () {
        const canvas = document.getElementById('goldTrendChart');
        if (!canvas || typeof Chart === 'undefined') { return; }

        const labels = @json($trend['labels']);
        const dates  = @json($trend['dates']);
        const values = @json($trend['values']);
        const inr = (v) => '₹' + Math.round(v).toLocaleString('en-IN');

        // Area fill fades from the line down to the axis
        const ctx = canvas.getContext('2d');
        const fill = ctx.createLinearGradient(0, 0, 0, canvas.parentElement.clientHeight || 210);
        fill.addColorStop(0, 'rgba(201, 163, 92, 0.26)');
        fill.addColorStop(1, 'rgba(201, 163, 92, 0)');

        // Vertical guide line under the hovered day
        const crosshair = {
            id: 'crosshair',
            afterDatasetsDraw(chart) {
                const active = chart.tooltip && chart.tooltip.getActiveElements();
                if (!active || !active.length) { return; }
                const x = active[0].element.x;
                const { top, bottom } = chart.chartArea;
                const c = chart.ctx;
                c.save();
                c.strokeStyle = 'rgba(29, 27, 23, 0.22)';
                c.lineWidth = 1;
                c.setLineDash([3, 3]);
                c.beginPath();
                c.moveTo(x, top);
                c.lineTo(x, bottom);
                c.stroke();
                c.restore();
            }
        };

        new Chart(canvas, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    borderColor: '#b08a43',
                    borderWidth: 2,
                    backgroundColor: fill,
                    fill: true,
                    tension: 0.3,
                    pointRadius: (c) => c.dataIndex === values.length - 1 ? 4 : 0,
                    pointBackgroundColor: '#b08a43',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 5,
                    pointHitRadius: 14
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 500 },
                interaction: { mode: 'index', intersect: false },
                layout: { padding: { top: 6, right: 4 } },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        displayColors: false,
                        backgroundColor: '#15130f',
                        titleColor: '#e8cf98',
                        bodyColor: '#ffffff',
                        titleFont: { weight: '600', size: 11 },
                        bodyFont: { weight: '700', size: 13 },
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            title: (items) => dates[items[0].dataIndex],
                            label: (item) => inr(item.parsed.y) + ' per 10 g'
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { color: '#8a8478', maxRotation: 0, autoSkip: true, maxTicksLimit: window.innerWidth < 576 ? 4 : 6, font: { size: 11 } }
                    },
                    y: {
                        grid: { color: '#efece5' },
                        border: { display: false },
                        ticks: { color: '#8a8478', maxTicksLimit: 5, font: { size: 11 }, callback: (v) => inr(v) }
                    }
                }
            },
            plugins: [crosshair]
        });
    })();
</script>
@endpush
@endif
