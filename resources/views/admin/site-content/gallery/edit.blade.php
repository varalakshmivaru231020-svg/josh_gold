@extends('admin.layouts.app')

@section('title', 'Edit Gallery Photo')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Edit Gallery Photo</h1>
    <a href="{{ route('admin.site-content.gallery') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Gallery
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.site-content.gallery.update', $photo->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Current Photo</label>
                    <div class="mb-3">
                        <img src="{{ versioned_asset('storage/' . $photo->image) }}" alt="{{ $photo->title }}" class="img-fluid rounded" style="max-height:250px; object-fit:cover; width:100%;">
                    </div>
                    <label class="form-label fw-semibold">Replace Photo <span class="text-muted">(optional)</span></label>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" id="imageInput">
                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div id="imagePreview" class="mt-3 d-none">
                        <img id="previewImg" src="#" alt="Preview" class="img-fluid rounded" style="max-height:200px; object-fit:cover;">
                    </div>
                    <small class="text-muted">Leave blank to keep the current photo. Max 8MB.</small>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Title <span class="text-muted">(optional)</span></label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Award Ceremony 2024" value="{{ old('title', $photo->title) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Caption <span class="text-muted">(shown on hover)</span></label>
                        <input type="text" name="caption" class="form-control" placeholder="e.g. Receiving the Best Advocate Award at NGT" value="{{ old('caption', $photo->caption) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Category</label>
                        <select name="category" class="form-select">
                            <option value="">— Select Category —</option>
                            @foreach(['Courtroom','Culture','Community','Publications','Events'] as $cat)
                            <option value="{{ $cat }}" {{ old('category', $photo->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $photo->sort_order) }}" min="0">
                        <small class="text-muted">Lower number = appears first. First photo (sort 0) spans 2 rows.</small>
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" {{ old('is_active', $photo->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Show on website</label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3 mt-2">
                <button type="submit" class="btn btn-primary px-5">
                    <i class="fas fa-save me-2"></i>Save Changes
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
