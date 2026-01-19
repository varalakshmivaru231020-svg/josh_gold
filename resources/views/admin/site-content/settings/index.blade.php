@extends('admin.layouts.app')

@section('title', 'Site Settings')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Site Settings</h4>
                    <p class="text-muted mb-0">Manage your website settings</p>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-cog me-2"></i>General Settings</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.site-content.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Site Logo</label>
                            <p class="text-muted small mb-2">This logo will appear in the header and footer of the website.</p>

                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div class="logo-preview-box" style="width: 120px; height: 120px; border: 2px dashed #dee2e6; border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #f8f9fa;">
                                        @if($logo)
                                            <img src="{{ asset('storage/' . $logo) }}" alt="Current Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;" id="logoPreview">
                                        @else
                                            <img src="{{ asset('blr.jpg') }}" alt="Default Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;" id="logoPreview">
                                        @endif
                                    </div>
                                </div>
                                <div class="col">
                                    <input type="file" class="form-control @error('site_logo') is-invalid @enderror" id="site_logo_input" accept="image/*">
                                    <input type="file" name="site_logo" id="site_logo" style="display: none;">
                                    @error('site_logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Images will be automatically compressed. Original max: 10MB.</div>
                                    <div id="compressionStatus" class="small text-info mt-1" style="display: none;"></div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-info">
                <div class="card-header bg-info text-white py-3">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Information</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Logo Guidelines:</strong></p>
                    <ul class="small text-muted mb-0">
                        <li>Use a high-quality image</li>
                        <li>Square images work best</li>
                        <li>Transparent PNG recommended</li>
                        <li>Minimum 200x200 pixels</li>
                        <li>Maximum file size: 10MB</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('site_logo_input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const statusEl = document.getElementById('compressionStatus');
    statusEl.style.display = 'block';
    statusEl.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Processing image...';

    // Compress and resize image
    compressImage(file, 800, 0.85).then(compressedFile => {
        const originalSize = (file.size / 1024 / 1024).toFixed(2);
        const newSize = (compressedFile.size / 1024 / 1024).toFixed(2);

        statusEl.innerHTML = `<i class="fas fa-check-circle text-success me-1"></i> Compressed: ${originalSize}MB → ${newSize}MB`;

        // Update preview
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('logoPreview').src = e.target.result;
        };
        reader.readAsDataURL(compressedFile);

        // Create a new FileList-like object for the hidden input
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(compressedFile);
        document.getElementById('site_logo').files = dataTransfer.files;
    }).catch(err => {
        statusEl.innerHTML = '<i class="fas fa-exclamation-circle text-danger me-1"></i> Error processing image. Using original.';
        // Fallback to original file
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        document.getElementById('site_logo').files = dataTransfer.files;

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('logoPreview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    });
});

function compressImage(file, maxWidth, quality) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function(event) {
            const img = new Image();
            img.src = event.target.result;
            img.onload = function() {
                const canvas = document.createElement('canvas');
                let width = img.width;
                let height = img.height;

                // Calculate new dimensions
                if (width > maxWidth) {
                    height = Math.round((height * maxWidth) / width);
                    width = maxWidth;
                }

                canvas.width = width;
                canvas.height = height;

                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);

                canvas.toBlob(function(blob) {
                    if (blob) {
                        const compressedFile = new File([blob], file.name, {
                            type: 'image/jpeg',
                            lastModified: Date.now()
                        });
                        resolve(compressedFile);
                    } else {
                        reject(new Error('Canvas to Blob failed'));
                    }
                }, 'image/jpeg', quality);
            };
            img.onerror = reject;
        };
        reader.onerror = reject;
    });
}
</script>
@endsection
