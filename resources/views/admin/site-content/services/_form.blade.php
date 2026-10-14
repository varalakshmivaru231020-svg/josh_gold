@php $service = $service ?? null; @endphp

<div class="row g-4">
    <div class="col-md-7">
        <div class="mb-3">
            <label for="title" class="form-label fw-semibold">Title</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $service?->title) }}" required>
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label fw-semibold">Description <span class="text-muted fw-normal">(optional)</span></label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="6">{{ old('description', $service?->description) }}</textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-5">
        <div class="mb-3">
            <label class="form-label fw-semibold">Service Image <span class="text-muted fw-normal">(optional)</span></label>
            <div class="border rounded-3 overflow-hidden bg-light mb-2 d-flex align-items-center justify-content-center" style="aspect-ratio: 16 / 10;">
                <img id="serviceImagePreview" src="{{ $service?->image ? versioned_asset('storage/' . $service->image) : '' }}" alt="" class="w-100 h-100 {{ $service?->image ? '' : 'd-none' }}" style="object-fit: cover;">
                <span id="serviceImagePlaceholder" class="text-muted small {{ $service?->image ? 'd-none' : '' }}"><i class="fas fa-image me-1"></i>No image yet</span>
            </div>
            <input type="file" name="image" id="service_image" class="form-control @error('image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <div class="form-text">JPG, PNG or WebP, max 8 MB. A landscape photo (about 1200 &times; 750 px) looks best.</div>

            @if($service?->image)
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image" value="1">
                <label class="form-check-label" for="remove_image">Remove the current image</label>
            </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="sort_order" class="form-label fw-semibold">Sort Order <span class="text-muted fw-normal">(optional)</span></label>
            <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', $service?->sort_order ?? 0) }}">
            <div class="form-text">Lower numbers appear first. Blank counts as 0.</div>
            @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $service?->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Show on website</label>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('service_image').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) { return; }
        const img = document.getElementById('serviceImagePreview');
        img.src = URL.createObjectURL(file);
        img.classList.remove('d-none');
        document.getElementById('serviceImagePlaceholder').classList.add('d-none');
    });
</script>
@endpush
