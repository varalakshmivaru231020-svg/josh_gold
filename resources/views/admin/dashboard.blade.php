@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@push('styles')
<style>
    .dash-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; height: 100%; }
    .dash-stat { padding: 1.25rem 1.4rem; display: flex; flex-direction: column; gap: .35rem; height: 100%; position: relative; overflow: hidden; }
    .dash-stat::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: var(--accent, #c9a84c); }
    .dash-label { font-size: .72rem; letter-spacing: .08em; text-transform: uppercase; font-weight: 700; color: #94a3b8; }
    .dash-value { font-size: 1.75rem; font-weight: 700; color: #0f172a; line-height: 1.15; font-variant-numeric: tabular-nums; }
    .dash-sub { font-size: .82rem; color: #64748b; }
    .dash-up { color: #16a34a; font-weight: 600; }
    .dash-down { color: #dc2626; font-weight: 600; }
    .dash-card .card-header-lite { padding: 1rem 1.4rem; border-bottom: 1px solid #eef1f5; display: flex; justify-content: space-between; align-items: center; gap: 1rem; }
    .dash-card .card-header-lite h5 { margin: 0; font-size: 1rem; font-weight: 700; color: #0f172a; }
    .session-pill { display: inline-flex; align-items: center; gap: .35rem; font-size: .78rem; font-weight: 600; padding: .25rem .65rem; border-radius: 999px; }
    .session-pill.done { background: #dcfce7; color: #15803d; }
    .session-pill.todo { background: #fef3c7; color: #b45309; }
    .quick-tile { display: flex; align-items: center; gap: .85rem; padding: 1rem 1.1rem; border: 1px solid #e2e8f0; border-radius: 12px; background: #fff; text-decoration: none; color: #0f172a; transition: border-color .2s, transform .2s, box-shadow .2s; height: 100%; }
    .quick-tile:hover { border-color: #c9a84c; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(15, 23, 42, .06); color: #0f172a; }
    .quick-tile i { width: 40px; height: 40px; border-radius: 10px; background: #fef9eb; color: #b45309; display: grid; place-items: center; font-size: 1rem; flex-shrink: 0; }
    .quick-tile strong { display: block; font-size: .95rem; }
    .quick-tile span { font-size: .8rem; color: #94a3b8; }
</style>
@endpush

@section('content')
@php
    $goldNow   = $latest ? $latest->gold_999_rate * 10 : null;      // stored per 1 gm, shown per 10 gm
    $silverNow = $latest ? $latest->silver_999_rate * 1000 : null;  // shown per 1 kg
    $change = function ($now, $prev) {
        if ($now === null || $prev === null || $prev == 0) { return null; }
        return ['diff' => $now - $prev, 'pct' => ($now - $prev) / $prev * 100];
    };
    $goldChange   = $change($goldNow,   $previous ? $previous->gold_999_rate * 10 : null);
    $silverChange = $change($silverNow, $previous ? $previous->silver_999_rate * 1000 : null);
    $sessionsDone = count($todaySessions);
@endphp

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-4 mb-4">
        <div>
            <h1 class="h3 mb-1">Dashboard</h1>
            <p class="text-muted mb-0">Gold &amp; silver rates and site content at a glance.</p>
        </div>
        <a href="{{ route('admin.metal-rates.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Enter Session Rates</a>
    </div>

    @if($counts['sample_rates'] > 0)
    <div class="alert border-0 rounded-3 mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2" style="background:#fef3c7;color:#92400e;">
        <div><i class="fas fa-flask me-2"></i><strong>{{ number_format($counts['sample_rates']) }} sample rate entries</strong> are loaded as demo history. Remove them before going live.</div>
        <a href="{{ route('admin.metal-rates.index') }}" class="btn btn-sm btn-outline-danger">Manage in Live Rates</a>
    </div>
    @endif

    {{-- Stat cards --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="dash-card dash-stat" style="--accent:#c9a84c;">
                <span class="dash-label">Gold 24K &middot; per 10 gm</span>
                <span class="dash-value">{{ $goldNow !== null ? '₹' . number_format($goldNow) : '—' }}</span>
                @if($goldChange)
                    <span class="dash-sub {{ $goldChange['diff'] >= 0 ? 'dash-up' : 'dash-down' }}">
                        {{ $goldChange['diff'] >= 0 ? '▲ +' : '▼ −' }}₹{{ number_format(abs($goldChange['diff'])) }} ({{ number_format(abs($goldChange['pct']), 2) }}%)
                    </span>
                @else
                    <span class="dash-sub">{{ $latest ? 'No earlier rate to compare' : 'No rates entered yet' }}</span>
                @endif
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="dash-card dash-stat" style="--accent:#64748b;">
                <span class="dash-label">Silver 999 &middot; per 1 kg</span>
                <span class="dash-value">{{ $silverNow !== null ? '₹' . number_format($silverNow) : '—' }}</span>
                @if($silverChange)
                    <span class="dash-sub {{ $silverChange['diff'] >= 0 ? 'dash-up' : 'dash-down' }}">
                        {{ $silverChange['diff'] >= 0 ? '▲ +' : '▼ −' }}₹{{ number_format(abs($silverChange['diff'])) }} ({{ number_format(abs($silverChange['pct']), 2) }}%)
                    </span>
                @else
                    <span class="dash-sub">{{ $latest ? 'No earlier rate to compare' : 'No rates entered yet' }}</span>
                @endif
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="dash-card dash-stat" style="--accent:#16a34a;">
                <span class="dash-label">Today's rates</span>
                <span class="dash-value">{{ $sessionsDone }} <span style="font-size:1rem;color:#94a3b8;font-weight:600;">of 2 entered</span></span>
                <span class="dash-sub d-flex gap-2 flex-wrap">
                    <span class="session-pill {{ in_array('morning', $todaySessions) ? 'done' : 'todo' }}"><i class="fas fa-sun"></i> Morning</span>
                    <span class="session-pill {{ in_array('evening', $todaySessions) ? 'done' : 'todo' }}"><i class="fas fa-moon"></i> Evening</span>
                </span>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <a href="{{ route('admin.site-content.contact-messages') }}" class="text-decoration-none d-block h-100">
                <div class="dash-card dash-stat" style="--accent:#2563eb;">
                    <span class="dash-label">Unread messages</span>
                    <span class="dash-value">{{ $counts['unread_messages'] }}</span>
                    <span class="dash-sub">From the Contact Us form</span>
                </div>
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        {{-- Recent rate entries --}}
        <div class="col-xl-7">
            <div class="dash-card">
                <div class="card-header-lite">
                    <h5><i class="fas fa-coins me-2 text-warning"></i>Recent Rate Entries</h5>
                    <a href="{{ route('admin.metal-rates.index') }}" class="btn btn-sm btn-outline-secondary">Manage</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr><th class="ps-4">Date</th><th>Session</th><th>Gold / 1 gm</th><th class="pe-4">Silver / 1 gm</th></tr>
                        </thead>
                        <tbody>
                            @forelse($recentRates as $rate)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $rate->rate_date->format('d M Y') }}@if($rate->is_sample)<span class="badge bg-warning text-dark ms-2" style="font-size:.65rem;">Sample</span>@endif</td>
                                <td class="text-capitalize">{{ $rate->session }}</td>
                                <td>₹{{ number_format($rate->gold_999_rate, 2) }}</td>
                                <td class="pe-4">₹{{ number_format($rate->silver_999_rate, 2) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No rate entries yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Recent messages --}}
        <div class="col-xl-5">
            <div class="dash-card">
                <div class="card-header-lite">
                    <h5><i class="fas fa-envelope me-2 text-primary"></i>Recent Messages</h5>
                    <a href="{{ route('admin.site-content.contact-messages') }}" class="btn btn-sm btn-outline-secondary">View all</a>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($recentMessages as $message)
                    <a href="{{ route('admin.site-content.contact-messages.view', $message->id) }}" class="list-group-item list-group-item-action px-4 py-3">
                        <div class="d-flex justify-content-between gap-2">
                            <strong>{{ $message->name }}@if($message->status === 'unread') <span class="badge bg-warning text-dark ms-1">New</span>@endif</strong>
                            <small class="text-muted text-nowrap">{{ $message->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="text-muted small text-truncate">{{ $message->subject }}</div>
                    </a>
                    @empty
                    <div class="text-center text-muted py-4">No messages yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Quick links --}}
    <h5 class="fw-bold mb-3">Manage site content</h5>
    <div class="row g-3 mb-5">
        @foreach([
            ['route' => 'admin.bank-accounts.index',         'icon' => 'fa-building-columns', 'label' => 'Bank Details',    'count' => $counts['bank_accounts'], 'unit' => 'accounts'],
            ['route' => 'admin.site-content.services',       'icon' => 'fa-concierge-bell',   'label' => 'Services',        'count' => $counts['services'],      'unit' => 'services'],
            ['route' => 'admin.site-content.blogs',          'icon' => 'fa-newspaper',        'label' => 'News & Updates',  'count' => $counts['news'],          'unit' => 'posts'],
            ['route' => 'admin.site-content.gallery',        'icon' => 'fa-camera',           'label' => 'Photo Gallery',   'count' => $counts['gallery'],       'unit' => 'photos'],
            ['route' => 'admin.site-content.settings', 'params' => ['tab' => 'offices'], 'icon' => 'fa-building', 'label' => 'Offices',         'count' => $counts['offices'],       'unit' => 'offices'],
            ['route' => 'admin.site-content.settings', 'params' => ['tab' => 'contact'], 'icon' => 'fa-address-card', 'label' => 'Contact Details', 'count' => null,                     'unit' => 'phone, email, map'],
        ] as $tile)
        <div class="col-xl-2 col-md-4 col-6">
            <a href="{{ route($tile['route'], $tile['params'] ?? []) }}" class="quick-tile">
                <i class="fas {{ $tile['icon'] }}"></i>
                <div>
                    <strong>{{ $tile['label'] }}</strong>
                    <span>{{ $tile['count'] !== null ? $tile['count'] . ' ' . $tile['unit'] : $tile['unit'] }}</span>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection
