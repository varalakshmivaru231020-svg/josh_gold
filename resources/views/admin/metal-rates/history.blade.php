@extends('admin.layouts.app')
@section('title', 'Rate History')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0"><i class="fas fa-history me-2 text-warning"></i>Full Rate History</h4>
            <p class="text-muted mb-0" style="font-size:.9rem;">Complete log of all gold & silver rate entries</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.metal-rates.create') }}" class="btn btn-warning btn-sm">
                <i class="fas fa-plus me-1"></i>Enter New Rates
            </a>
            <a href="{{ route('admin.metal-rates.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Back
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success border-0 rounded-3 mb-4">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="font-size:.78rem;text-transform:uppercase;letter-spacing:.06em;color:#64748b;">#</th>
                            <th style="font-size:.78rem;text-transform:uppercase;letter-spacing:.06em;color:#64748b;">Date</th>
                            <th style="font-size:.78rem;text-transform:uppercase;letter-spacing:.06em;color:#64748b;">Session</th>
                            <th style="font-size:.78rem;text-transform:uppercase;letter-spacing:.06em;color:#64748b;">Gold 999 / 1gm</th>
                            <th style="font-size:.78rem;text-transform:uppercase;letter-spacing:.06em;color:#64748b;">Silver 999 / 1gm</th>
                            <th style="font-size:.78rem;text-transform:uppercase;letter-spacing:.06em;color:#64748b;">Remarks</th>
                            <th style="font-size:.78rem;text-transform:uppercase;letter-spacing:.06em;color:#64748b;">Saved At</th>
                            <th class="pe-4" style="font-size:.78rem;text-transform:uppercase;letter-spacing:.06em;color:#64748b;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($history as $i => $rate)
                        <tr>
                            <td class="ps-4 text-muted" style="font-size:.85rem;">{{ $history->firstItem() + $i }}</td>
                            <td class="fw-semibold">{{ $rate->rate_date->format('d M Y') }}@if($rate->is_sample)<span class="badge bg-warning text-dark ms-2" style="font-size:.65rem;">Sample</span>@endif</td>
                            <td>
                                @if($rate->session === 'morning')
                                <span style="background:#fef3c7;color:#d97706;border-radius:20px;padding:3px 12px;font-size:.75rem;font-weight:600;">
                                    <i class="fas fa-sun me-1"></i>Morning
                                </span>
                                @else
                                <span style="background:#ede9fe;color:#6d28d9;border-radius:20px;padding:3px 12px;font-size:.75rem;font-weight:600;">
                                    <i class="fas fa-moon me-1"></i>Evening
                                </span>
                                @endif
                            </td>
                            <td style="color:#b45309;font-weight:700;">₹{{ number_format($rate->gold_999_rate, 2) }}</td>
                            <td style="color:#475569;font-weight:700;">₹{{ number_format($rate->silver_999_rate, 2) }}</td>
                            <td style="max-width:200px;font-size:.85rem;color:#555;">{{ $rate->remarks ?? '—' }}</td>
                            <td style="font-size:.82rem;color:#94a3b8;">{{ $rate->updated_at->format('d M Y, H:i') }}</td>
                            <td class="pe-4">
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.metal-rates.edit', $rate) }}" class="btn btn-outline-secondary btn-sm" style="font-size:.8rem;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.metal-rates.destroy', $rate) }}" onsubmit="return confirm('Delete this rate entry?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm" style="font-size:.8rem;"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>No rate entries found.
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
