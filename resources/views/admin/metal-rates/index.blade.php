@extends('admin.layouts.app')
@section('title', 'Live Rate Management')

@push('styles')
<style>
    .rate-module-header {
        background: linear-gradient(135deg, #1a1005 0%, #3d2a07 50%, #1a1005 100%);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(201,168,76,0.3);
    }
    .rate-module-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(201,168,76,0.15) 0%, transparent 70%);
        pointer-events: none;
    }
    .rate-module-header h1 {
        color: #c9a84c;
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0;
    }
    .rate-module-header p {
        color: #e8c878;
        margin: 0.5rem 0 0;
        opacity: 0.8;
    }
    .session-card {
        border-radius: 14px;
        border: 2px solid transparent;
        transition: all 0.3s;
        overflow: hidden;
    }
    .session-card.morning {
        border-color: rgba(251,191,36,0.3);
        background: linear-gradient(135deg, #fef9eb, #fff8dc);
    }
    .session-card.evening {
        border-color: rgba(99,102,241,0.3);
        background: linear-gradient(135deg, #eef2ff, #f0f0ff);
    }
    .session-card .card-header-custom {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(0,0,0,0.06);
    }
    .session-card.morning .card-header-custom { background: rgba(251,191,36,0.1); }
    .session-card.evening .card-header-custom { background: rgba(99,102,241,0.08); }
    .session-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    .session-card.morning .session-icon { background: rgba(251,191,36,0.2); color: #d97706; }
    .session-card.evening .session-icon { background: rgba(99,102,241,0.15); color: #6366f1; }
    .rate-display-big {
        font-size: 2rem;
        font-weight: 800;
        color: #1e293b;
        line-height: 1;
    }
    .rate-label { font-size: 0.7rem; text-transform: uppercase; letter-spacing: .08em; color: #94a3b8; font-weight: 600; }
    .rate-value-block { text-align: center; padding: 0.5rem; }
    .session-empty {
        text-align: center;
        padding: 2rem;
        color: #94a3b8;
    }
    .session-empty i { font-size: 2rem; margin-bottom: 0.5rem; display: block; }
    .history-table th { font-size: 0.78rem; text-transform: uppercase; letter-spacing: .06em; color: #64748b; font-weight: 600; white-space: nowrap; }
    .badge-morning { background: #fef3c7; color: #d97706; border-radius: 20px; padding: 3px 12px; font-size: 0.75rem; font-weight: 600; }
    .badge-evening { background: #ede9fe; color: #6d28d9; border-radius: 20px; padding: 3px 12px; font-size: 0.75rem; font-weight: 600; }
    .gold-val { color: #b45309; font-weight: 700; }
    .silver-val { color: #475569; font-weight: 700; }
    .btn-gold { background: linear-gradient(135deg, #c9a84c, #e8c878); color: #1a1005; font-weight: 700; border: none; }
    .btn-gold:hover { background: linear-gradient(135deg, #b8972d, #c9a84c); color: #1a1005; }
    .action-btns { display: flex; gap: 0.4rem; align-items: center; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="rate-module-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h1><i class="fas fa-coins me-2"></i>Live Rate Management</h1>
                <p>Enter today's gold & silver rates — the website updates instantly.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.metal-rates.create') }}" class="btn btn-gold btn-sm px-4">
                    <i class="fas fa-plus me-1"></i> Enter Session Rates
                </a>
                <a href="{{ route('admin.metal-rates.history') }}" class="btn btn-outline-light btn-sm px-4">
                    <i class="fas fa-history me-1"></i> Full History
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success border-0 rounded-3 mb-4" style="background:#d1fae5;color:#065f46;">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    </div>
    @endif

    {{-- Sample (demo) history loaded by `php artisan rates:sample` --}}
    @if($sampleCount > 0)
    <div class="alert border-0 rounded-3 mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2" style="background:#fef3c7;color:#92400e;">
        <div>
            <i class="fas fa-flask me-2"></i>
            <strong>{{ number_format($sampleCount) }} sample rate entries</strong> are loaded as demo history.
            A real rate you save for the same date and session replaces its sample. Remove the rest before going live.
        </div>
        <form method="POST" action="{{ route('admin.metal-rates.clear-sample') }}" onsubmit="return confirm('Remove all sample rates? Real rates are not affected.')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash me-1"></i>Remove sample data</button>
        </form>
    </div>
    @endif

    {{-- TODAY'S SESSIONS --}}
    <div class="mb-3">
        <h5 class="fw-bold text-slate-700 mb-3">
            <i class="fas fa-calendar-day me-2 text-warning"></i>
            Today's Sessions — {{ \Carbon\Carbon::today()->format('l, d F Y') }}
        </h5>
    </div>

    <div class="row g-4 mb-5">
        {{-- MORNING CARD --}}
        <div class="col-md-6">
            @php $morning = $todayRates->firstWhere('session', 'morning'); @endphp
            <div class="session-card morning h-100">
                <div class="card-header-custom">
                    <div class="session-icon"><i class="fas fa-sun"></i></div>
                    <div>
                        <div class="fw-bold text-amber-700">Morning Session</div>
                        <div class="text-muted" style="font-size:.8rem;">First update of the day</div>
                    </div>
                    @if($morning)
                    <div class="ms-auto">
                        <span class="badge bg-success">✓ Entered</span>
                    </div>
                    @else
                    <div class="ms-auto">
                        <span class="badge bg-warning text-dark">Pending</span>
                    </div>
                    @endif
                </div>

                @if($morning)
                <div class="p-4">
                    <div class="row text-center g-3">
                        <div class="col-6 rate-value-block">
                            <div class="rate-label">Gold 999 / 1 gm</div>
                            <div class="rate-display-big gold-val">₹{{ number_format($morning->gold_999_rate, 2) }}</div>
                        </div>
                        <div class="col-6 rate-value-block">
                            <div class="rate-label">Silver 999 / 1 gm</div>
                            <div class="rate-display-big silver-val">₹{{ number_format($morning->silver_999_rate, 2) }}</div>
                        </div>
                    </div>
                    @if($morning->remarks)
                    <div class="mt-3 p-2 bg-white rounded-3 border" style="font-size:.85rem;color:#555;">
                        <i class="fas fa-info-circle me-1 text-warning"></i>{{ $morning->remarks }}
                    </div>
                    @endif
                    <div class="mt-3 d-flex gap-2">
                        <a href="{{ route('admin.metal-rates.edit', $morning) }}" class="btn btn-sm btn-outline-warning flex-fill">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <form method="POST" action="{{ route('admin.metal-rates.destroy', $morning) }}" onsubmit="return confirm('Delete this entry?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
                @else
                <div class="session-empty">
                    <i class="fas fa-clock text-warning"></i>
                    <p class="mb-2 fw-semibold">Morning rates not entered yet</p>
                    <a href="{{ route('admin.metal-rates.create') }}?session=morning" class="btn btn-warning btn-sm">
                        <i class="fas fa-plus me-1"></i>Enter Now
                    </a>
                </div>
                @endif
            </div>
        </div>

        {{-- EVENING CARD --}}
        <div class="col-md-6">
            @php $evening = $todayRates->firstWhere('session', 'evening'); @endphp
            <div class="session-card evening h-100">
                <div class="card-header-custom">
                    <div class="session-icon"><i class="fas fa-moon"></i></div>
                    <div>
                        <div class="fw-bold" style="color:#4338ca;">Evening Session</div>
                        <div class="text-muted" style="font-size:.8rem;">End-of-day update</div>
                    </div>
                    @if($evening)
                    <div class="ms-auto">
                        <span class="badge bg-success">✓ Entered</span>
                    </div>
                    @else
                    <div class="ms-auto">
                        <span class="badge bg-secondary">Not yet</span>
                    </div>
                    @endif
                </div>

                @if($evening)
                <div class="p-4">
                    <div class="row text-center g-3">
                        <div class="col-6 rate-value-block">
                            <div class="rate-label">Gold 999 / 1 gm</div>
                            <div class="rate-display-big gold-val">₹{{ number_format($evening->gold_999_rate, 2) }}</div>
                        </div>
                        <div class="col-6 rate-value-block">
                            <div class="rate-label">Silver 999 / 1 gm</div>
                            <div class="rate-display-big silver-val">₹{{ number_format($evening->silver_999_rate, 2) }}</div>
                        </div>
                    </div>
                    @if($evening->remarks)
                    <div class="mt-3 p-2 bg-white rounded-3 border" style="font-size:.85rem;color:#555;">
                        <i class="fas fa-info-circle me-1 text-primary"></i>{{ $evening->remarks }}
                    </div>
                    @endif
                    <div class="mt-3 d-flex gap-2">
                        <a href="{{ route('admin.metal-rates.edit', $evening) }}" class="btn btn-sm btn-outline-primary flex-fill">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <form method="POST" action="{{ route('admin.metal-rates.destroy', $evening) }}" onsubmit="return confirm('Delete this entry?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
                @else
                <div class="session-empty">
                    <i class="fas fa-moon text-primary"></i>
                    <p class="mb-2 fw-semibold">Evening rates not entered yet</p>
                    <a href="{{ route('admin.metal-rates.create') }}?session=evening" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Enter Now
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- RATE HISTORY TABLE --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0"><i class="fas fa-table me-2 text-warning"></i>Recent Rate History</h5>
                <a href="{{ route('admin.metal-rates.history') }}" class="btn btn-sm btn-outline-secondary">View All</a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 history-table">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Date</th>
                            <th>Session</th>
                            <th>Gold 999 (per 1gm)</th>
                            <th>Silver 999 (per 1gm)</th>
                            <th>Remarks</th>
                            <th>Updated At</th>
                            <th class="pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($history as $rate)
                        <tr>
                            <td class="ps-4 fw-semibold">{{ $rate->rate_date->format('d M Y') }}@if($rate->is_sample)<span class="badge bg-warning text-dark ms-2" style="font-size:.65rem;">Sample</span>@endif</td>
                            <td>
                                @if($rate->session === 'morning')
                                    <span class="badge-morning"><i class="fas fa-sun me-1"></i>Morning</span>
                                @else
                                    <span class="badge-evening"><i class="fas fa-moon me-1"></i>Evening</span>
                                @endif
                            </td>
                            <td class="gold-val">₹{{ number_format($rate->gold_999_rate, 2) }}</td>
                            <td class="silver-val">₹{{ number_format($rate->silver_999_rate, 2) }}</td>
                            <td style="max-width:200px;font-size:.85rem;color:#555;">{{ $rate->remarks ?? '—' }}</td>
                            <td style="font-size:.82rem;color:#94a3b8;">{{ $rate->updated_at->format('d M, H:i') }}</td>
                            <td class="pe-4">
                                <div class="action-btns">
                                    <a href="{{ route('admin.metal-rates.edit', $rate) }}" class="btn btn-xs btn-outline-secondary" style="padding:3px 10px;font-size:.8rem;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.metal-rates.destroy', $rate) }}" onsubmit="return confirm('Delete this rate?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-xs btn-outline-danger" style="padding:3px 10px;font-size:.8rem;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                No rate entries yet. Start by entering today's rates.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($history->hasPages())
            <div class="px-4 py-3">{{ $history->links() }}</div>
            @endif
        </div>
    </div>

</div>
@endsection
