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
                    <div class="mb-3">
                        <label for="title" class="form-label">Main Heading <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $about->title ?? 'Bridging the Gap Between Law & Literature') }}" required>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="subtitle" class="form-label">Subheading (Optional)</label>
                        <input type="text" class="form-control" id="subtitle" name="subtitle" value="{{ old('subtitle', $about->subtitle ?? '') }}">
                    </div>

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

                    <div class="mb-4 text-dark">
                        <label for="image" class="form-label fw-bold">1. Biography Page Image</label>
                        @if($about && $about->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $about->image) }}" alt="Biography" class="img-fluid rounded shadow-sm border" style="max-height: 120px;">
                        </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        <small class="text-muted">Used on the dedicated "About Us" page.</small>
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4 text-dark">
                        <label for="hero_image" class="form-label fw-bold">2. Hero Portrait (Homepage)</label>
                        @if($about && $about->hero_image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $about->hero_image) }}" alt="Hero" class="img-fluid rounded shadow-sm border" style="max-height: 120px;">
                        </div>
                        @endif
                        <input type="file" class="form-control @error('hero_image') is-invalid @enderror" id="hero_image" name="hero_image" accept="image/*">
                        <small class="text-muted">Used in the top Hero section of the homepage.</small>
                        @error('hero_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4 text-dark">
                        <label for="id_card_image" class="form-label fw-bold">3. ID Card Photo (Homepage)</label>
                        @if($about && $about->id_card_image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $about->id_card_image) }}" alt="ID Card" class="img-fluid rounded shadow-sm border" style="max-height: 120px;">
                        </div>
                        @endif
                        <input type="file" class="form-control @error('id_card_image') is-invalid @enderror" id="id_card_image" name="id_card_image" accept="image/*">
                        <small class="text-muted">Used in the Digital Identity Card section.</small>
                        @error('id_card_image')
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
