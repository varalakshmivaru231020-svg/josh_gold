@extends('admin.layouts.app')

@section('title', 'Edit Slider')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Edit Slider</h1>
    <a href="{{ route('admin.site-content.sliders') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Sliders
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.site-content.sliders.update', $slider->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-4 p-3 rounded-3" style="background:#fef9eb;border:1px solid #f3e2b0;">
                        <label for="page" class="form-label fw-semibold">Show on page <span class="text-danger">*</span></label>
                        <select class="form-select @error('page') is-invalid @enderror" id="page" name="page" required>
                            @foreach(\App\Models\Slider::PAGES as $key => $label)
                            <option value="{{ $key }}" {{ old('page', $slider->page) === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('page') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">This slider appears at the top of the chosen page on the website.</div>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $slider->title) }}">
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="subtitle" class="form-label">Subtitle</label>
                        <input type="text" class="form-control @error('subtitle') is-invalid @enderror" id="subtitle" name="subtitle" value="{{ old('subtitle', $slider->subtitle) }}">
                        @error('subtitle')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $slider->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="button_text" class="form-label">Button Text</label>
                                <input type="text" class="form-control @error('button_text') is-invalid @enderror" id="button_text" name="button_text" value="{{ old('button_text', $slider->button_text) }}">
                                @error('button_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="button_link" class="form-label">Button Link</label>
                                <input type="text" class="form-control @error('button_link') is-invalid @enderror" id="button_link" name="button_link" value="{{ old('button_link', $slider->button_link) }}" placeholder="e.g., /live-rates or https://...">
                                @error('button_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="image" class="form-label">Slider Image</label>
                        @if($slider->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $slider->image) }}" alt="{{ $slider->title }}" class="img-fluid rounded" style="max-height: 150px;">
                        </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        <small class="text-muted">Leave empty to keep current image. Recommended: 1920x800px, Max 5MB</small>
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="imagePreview" class="mt-2"></div>
                    </div>

                    <div class="mb-3">
                        <label for="sort_order" class="form-label">Sort Order</label>
                        <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', $slider->sort_order) }}">
                        @error('sort_order')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $slider->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.site-content.sliders') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Slider
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('image').addEventListener('change', function(e) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.cssText = 'max-width: 100%; border-radius: 8px; margin-top: 10px;';
            preview.appendChild(img);
        };
        reader.readAsDataURL(e.target.files[0]);
    }
});
</script>
@endpush
