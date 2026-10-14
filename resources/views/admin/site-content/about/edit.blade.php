@extends('admin.layouts.app')

@section('title', 'About Section')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h1 class="h3 mb-1">About Section</h1>
        <p class="text-muted mb-0">Shown at the top of the <strong>About Us</strong> page on the website. Everything here is optional.</p>
    </div>
    <a href="{{ route('about-us') }}" target="_blank" class="btn btn-outline-secondary"><i class="fas fa-up-right-from-square me-2"></i>View About Us page</a>
</div>

<form action="{{ route('admin.site-content.about.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="title" class="form-label fw-semibold">Heading <span class="text-muted fw-normal">(optional)</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" maxlength="255" value="{{ old('title', $about->title ?? '') }}" placeholder="e.g. Who We Are">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="subtitle" class="form-label fw-semibold">Subheading <span class="text-muted fw-normal">(optional)</span></label>
                        <input type="text" class="form-control @error('subtitle') is-invalid @enderror" id="subtitle" name="subtitle" maxlength="255" value="{{ old('subtitle', $about->subtitle ?? '') }}" placeholder="e.g. Our story">
                        <div class="form-text">Shown as a small gold label above the heading.</div>
                        @error('subtitle') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label fw-semibold">Content <span class="text-muted fw-normal">(optional)</span></label>
                        <textarea class="form-control summernote @error('content') is-invalid @enderror" id="content" name="content" rows="10">{{ old('content', $about->content ?? '') }}</textarea>
                        @error('content') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $about->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_active">Show on the About Us page</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            @foreach([
                'image'      => ['Main image', 'Shown beside the text (about 900 × 700 px).', 'aspect-ratio: 4 / 3;'],
                'hero_image' => ['Header background image', 'Faint background of the page title area (about 1600 × 500 px).', 'aspect-ratio: 16 / 5;'],
            ] as $field => [$label, $hint, $ratio])
            <div class="card mb-4">
                <div class="card-body">
                    <label class="form-label fw-semibold">{{ $label }} <span class="text-muted fw-normal">(optional)</span></label>
                    <div class="border rounded-3 overflow-hidden bg-light mb-2 d-flex align-items-center justify-content-center" style="{{ $ratio }}">
                        <img id="{{ $field }}Preview" src="{{ !empty($about->$field) ? versioned_asset('storage/' . $about->$field) : '' }}" alt="" class="w-100 h-100 {{ !empty($about->$field) ? '' : 'd-none' }}" style="object-fit: cover;">
                        <span id="{{ $field }}Placeholder" class="text-muted small {{ !empty($about->$field) ? 'd-none' : '' }}"><i class="fas fa-image me-1"></i>No image yet</span>
                    </div>
                    <input type="file" class="form-control @error($field) is-invalid @enderror" id="{{ $field }}" name="{{ $field }}" accept="image/jpeg,image/png,image/webp" data-preview="{{ $field }}">
                    @error($field) <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text">{{ $hint }} JPG, PNG or WebP, max 5 MB.</div>
                    @if(!empty($about->$field))
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="remove_{{ $field }}" name="remove_{{ $field }}" value="1">
                        <label class="form-check-label" for="remove_{{ $field }}">Remove the current image</label>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="d-flex justify-content-end mt-2">
        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>Save About Section</button>
    </div>
</form>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('input[type="file"][data-preview]').forEach(function (input) {
        input.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) { return; }
            const key = this.dataset.preview;
            const img = document.getElementById(key + 'Preview');
            img.src = URL.createObjectURL(file);
            img.classList.remove('d-none');
            document.getElementById(key + 'Placeholder').classList.add('d-none');
        });
    });
</script>
@endpush
