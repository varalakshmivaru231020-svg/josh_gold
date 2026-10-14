{{--
    Form fields for an update (Admin → Updates), shared by create and edit.
    Expects: $blog (App\Models\Blog, unsaved on create)
--}}
@php $isNew = ! $blog->exists; @endphp

<div class="row g-4">
    <div class="col-lg-8">
        <div class="mb-3">
            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $blog->title) }}" maxlength="255" required>
            @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Content <span class="text-danger">*</span></label>
            <textarea class="form-control ckeditor @error('description') is-invalid @enderror" id="description" name="description" rows="12">{{ old('description', $blog->description) }}</textarea>
            @error('description')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            <small class="text-muted">The first lines are used as the summary on the Updates page.</small>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="mb-4">
            <label for="image" class="form-label">Cover image @if($isNew)<span class="text-danger">*</span>@endif</label>
            <div class="cover-preview mb-2" id="coverPreview">
                @if($blog->image)
                    <img src="{{ versioned_asset('storage/' . $blog->image) }}" alt="Current cover image">
                @else
                    <span class="cover-preview-empty"><i class="fas fa-image" aria-hidden="true"></i>No cover image yet</span>
                @endif
            </div>
            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/jpeg,image/png,image/webp,image/gif" @if($isNew) required @endif>
            @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted d-block mt-1">
                Shown on the Updates page and at the top of the update. Landscape works best (about 1200 × 750 px). JPG, PNG or WebP, up to 5 MB.
                @unless($isNew) Leave empty to keep the current image. @endunless
            </small>
        </div>

        <div class="mb-3">
            <label for="sort_order" class="form-label">Sort order</label>
            <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', $blog->sort_order ?? 0) }}">
            @error('sort_order')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Lower numbers show first. Updates with the same number show newest first.</small>
        </div>

        <div class="mb-3">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $isNew ? true : $blog->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Show on the website</label>
            </div>
        </div>

        @unless($isNew)
        <div class="mb-3">
            <label class="form-label">Web address</label>
            <div class="input-group">
                <span class="input-group-text">/updates/</span>
                <input type="text" class="form-control" value="{{ $blog->slug }}" readonly aria-label="Web address">
            </div>
        </div>
        @endunless
    </div>
</div>

@push('styles')
<style>
    .cover-preview { display: grid; place-items: center; aspect-ratio: 16 / 10; overflow: hidden; border: 1px dashed #d9c9a3; border-radius: 12px; background: #fbf7ee; }
    .cover-preview img { width: 100%; height: 100%; object-fit: cover; }
    .cover-preview-empty { display: flex; flex-direction: column; align-items: center; gap: .4rem; color: #9a8f7a; font-size: .85rem; }
    .cover-preview-empty i { font-size: 1.6rem; color: #c9a35c; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    if (typeof ClassicEditor !== 'undefined') {
        ClassicEditor
            .create(document.querySelector('#description'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo']
            })
            .catch(error => console.error(error));
    }

    // Preview the chosen cover image before saving
    document.getElementById('image').addEventListener('change', function (e) {
        const file = e.target.files && e.target.files[0];
        if (!file) { return; }
        const reader = new FileReader();
        reader.onload = function (ev) {
            const preview = document.getElementById('coverPreview');
            preview.innerHTML = '';
            const img = document.createElement('img');
            img.src = ev.target.result;
            img.alt = 'New cover image';
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush
