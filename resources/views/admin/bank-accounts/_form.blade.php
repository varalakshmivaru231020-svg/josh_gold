@php $account = $account ?? null; @endphp

<div class="row g-4">
    <div class="col-md-8">
        <div class="mb-3">
            <label for="bank_name" class="form-label fw-semibold">Bank Name</label>
            <input type="text" class="form-control @error('bank_name') is-invalid @enderror" id="bank_name" name="bank_name" value="{{ old('bank_name', $account?->bank_name) }}" placeholder="e.g. State Bank of India" required>
            @error('bank_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="account_holder" class="form-label fw-semibold">Account Holder Name</label>
            <input type="text" class="form-control @error('account_holder') is-invalid @enderror" id="account_holder" name="account_holder" value="{{ old('account_holder', $account?->account_holder) }}" placeholder="e.g. JOSHA GOLD AND DIAMOND JEWELLERY" required>
            @error('account_holder') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="account_number" class="form-label fw-semibold">Account Number</label>
                <input type="text" class="form-control @error('account_number') is-invalid @enderror" id="account_number" name="account_number" value="{{ old('account_number', $account?->account_number) }}" required>
                @error('account_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="ifsc_code" class="form-label fw-semibold">IFSC Code</label>
                <input type="text" class="form-control text-uppercase @error('ifsc_code') is-invalid @enderror" id="ifsc_code" name="ifsc_code" value="{{ old('ifsc_code', $account?->ifsc_code) }}" required>
                @error('ifsc_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="branch" class="form-label fw-semibold">Branch <span class="text-muted fw-normal">(optional)</span></label>
                <input type="text" class="form-control @error('branch') is-invalid @enderror" id="branch" name="branch" value="{{ old('branch', $account?->branch) }}">
                @error('branch') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="account_type" class="form-label fw-semibold">Account Type <span class="text-muted fw-normal">(optional)</span></label>
                <select class="form-select @error('account_type') is-invalid @enderror" id="account_type" name="account_type">
                    <option value="">— Select —</option>
                    @foreach(['Current', 'Savings'] as $type)
                    <option value="{{ $type }}" {{ old('account_type', $account?->account_type) === $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
                @error('account_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="upi_id" class="form-label fw-semibold">UPI ID <span class="text-muted fw-normal">(optional)</span></label>
            <input type="text" class="form-control @error('upi_id') is-invalid @enderror" id="upi_id" name="upi_id" value="{{ old('upi_id', $account?->upi_id) }}" placeholder="e.g. name@bank">
            @error('upi_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label fw-semibold">UPI / Payment QR <span class="text-muted fw-normal">(optional)</span></label>
            @if($account?->qr_image)
            <div class="mb-2">
                <img src="{{ asset('storage/' . $account->qr_image) }}" alt="QR" class="img-fluid rounded border" style="max-height:180px;">
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="remove_qr_image" name="remove_qr_image" value="1">
                <label class="form-check-label" for="remove_qr_image">Remove current QR</label>
            </div>
            @endif
            <input type="file" name="qr_image" class="form-control @error('qr_image') is-invalid @enderror" accept="image/*">
            @error('qr_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <small class="text-muted">Max 4MB.{{ $account?->qr_image ? ' Leave blank to keep the current QR.' : '' }}</small>
        </div>

        <div class="mb-3">
            <label for="sort_order" class="form-label fw-semibold">Sort Order</label>
            <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', $account?->sort_order ?? 0) }}" min="0">
            <small class="text-muted">Lower number = appears first.</small>
            @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $account?->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Show on website</label>
        </div>
    </div>
</div>
