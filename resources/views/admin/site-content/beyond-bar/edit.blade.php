@extends('admin.layouts.app')

@section('title', 'Beyond the Bar Section')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Beyond the Bar</h1>
        <p class="text-muted small mb-0">Manage the gallery images and heading for the "Beyond the Bar" homepage section.</p>
    </div>
    <a href="{{ url('/') }}#beyond-the-bar" target="_blank" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-eye me-1"></i> Preview on Site
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<form action="{{ route('admin.site-content.beyond-bar.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
        {{-- Left Panel: Text Content --}}
        <div class="col-md-5">
            <div class="card">
                <div class="card-header"><i class="fas fa-font me-2 text-primary"></i>Heading Content</div>
                <div class="card-body">
                    <div class="mb-4">
                        <label for="bb_eyebrow" class="form-label fw-bold">Eyebrow Label</label>
                        <input type="text" class="form-control" id="bb_eyebrow" name="bb_eyebrow"
                               value="{{ old('bb_eyebrow', $settings['bb_eyebrow']) }}"
                               placeholder="e.g., BEYOND THE BAR">
                        <small class="text-muted">Small gold label shown above the main title.</small>
                    </div>
                    <div class="mb-3">
                        <label for="bb_title" class="form-label fw-bold">Main Title</label>
                        <input type="text" class="form-control" id="bb_title" name="bb_title"
                               value="{{ old('bb_title', $settings['bb_title']) }}"
                               placeholder="e.g., Filmmaker, Journalist & Cultural Visionary">
                        <small class="text-muted">The large serif heading below the eyebrow.</small>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-dark text-white"><i class="fas fa-list me-2"></i>Timeline Item 1</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Title</label>
                        <input type="text" class="form-control" name="bb_item1_title" value="{{ old('bb_item1_title', $settings['bb_item1_title']) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <textarea class="form-control" name="bb_item1_desc" rows="3">{{ old('bb_item1_desc', $settings['bb_item1_desc']) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Icon Class (FontAwesome)</label>
                        <input type="text" class="form-control" name="bb_item1_icon" value="{{ old('bb_item1_icon', $settings['bb_item1_icon']) }}" placeholder="e.g. fas fa-clapperboard">
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-dark text-white"><i class="fas fa-list me-2"></i>Timeline Item 2</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Title</label>
                        <input type="text" class="form-control" name="bb_item2_title" value="{{ old('bb_item2_title', $settings['bb_item2_title']) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <textarea class="form-control" name="bb_item2_desc" rows="3">{{ old('bb_item2_desc', $settings['bb_item2_desc']) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Icon Class (FontAwesome)</label>
                        <input type="text" class="form-control" name="bb_item2_icon" value="{{ old('bb_item2_icon', $settings['bb_item2_icon']) }}" placeholder="e.g. fas fa-newspaper">
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-dark text-white"><i class="fas fa-list me-2"></i>Timeline Item 3</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Title</label>
                        <input type="text" class="form-control" name="bb_item3_title" value="{{ old('bb_item3_title', $settings['bb_item3_title']) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <textarea class="form-control" name="bb_item3_desc" rows="3">{{ old('bb_item3_desc', $settings['bb_item3_desc']) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Icon Class (FontAwesome)</label>
                        <input type="text" class="form-control" name="bb_item3_icon" value="{{ old('bb_item3_icon', $settings['bb_item3_icon']) }}" placeholder="e.g. fas fa-feather-pointed">
                    </div>
                </div>
            </div>

            {{-- Live Preview Card --}}
            <div class="card mt-3" style="background: #f6f1e9; border: 1px solid #ddd;">
                <div class="card-body text-center py-4">
                    <span style="font-size:11px; letter-spacing:3px; color:#b08a3a; font-weight:800; text-transform:uppercase;">
                        {{ $settings['bb_eyebrow'] ?: 'BEYOND THE BAR' }}
                    </span>
                    <h4 style="font-family: Georgia, serif; margin-top: 10px; color: #121826;">
                        {{ $settings['bb_title'] ?: 'Filmmaker, Journalist & Cultural Visionary' }}
                    </h4>
                    <div style="width:40px; height:3px; background:#b08a3a; margin: 10px auto;"></div>
                    <p class="text-muted small mt-2 mb-0">↑ Live Preview of Heading</p>
                </div>
            </div>
        </div>

        {{-- Right Panel: Gallery Images --}}
        <div class="col-md-7">
            <div class="card">
                <div class="card-header"><i class="fas fa-images me-2 text-success"></i>Gallery Images (3 Images)</div>
                <div class="card-body">
                    <p class="text-muted small mb-4">
                        <i class="fas fa-info-circle me-1"></i>
                        Layout: One large <strong>top image</strong> (wide, 16:9) + two <strong>smaller images side-by-side</strong> below it.
                    </p>

                    {{-- Top Image --}}
                    <div class="mb-4 p-3 rounded" style="background:#f8fafc; border: 1px dashed #cbd5e1;">
                        <label class="form-label fw-bold text-dark">
                            <span class="badge bg-success me-2">1</span>Top Wide Image (e.g., Filmmaking scene)
                        </label>
                        @if($settings['bb_image_top'])
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $settings['bb_image_top']) }}" alt="Top Image" class="img-fluid rounded" style="max-height:160px; width:100%; object-fit:cover;">
                            <span class="badge bg-success mt-1"><i class="fas fa-check me-1"></i>Image Uploaded</span>
                        </div>
                        @else
                        <div class="mb-2 p-3 text-center rounded" style="background:#e9ecef; height:80px; display:flex; align-items:center; justify-content:center;">
                            <span class="text-muted small"><i class="fas fa-camera me-1"></i>No image uploaded yet</span>
                        </div>
                        @endif
                        <input type="file" class="form-control @error('bb_image_top') is-invalid @enderror" name="bb_image_top" accept="image/*" id="img_top">
                        <small class="text-muted">Recommended: 1200×675px (16:9 ratio). Max 8MB.</small>
                        @error('bb_image_top') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div id="preview_top" class="mt-2"></div>
                    </div>

                    <div class="row g-3">
                        {{-- Bottom Left Image --}}
                        <div class="col-6">
                            <div class="p-3 rounded h-100" style="background:#f8fafc; border: 1px dashed #cbd5e1;">
                                <label class="form-label fw-bold text-dark">
                                    <span class="badge bg-warning text-dark me-1">2</span>Bottom Left (e.g., Journalism)
                                </label>
                                @if($settings['bb_image_left'])
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $settings['bb_image_left']) }}" alt="Left Image" class="img-fluid rounded" style="max-height:120px; width:100%; object-fit:cover;">
                                    <span class="badge bg-success mt-1 d-block"><i class="fas fa-check me-1"></i>Uploaded</span>
                                </div>
                                @else
                                <div class="mb-2 p-3 text-center rounded" style="background:#e9ecef; height:60px; display:flex; align-items:center; justify-content:center;">
                                    <span class="text-muted small"><i class="fas fa-camera"></i></span>
                                </div>
                                @endif
                                <input type="file" class="form-control form-control-sm @error('bb_image_left') is-invalid @enderror" name="bb_image_left" accept="image/*" id="img_left">
                                <small class="text-muted d-block mt-1">Square or 4:3. Max 8MB.</small>
                                @error('bb_image_left') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <div id="preview_left" class="mt-2"></div>
                            </div>
                        </div>

                        {{-- Bottom Right Image --}}
                        <div class="col-6">
                            <div class="p-3 rounded h-100" style="background:#f8fafc; border: 1px dashed #cbd5e1;">
                                <label class="form-label fw-bold text-dark">
                                    <span class="badge bg-danger me-1">3</span>Bottom Right (e.g., Authoring)
                                </label>
                                @if($settings['bb_image_right'])
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $settings['bb_image_right']) }}" alt="Right Image" class="img-fluid rounded" style="max-height:120px; width:100%; object-fit:cover;">
                                    <span class="badge bg-success mt-1 d-block"><i class="fas fa-check me-1"></i>Uploaded</span>
                                </div>
                                @else
                                <div class="mb-2 p-3 text-center rounded" style="background:#e9ecef; height:60px; display:flex; align-items:center; justify-content:center;">
                                    <span class="text-muted small"><i class="fas fa-camera"></i></span>
                                </div>
                                @endif
                                <input type="file" class="form-control form-control-sm @error('bb_image_right') is-invalid @enderror" name="bb_image_right" accept="image/*" id="img_right">
                                <small class="text-muted d-block mt-1">Square or 4:3. Max 8MB.</small>
                                @error('bb_image_right') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <div id="preview_right" class="mt-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-4">
    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-light">Cancel</a>
        <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-save me-2"></i>Save Beyond the Bar Section
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script>
// Live image preview helper
function previewImage(inputId, previewId) {
    document.getElementById(inputId).addEventListener('change', function(e) {
        const preview = document.getElementById(previewId);
        preview.innerHTML = '';
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                preview.innerHTML = `<img src="${ev.target.result}" style="max-width:100%; border-radius:8px; margin-top:8px; border:2px solid #b08a3a;">
                    <span class="badge bg-warning text-dark mt-1 d-block">New image selected — save to apply</span>`;
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    });
}
previewImage('img_top', 'preview_top');
previewImage('img_left', 'preview_left');
previewImage('img_right', 'preview_right');
</script>
@endpush
