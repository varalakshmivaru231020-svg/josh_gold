@extends('admin.layouts.app')
@section('title', 'Enter Session Rates')

@push('styles')
<style>
    .form-card { background:#fff; border-radius:16px; box-shadow:0 4px 24px rgba(0,0,0,0.08); overflow:hidden; }
    .form-card-header { background:linear-gradient(135deg,#1a1005 0%,#3d2a07 100%); padding:2rem; }
    .form-card-header h2 { font-size:1.5rem; font-weight:700; margin:0; color:#c9a84c; }
    .form-card-header p { color:rgba(232,200,120,0.8); margin:0.4rem 0 0; font-size:.9rem; }
    .form-body { padding:2rem; }
    .session-select-wrap { position:relative; }
    .session-select-wrap select {
        appearance:none; -webkit-appearance:none;
        border-radius:12px; border:2px solid #e2e8f0;
        padding:0.9rem 3rem 0.9rem 1.2rem;
        font-size:1rem; font-weight:600; color:#1e293b;
        background:#f8fafc; cursor:pointer; width:100%; transition:all 0.2s;
    }
    .session-select-wrap select:focus { border-color:#c9a84c; box-shadow:0 0 0 3px rgba(201,168,76,0.18); outline:none; }
    .session-select-wrap .select-icon { position:absolute; right:1rem; top:50%; transform:translateY(-50%); pointer-events:none; font-size:1.1rem; }
    .session-select-wrap select.morning { border-color:#f59e0b; background:#fffbeb; color:#92400e; }
    .session-select-wrap select.evening { border-color:#6366f1; background:#eef2ff; color:#3730a3; }
    .input-gold { border-radius:0 10px 10px 0; border:2px solid #fbbf24; border-left:none; font-size:1.2rem; font-weight:600; color:#92400e; background:#fffbeb; }
    .input-silver { border-radius:0 10px 10px 0; border:2px solid #94a3b8; border-left:none; font-size:1.2rem; font-weight:600; color:#334155; background:#f8fafc; }
    .input-gold:focus { border-color:#d97706; box-shadow:0 0 0 3px rgba(217,119,6,0.15); background:#fffbeb; outline:none; }
    .input-silver:focus { border-color:#64748b; box-shadow:0 0 0 3px rgba(100,116,139,0.15); background:#f8fafc; outline:none; }
    .currency-prefix { background:#fef3c7; color:#92400e; border:2px solid #fbbf24; border-right:none; border-radius:10px 0 0 10px; padding:0.75rem 1rem; font-weight:700; font-size:1.1rem; }
    .currency-prefix-silver { background:#f1f5f9; color:#475569; border:2px solid #94a3b8; border-right:none; border-radius:10px 0 0 10px; padding:0.75rem 1rem; font-weight:700; font-size:1.1rem; }
    .preview-section { margin-top:2rem; display:none; }
    .preview-section.active { display:block; }
    .preview-table-wrap { border-radius:14px; overflow:hidden; border:1px solid rgba(201,168,76,0.25); margin-bottom:1.5rem; }
    .preview-table { width:100%; border-collapse:collapse; font-size:.88rem; }
    .preview-table thead th {
        background:linear-gradient(135deg,#1a1005,#3d2a07);
        color:#c9a84c; font-weight:700; text-align:center;
        padding:0.65rem 0.8rem; font-size:.8rem; text-transform:uppercase; letter-spacing:.05em;
    }
    .preview-table thead th:first-child { text-align:left; padding-left:1.2rem; }
    .preview-table tbody tr:nth-child(even) { background:#fefcf5; }
    .preview-table tbody tr:nth-child(odd) { background:#fff; }
    .preview-table tbody tr:hover { background:#fef9eb; }
    .preview-table tbody td { padding:0.55rem 0.8rem; text-align:center; color:#92400e; font-weight:600; border-bottom:1px solid rgba(201,168,76,0.1); }
    .preview-table tbody td:first-child { text-align:left; padding-left:1.2rem; color:#334155; font-weight:700; }
    .preview-table.silver thead th { background:linear-gradient(135deg,#1e293b,#334155); color:#94a3b8; }
    .preview-table.silver tbody tr:nth-child(even) { background:#f8fafc; }
    .preview-table.silver tbody td { color:#334155; }
    .preview-table.silver tbody td:first-child { color:#1e293b; }
    .preview-table.silver tbody tr:hover { background:#f1f5f9; }
    .preview-header { font-size:1rem; font-weight:700; margin-bottom:0.75rem; display:flex; align-items:center; gap:0.5rem; }
    .preview-header.gold { color:#b45309; }
    .preview-header.silver { color:#475569; }
    .field-label { font-weight:600; font-size:.85rem; text-transform:uppercase; letter-spacing:.06em; color:#475569; margin-bottom:0.5rem; display:block; }
    .btn-submit-rate { background:linear-gradient(135deg,#c9a84c,#e8c878); color:#1a1005; font-weight:700; font-size:1.05rem; border:none; border-radius:10px; padding:0.875rem 3rem; transition:all 0.25s; }
    .btn-submit-rate:hover { background:linear-gradient(135deg,#b8972d,#c9a84c); transform:translateY(-1px); box-shadow:0 4px 12px rgba(201,168,76,0.4); }
    .section-divider { border:0; border-top:2px dashed rgba(201,168,76,0.25); margin:2rem 0; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">

            <div class="mb-4">
                <a href="{{ route('admin.metal-rates.index') }}" class="text-muted text-decoration-none">
                    <i class="fas fa-arrow-left me-1"></i> Back to Rate Management
                </a>
            </div>

            <div class="form-card">
                <div class="form-card-header">
                    <h2><i class="fas fa-coins me-2"></i>Enter Session Rates</h2>
                    <p>Enter Gold 999 &amp; Silver 999 base rates per 1 gram. All other values are auto-computed.</p>
                </div>

                <div class="form-body">
                    @if($errors->any())
                    <div class="alert alert-danger rounded-3 mb-4">
                        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('admin.metal-rates.store') }}" id="rateForm">
                        @csrf

                        <div class="row g-4 mb-4">
                            {{-- Session Dropdown --}}
                            <div class="col-md-4">
                                <label class="field-label">Session</label>
                                <div class="session-select-wrap">
                                    <select name="session" id="sessionSelect" onchange="onSessionChange(this)">
                                        <option value="morning" {{ old('session', request('session', 'morning')) === 'morning' ? 'selected' : '' }}>
                                            ☀️ Morning — First update of the day
                                        </option>
                                        <option value="evening" {{ old('session', request('session')) === 'evening' ? 'selected' : '' }}>
                                            🌙 Evening — End-of-day update
                                        </option>
                                    </select>
                                    <span class="select-icon" id="sessionIcon">☀️</span>
                                </div>
                                @foreach($filled as $f)
                                    <small class="text-success d-block mt-1">
                                        <i class="fas fa-check-circle"></i> {{ ucfirst($f) }} rates already entered today
                                    </small>
                                @endforeach
                            </div>

                            {{-- Rate Date --}}
                            <div class="col-md-4">
                                <label class="field-label">Rate Date</label>
                                <input type="date" name="rate_date" class="form-control rounded-3 fw-semibold"
                                    value="{{ old('rate_date', $today) }}" max="{{ $today }}" required
                                    style="font-size:1rem;padding:.75rem 1rem;">
                            </div>

                            {{-- Remarks --}}
                            <div class="col-md-4">
                                <label class="field-label">Remarks (Optional)</label>
                                <input type="text" name="remarks" class="form-control rounded-3"
                                    placeholder="e.g. GST inclusive | Holiday notice"
                                    maxlength="500" value="{{ old('remarks') }}"
                                    style="font-size:.95rem;padding:.75rem 1rem;">
                            </div>
                        </div>

                        <hr class="section-divider">

                        <div class="row g-4">
                            {{-- GOLD RATE --}}
                            <div class="col-md-6">
                                <label class="field-label">
                                    <i class="fas fa-circle" style="color:#d97706;font-size:.6rem;vertical-align:middle;"></i>
                                    Gold Rate — per 1 gram <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <select id="goldKaratSelect" class="form-select" style="max-width: 160px; border-radius: 10px 0 0 10px; border: 2px solid #fbbf24; border-right: none; background: #fffbeb; color: #92400e; font-weight: 600;" onchange="updatePreview()">
                                        <option value="1.0">Gold 24 Karat</option>
                                        <option value="0.9166666666666666">Gold 22 Karat</option>
                                        <option value="0.8333333333333334">Gold 20 Karat</option>
                                        <option value="0.75">Gold 18 Karat</option>
                                        <option value="0.6666666666666666">Gold 16 Karat</option>
                                        <option value="0.5833333333333334">Gold 14 Karat</option>
                                        <option value="0.5">Gold 12 Karat</option>
                                        <option value="0.4166666666666667">Gold 10 Karat</option>
                                    </select>
                                    <span class="currency-prefix" style="border-radius: 0;">₹</span>
                                    <input type="number" id="goldInput" class="form-control input-gold"
                                        step="0.01" min="1" placeholder="e.g. 7500"
                                        oninput="updatePreview()" required>
                                    <input type="hidden" name="gold_999_rate" id="hiddenGold999Rate" value="{{ old('gold_999_rate') }}">
                                </div>
                                <div class="text-muted mt-1" style="font-size:.8rem;">Select karat and enter its rate per <strong>1 gram</strong>. All karats &amp; weights auto-computed below.</div>
                            </div>

                            {{-- SILVER RATE --}}
                            <div class="col-md-6">
                                <label class="field-label">
                                    <i class="fas fa-circle" style="color:#94a3b8;font-size:.6rem;vertical-align:middle;"></i>
                                    Silver Rate — per 1 gram <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <select id="silverTypeSelect" class="form-select" style="max-width: 160px; border-radius: 10px 0 0 10px; border: 2px solid #94a3b8; border-right: none; background: #f8fafc; color: #475569; font-weight: 600;" onchange="updatePreview()">
                                        <option value="1.0">Silver 999 Fine</option>
                                        <option value="0.925">Silver 925 Sterling</option>
                                        <option value="0.900">Silver 900 Coin</option>
                                        <option value="0.800">Silver 800 German</option>
                                    </select>
                                    <span class="currency-prefix-silver" style="border-radius: 0;">₹</span>
                                    <input type="number" id="silverInput" class="form-control input-silver"
                                        step="0.01" min="1" placeholder="e.g. 95"
                                        oninput="updatePreview()" required>
                                    <input type="hidden" name="silver_999_rate" id="hiddenSilver999Rate" value="{{ old('silver_999_rate') }}">
                                </div>
                                <div class="text-muted mt-1" style="font-size:.8rem;">Select type and enter its rate per <strong>1 gram</strong>. All silver types auto-computed below.</div>
                            </div>
                        </div>

                        {{-- AUTO-COMPUTED PREVIEW --}}
                        <div class="preview-section" id="previewSection">
                            <hr class="section-divider">

                            <div class="preview-header gold">
                                <i class="fas fa-calculator"></i> Gold — Auto-Computed Rate Table (Rs ₹)
                            </div>
                            <div class="preview-table-wrap">
                                <table class="preview-table">
                                    <thead>
                                        <tr>
                                            <th>Karat</th>
                                            <th>1 Gram</th>
                                            <th>2 Gram</th>
                                            <th>4 Gram</th>
                                            <th>5 Gram</th>
                                            <th>8 Gram</th>
                                            <th>10 Gram</th>
                                            <th>100 Gram</th>
                                        </tr>
                                    </thead>
                                    <tbody id="goldTableBody">
                                        <tr><td colspan="8" class="text-center text-muted py-3">Enter gold rate above to see preview</td></tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="preview-header silver mt-3">
                                <i class="fas fa-calculator"></i> Silver — Auto-Computed Rate Table (Rs ₹)
                            </div>
                            <div class="preview-table-wrap">
                                <table class="preview-table silver">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>1 Gram</th>
                                            <th>10 Gram</th>
                                            <th>100 Gram</th>
                                            <th>500 Gram</th>
                                            <th>1 KG</th>
                                        </tr>
                                    </thead>
                                    <tbody id="silverTableBody">
                                        <tr><td colspan="6" class="text-center text-muted py-3">Enter silver rate above to see preview</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="d-flex gap-3 mt-4">
                            <button type="submit" class="btn-submit-rate">
                                <i class="fas fa-check me-2"></i>Save Rates
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

@push('scripts')
<script>
const GOLD_KARATS = [
    { label: 'Gold 24 Karat', mult: 1.0 },
    { label: 'Gold 22 Karat', mult: 22/24 },
    { label: 'Gold 20 Karat', mult: 20/24 },
    { label: 'Gold 18 Karat', mult: 18/24 },
    { label: 'Gold 16 Karat', mult: 16/24 },
    { label: 'Gold 14 Karat', mult: 14/24 },
    { label: 'Gold 12 Karat', mult: 12/24 },
    { label: 'Gold 10 Karat', mult: 10/24 },
];
const GOLD_WEIGHTS = [1, 2, 4, 5, 8, 10, 100];

const SILVER_TYPES = [
    { label: 'Silver 999 Fine',     mult: 1.0 },
    { label: 'Silver 925 Sterling', mult: 0.925 },
    { label: 'Silver 900 Coin',     mult: 0.900 },
    { label: 'Silver 800 German',   mult: 0.800 },
];
const SILVER_WEIGHTS = [1, 10, 100, 500, 1000];

function fmt(n) {
    return '\u20b9' + Number(n).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function onSessionChange(sel) {
    sel.className = sel.value;
    document.getElementById('sessionIcon').textContent = sel.value === 'morning' ? '\u2600\ufe0f' : '\ud83c\udf19';
}

function updatePreview() {
    const inputVal = parseFloat(document.getElementById('goldInput').value) || 0;
    const karatMult = parseFloat(document.getElementById('goldKaratSelect').value) || 1.0;
    
    // Calculate the 24K (999) rate based on input
    const gold1gm = inputVal > 0 ? (inputVal / karatMult) : 0;
    document.getElementById('hiddenGold999Rate').value = gold1gm > 0 ? gold1gm.toFixed(4) : '';

    const silverInputVal = parseFloat(document.getElementById('silverInput').value) || 0;
    const silverTypeMult = parseFloat(document.getElementById('silverTypeSelect').value) || 1.0;
    
    // Calculate the 999 Fine rate based on input
    const silver1gm = silverInputVal > 0 ? (silverInputVal / silverTypeMult) : 0;
    document.getElementById('hiddenSilver999Rate').value = silver1gm > 0 ? silver1gm.toFixed(4) : '';

    const hasData   = gold1gm > 0 || silver1gm > 0;
    document.getElementById('previewSection').classList.toggle('active', hasData);
    if (!hasData) return;

    if (gold1gm > 0) {
        let html = '';
        GOLD_KARATS.forEach(k => {
            const r = gold1gm * k.mult;
            html += '<tr><td>' + k.label + '</td>';
            GOLD_WEIGHTS.forEach(w => { html += '<td>' + fmt(r * w) + '</td>'; });
            html += '</tr>';
        });
        document.getElementById('goldTableBody').innerHTML = html;
    } else {
        document.getElementById('goldTableBody').innerHTML =
            '<tr><td colspan="8" class="text-center text-muted py-3">Enter gold rate above</td></tr>';
    }

    if (silver1gm > 0) {
        let html = '';
        SILVER_TYPES.forEach(t => {
            const r = silver1gm * t.mult;
            html += '<tr><td>' + t.label + '</td>';
            SILVER_WEIGHTS.forEach(w => { html += '<td>' + fmt(r * w) + '</td>'; });
            html += '</tr>';
        });
        document.getElementById('silverTableBody').innerHTML = html;
    } else {
        document.getElementById('silverTableBody').innerHTML =
            '<tr><td colspan="6" class="text-center text-muted py-3">Enter silver rate above</td></tr>';
    }
}

window.addEventListener('DOMContentLoaded', () => {
    const sel = document.getElementById('sessionSelect');
    onSessionChange(sel);
    updatePreview();
});
</script>
@endpush