@extends('admin.layouts.app')

@section('title', 'Call To Action')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h1 class="h3 mb-1">Call To Action</h1>
        <p class="text-muted mb-0">The banner shown above the footer on every public page. Everything here is optional.</p>
    </div>
    <a href="{{ route('home') }}#" target="_blank" class="btn btn-outline-secondary"><i class="fas fa-up-right-from-square me-2"></i>View on site</a>
</div>

<form action="{{ route('admin.site-content.cta.update') }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-body">
                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" role="switch" id="cta_enabled" name="cta_enabled" value="1" {{ old('cta_enabled', $cta['cta_enabled'] !== '0' ? '1' : '') ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="cta_enabled">Show the banner on the website</label>
                    </div>

                    <div class="mb-3">
                        <label for="cta_heading" class="form-label fw-semibold">Heading <span class="text-muted fw-normal">(optional)</span></label>
                        <input type="text" class="form-control @error('cta_heading') is-invalid @enderror" id="cta_heading" name="cta_heading" maxlength="120" value="{{ old('cta_heading', $cta['cta_heading']) }}" placeholder="{{ $defaults['cta_heading'] }}">
                        @error('cta_heading') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="cta_subtext" class="form-label fw-semibold">Text under the heading <span class="text-muted fw-normal">(optional)</span></label>
                        <textarea class="form-control @error('cta_subtext') is-invalid @enderror" id="cta_subtext" name="cta_subtext" rows="3" maxlength="300" placeholder="{{ $defaults['cta_subtext'] }}">{{ old('cta_subtext', $cta['cta_subtext']) }}</textarea>
                        @error('cta_subtext') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <hr class="my-4">

                    <div class="row g-3">
                        <div class="col-12"><h6 class="fw-bold mb-0">Button 1 <span class="text-muted fw-normal">&mdash; outlined</span></h6></div>
                        <div class="col-md-6">
                            <label for="cta_button1_label" class="form-label fw-semibold">Text <span class="text-muted fw-normal">(optional)</span></label>
                            <input type="text" class="form-control @error('cta_button1_label') is-invalid @enderror" id="cta_button1_label" name="cta_button1_label" maxlength="40" value="{{ old('cta_button1_label', $cta['cta_button1_label']) }}" placeholder="{{ $defaults['cta_button1_label'] }}">
                            <div class="form-text">Blank text hides this button.</div>
                            @error('cta_button1_label') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="cta_button1_url" class="form-label fw-semibold">Links to <span class="text-muted fw-normal">(optional)</span></label>
                            <input type="text" class="form-control @error('cta_button1_url') is-invalid @enderror" id="cta_button1_url" name="cta_button1_url" maxlength="255" value="{{ old('cta_button1_url', $cta['cta_button1_url']) }}" placeholder="Blank = the Live Rates page">
                            <div class="form-text">A page like <code>/live-rates</code>, a full link, or <code>tel:+91&hellip;</code></div>
                            @error('cta_button1_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12 mt-4"><h6 class="fw-bold mb-0">Button 2 <span class="text-muted fw-normal">&mdash; white</span></h6></div>
                        <div class="col-md-6">
                            <label for="cta_button2_label" class="form-label fw-semibold">Text <span class="text-muted fw-normal">(optional)</span></label>
                            <input type="text" class="form-control @error('cta_button2_label') is-invalid @enderror" id="cta_button2_label" name="cta_button2_label" maxlength="40" value="{{ old('cta_button2_label', $cta['cta_button2_label']) }}" placeholder="{{ $defaults['cta_button2_label'] }}">
                            <div class="form-text">Blank text hides this button.</div>
                            @error('cta_button2_label') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="cta_button2_url" class="form-label fw-semibold">Links to <span class="text-muted fw-normal">(optional)</span></label>
                            <input type="text" class="form-control @error('cta_button2_url') is-invalid @enderror" id="cta_button2_url" name="cta_button2_url" maxlength="255" value="{{ old('cta_button2_url', $cta['cta_button2_url']) }}" placeholder="Blank = WhatsApp chat">
                            <div class="form-text">
                                Blank opens WhatsApp with <strong>{{ $whatsapp ?: 'no number yet' }}</strong> from
                                <a href="{{ route('admin.site-content.settings', ['tab' => 'contact']) }}">Site Settings → Contact</a>
                                (hidden if there is no number).
                            </div>
                            @error('cta_button2_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-body">
                    <label class="form-label fw-semibold">Background photo <span class="text-muted fw-normal">(optional)</span></label>
                    <div class="border rounded-3 overflow-hidden bg-light mb-3 d-flex align-items-center justify-content-center" style="aspect-ratio: 16 / 9;">
                        <img id="ctaPreview" src="{{ $cta['cta_image'] ? versioned_asset('storage/' . $cta['cta_image']) : '' }}" alt="" class="w-100 h-100 {{ $cta['cta_image'] ? '' : 'd-none' }}" style="object-fit: cover;">
                        <span id="ctaPlaceholder" class="text-muted small {{ $cta['cta_image'] ? 'd-none' : '' }}"><i class="fas fa-image me-1"></i>No photo &mdash; a navy and gold background is used</span>
                    </div>

                    <input type="file" name="cta_image" id="cta_image" class="form-control @error('cta_image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
                    @error('cta_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text">Wide photo works best (about 1600 &times; 600 px). JPG, PNG or WebP, max 8 MB. A blue-navy tint is laid over it so the text stays readable.</div>

                    @if($cta['cta_image'])
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image" value="1">
                        <label class="form-check-label" for="remove_image">Remove the current photo</label>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-4">
        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>Save Call To Action</button>
    </div>
</form>
@endsection

@push('scripts')
<script>
    document.getElementById('cta_image').addEventListener('change', function () {
        const file = this.files[0];
        const img = document.getElementById('ctaPreview');
        const placeholder = document.getElementById('ctaPlaceholder');
        if (!file) { return; }
        img.src = URL.createObjectURL(file);
        img.classList.remove('d-none');
        placeholder.classList.add('d-none');
    });
</script>
@endpush
