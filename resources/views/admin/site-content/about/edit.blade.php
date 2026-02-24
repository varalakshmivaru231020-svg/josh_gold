@extends('admin.layouts.app')

@section('title', 'About Section')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">About Section</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.site-content.about.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="designation" class="form-label">Designation Badge (Top Left)</label>
                                <input type="text" class="form-control" id="designation" name="designation" value="{{ old('designation', $about->designation ?? 'Advocate • Author • Filmmaker • Journalist') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name_kn" class="form-label">Native Name (Kannada)</label>
                                <input type="text" class="form-control" id="name_kn" name="name_kn" value="{{ old('name_kn', $about->name_kn ?? 'ವೀರೇಂದ್ರಬಾಬು ನಂಜೇಗೌಡ') }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="qualifications" class="form-label">Qualifications</label>
                        <input type="text" class="form-control" id="qualifications" name="qualifications" value="{{ old('qualifications', $about->qualifications ?? 'MBA • LLB • PhD (Pursuing)') }}">
                    </div>

                    <div class="mb-3">
                        <label for="tagline" class="form-label">Hero Tagline</label>
                        <textarea class="form-control" id="tagline" name="tagline" rows="2">{{ old('tagline', $about->tagline ?? '"A relentless guardian of justice, a storyteller of truth, and a voice that echoes across courtrooms and culture alike."') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Biography Content <span class="text-danger">*</span></label>
                        <textarea class="form-control summernote @error('content') is-invalid @enderror" id="content" name="content" rows="10">{{ old('content', $about->content ?? '') }}</textarea>
                        @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-light mb-4 text-dark border-0">
                        <div class="card-body">
                            <h5 class="card-title h6 fw-bold">Hero Statistics</h5>
                            <div class="mb-3">
                                <label for="stat_courts" class="form-label small mb-1">Court Tiers (e.g., 3+)</label>
                                <input type="text" class="form-control form-control-sm" id="stat_courts" name="stat_courts" value="{{ old('stat_courts', $about->stat_courts ?? '3+') }}">
                            </div>
                            <div class="mb-3">
                                <label for="stat_books" class="form-label small mb-1">Books (e.g., 15+)</label>
                                <input type="text" class="form-control form-control-sm" id="stat_books" name="stat_books" value="{{ old('stat_books', $about->stat_books ?? '15+') }}">
                            </div>
                            <div class="mb-3">
                                <label for="stat_offices" class="form-label small mb-1">Offices (e.g., 2)</label>
                                <input type="text" class="form-control form-control-sm" id="stat_offices" name="stat_offices" value="{{ old('stat_offices', $about->stat_offices ?? '2') }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 text-dark">
                        <label for="image" class="form-label">Shared Profile Image</label>
                        @if($about && $about->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $about->image) }}" alt="{{ $about->title ?? 'About' }}" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                        </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        <small class="text-muted d-block">This image is used in Hero and ID Card sections.</small>
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 d-none">
                        <label for="years_experience" class="form-label">Years of Experience</label>
                        <input type="text" class="form-control" name="years_experience" value="{{ old('years_experience', $about->years_experience ?? '15+') }}">
                    </div>

                    <div class="mb-3 text-dark">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $about->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_active">
                                Show Section on Homepage
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Save Changes
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
