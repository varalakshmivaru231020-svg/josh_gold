@extends('admin.layouts.app')

@section('title', 'Add Order Flow Step')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Add Order Flow Step</h1>
    <a href="{{ route('admin.site-content.order-flow') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Steps
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.site-content.order-flow.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="title" class="form-label">Step Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required placeholder="e.g., Browse Products">
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Brief description of this step">{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="icon" class="form-label">Icon Class <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" value="{{ old('icon') }}" required placeholder="e.g., fas fa-search">
                        <small class="text-muted">
                            Use Font Awesome classes. Browse icons at <a href="https://fontawesome.com/icons" target="_blank">fontawesome.com</a>
                        </small>
                        @error('icon')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Icon Preview</label>
                        <div class="border rounded p-3 d-flex align-items-center gap-3">
                            <i id="iconPreview" class="fas fa-question-circle fa-3x text-primary"></i>
                            <span class="text-muted">Enter an icon class above to see preview</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="step_number" class="form-label">Step Number <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('step_number') is-invalid @enderror" id="step_number" name="step_number" value="{{ old('step_number', 1) }}" min="1" required>
                        @error('step_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                    </div>

                    <div class="card bg-light">
                        <div class="card-body">
                            <h6>Common Icons</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm icon-btn" data-icon="fas fa-search"><i class="fas fa-search"></i></button>
                                <button type="button" class="btn btn-outline-secondary btn-sm icon-btn" data-icon="fas fa-book"><i class="fas fa-book"></i></button>
                                <button type="button" class="btn btn-outline-secondary btn-sm icon-btn" data-icon="fas fa-shopping-cart"><i class="fas fa-shopping-cart"></i></button>
                                <button type="button" class="btn btn-outline-secondary btn-sm icon-btn" data-icon="fas fa-credit-card"><i class="fas fa-credit-card"></i></button>
                                <button type="button" class="btn btn-outline-secondary btn-sm icon-btn" data-icon="fas fa-truck"><i class="fas fa-truck"></i></button>
                                <button type="button" class="btn btn-outline-secondary btn-sm icon-btn" data-icon="fas fa-box"><i class="fas fa-box"></i></button>
                                <button type="button" class="btn btn-outline-secondary btn-sm icon-btn" data-icon="fas fa-check-circle"><i class="fas fa-check-circle"></i></button>
                                <button type="button" class="btn btn-outline-secondary btn-sm icon-btn" data-icon="fas fa-user"><i class="fas fa-user"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.site-content.order-flow') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Save Step
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('icon').addEventListener('input', function() {
    const preview = document.getElementById('iconPreview');
    preview.className = this.value + ' fa-3x text-primary';
});

document.querySelectorAll('.icon-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const iconClass = this.dataset.icon;
        document.getElementById('icon').value = iconClass;
        document.getElementById('iconPreview').className = iconClass + ' fa-3x text-primary';
    });
});
</script>
@endpush
