@extends('admin.layouts.app')

@section('title', 'Upload Gallery Photo')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Upload Gallery Photo</h1>
    <a href="{{ route('admin.site-content.gallery') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Gallery
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.site-content.gallery.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Photo <span class="text-danger">*</span></label>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" required id="imageInput">
                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div id="imagePreview" class="mt-3 d-none">
                        <img id="previewImg" src="#" alt="Preview" class="img-fluid rounded" style="max-height:250px; object-fit:cover;">
                    </div>
                    <small class="text-muted">Max 8MB. JPG, PNG, WEBP, GIF accepted.</small>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Title <span class="text-muted">(optional)</span></label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Award Ceremony 2024" value="{{ old('title') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Caption <span class="text-muted">(shown on hover)</span></label>
                        <input type="text" name="caption" class="form-control" placeholder="e.g. Receiving the Best Advocate Award at NGT" value="{{ old('caption') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Category <span class="text-muted">(optional)</span></label>
                        <select name="category" class="form-select">
                            <option value="">— Select Category —</option>
                            <option value="Courtroom" {{ old('category') == 'Courtroom' ? 'selected' : '' }}>Courtroom</option>
                            <option value="Culture" {{ old('category') == 'Culture' ? 'selected' : '' }}>Culture</option>
                            <option value="Community" {{ old('category') == 'Community' ? 'selected' : '' }}>Community</option>
                            <option value="Publications" {{ old('category') == 'Publications' ? 'selected' : '' }}>Publications</option>
                            <option value="Events" {{ old('category') == 'Events' ? 'selected' : '' }}>Events</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                        <small class="text-muted">Lower number = appears first. First photo (sort 0) spans 2 rows.</small>
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Show on website</label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3 mt-2">
                <button type="submit" class="btn btn-primary px-5">
                    <i class="fas fa-upload me-2"></i>Upload Photo
                </button>
                <a href="{{ route('admin.site-content.gallery') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('imageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(ev) {
            document.getElementById('previewImg').src = ev.target.result;
            document.getElementById('imagePreview').classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
