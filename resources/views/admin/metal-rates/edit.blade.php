@extends('admin.layouts.app')
@section('title', 'Edit Rate Entry')

@push('styles')
<style>
    .form-card { background:#fff; border-radius:16px; box-shadow:0 4px 24px rgba(0,0,0,0.08); overflow:hidden; }
    .form-card-header { background:linear-gradient(135deg,#1a1005,#3d2a07); padding:2rem; }
    .form-card-header h2 { color:#c9a84c; font-size:1.5rem; font-weight:700; margin:0; }
    .form-card-header p  { color:rgba(232,200,120,.8); margin:.4rem 0 0; font-size:.9rem; }
    .form-body { padding:2.5rem; }
    .input-gold   { border-radius:10px; border:2px solid #fbbf24; font-size:1.2rem; font-weight:600; color:#92400e; background:#fffbeb; }
    .input-silver { border-radius:10px; border:2px solid #94a3b8; font-size:1.2rem; font-weight:600; color:#334155; background:#f8fafc; }
    .input-gold:focus   { border-color:#d97706; box-shadow:0 0 0 3px rgba(217,119,6,.15); }
    .input-silver:focus { border-color:#64748b; box-shadow:0 0 0 3px rgba(100,116,139,.15); }
    .currency-prefix { background:#fef3c7; color:#92400e; border:2px solid #fbbf24; border-right:none; border-radius:10px 0 0 10px; padding:.75rem 1rem; font-weight:700; font-size:1.1rem; }
    .currency-prefix-silver { background:#f1f5f9; color:#475569; border:2px solid #94a3b8; border-right:none; border-radius:10px 0 0 10px; padding:.75rem 1rem; font-weight:700; font-size:1.1rem; }
    .field-label { font-weight:600; font-size:.85rem; text-transform:uppercase; letter-spacing:.06em; color:#475569; margin-bottom:.5rem; }
    .session-badge { display:inline-flex; align-items:center; gap:.5rem; padding:.6rem 1.2rem; border-radius:30px; font-weight:700; font-size:.95rem; }
    .session-badge.morning { background:#fef3c7; color:#d97706; }
    .session-badge.evening { background:#ede9fe; color:#6d28d9; }
    .btn-submit-rate { background:linear-gradient(135deg,#c9a84c,#e8c878); color:#1a1005; font-weight:700; border:none; border-radius:10px; padding:.875rem 3rem; transition:all .25s; }
    .btn-submit-rate:hover { background:linear-gradient(135deg,#b8972d,#c9a84c); transform:translateY(-1px); box-shadow:0 4px 12px rgba(201,168,76,.4); }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
<div class="row justify-content-center">
<div class="col-xl-7 col-lg-9">

    <div class="mb-4">
        <a href="{{ route('admin.metal-rates.index') }}" class="text-muted text-decoration-none">
            <i class="fas fa-arrow-left me-1"></i> Back to Rate Management
        </a>
    </div>

    <div class="form-card">
        <div class="form-card-header">
            <h2><i class="fas fa-edit me-2"></i>Edit Rate Entry</h2>
            <p>Correct an existing rate entry. Date and session cannot be changed.</p>
        </div>

        <div class="form-body">
            @if($errors->any())
            <div class="alert alert-danger rounded-3 mb-4">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            {{-- Read-only info --}}
            <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded-3" style="background:#f8fafc;border:1px solid #e2e8f0;">
                <div>
                    <div class="text-muted" style="font-size:.8rem;">Date</div>
                    <div class="fw-bold">{{ $metalRate->rate_date->format('l, d F Y') }}</div>
                </div>
                <div class="ms-4">
                    <div class="text-muted" style="font-size:.8rem;">Session</div>
                    <span class="session-badge {{ $metalRate->session }}">
                        <i class="fas fa-{{ $metalRate->session === 'morning' ? 'sun' : 'moon' }}"></i>
                        {{ ucfirst($metalRate->session) }}
                    </span>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.metal-rates.update', $metalRate) }}" id="editForm">
                @csrf @method('PUT')

                <div class="mb-4">
                    <label class="field-label"><i class="fas fa-circle" style="color:#d97706;font-size:.6rem;vertical-align:middle;"></i> Gold 999 Rate (per 1 gm)</label>
                    <div class="input-group">
                        <span class="currency-prefix">₹</span>
                        <input type="number" name="gold_999_rate" class="form-control input-gold"
                            step="0.01" min="1"
                            value="{{ old('gold_999_rate', $metalRate->gold_999_rate) }}"
                            oninput="updatePreview()" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="field-label"><i class="fas fa-circle" style="color:#94a3b8;font-size:.6rem;vertical-align:middle;"></i> Silver 999 Rate (per 1 gm)</label>
                    <div class="input-group">
                        <span class="currency-prefix-silver">₹</span>
                        <input type="number" name="silver_999_rate" class="form-control input-silver"
                            step="0.01" min="1"
                            value="{{ old('silver_999_rate', $metalRate->silver_999_rate) }}"
                            oninput="updatePreview()" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="field-label">Remarks (Optional)</label>
                    <textarea name="remarks" class="form-control rounded-3" rows="2"
                        placeholder="e.g. Rates inclusive of GST" maxlength="500">{{ old('remarks', $metalRate->remarks) }}</textarea>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button type="submit" class="btn-submit-rate">
                        <i class="fas fa-save me-2"></i>Update Rates
                    </button>
                    <a href="{{ route('admin.metal-rates.index') }}" class="btn btn-outline-secondary rounded-3 px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</div>
</div>
</div>
@endsection
