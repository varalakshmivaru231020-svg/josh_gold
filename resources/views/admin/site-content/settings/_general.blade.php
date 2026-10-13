{{-- Site Settings → General tab (site logo) --}}
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
                        <p class="text-muted small mb-2">Shown in the website header, on a black background. Use a <strong>transparent PNG or WebP</strong>: a JPG always has a white box around it.</p>

                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="logo-preview-box" style="width: 160px; height: 120px; border: 2px dashed #c9a35c; border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #050505; padding: 8px;" title="Preview on the website header colour">
                                    @if($logo)
                                        <img src="{{ asset('storage/' . $logo) }}" alt="Current Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;" id="logoPreview">
                                    @else
                                        <img src="{{ asset('images/brand/josha-logo.png') }}" alt="Default Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;" id="logoPreview">
                                    @endif
                                </div>
                            </div>
                            <div class="col">
                                <input type="file" class="form-control @error('site_logo') is-invalid @enderror" id="site_logo_input" accept="image/png,image/webp">
                                <input type="file" name="site_logo" id="site_logo" style="display: none;">
                                @error('site_logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">PNG or WebP with a transparent background. Large images are resized automatically (transparency is kept). Max 10MB.</div>
                                @if($logo)
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="remove_logo" value="1" id="remove_logo">
                                    <label class="form-check-label small" for="remove_logo">Remove the uploaded logo and use the default Josha logo</label>
                                </div>
                                @endif
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
                    <li>Transparent PNG or WebP only (no white background)</li>
                    <li>Shown about 68 px tall in the header, on black</li>
                    <li>At least 400 px wide for sharp results</li>
                    <li>Remove the uploaded logo to go back to the default Josha logo</li>
                    <li>Maximum file size: 10MB</li>
                </ul>
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

                // Keep transparency: WebP when the browser supports it, otherwise PNG (never JPEG)
                const toFile = (blob, type, ext) => new File([blob], file.name.replace(/\.[^.]+$/, '') + ext, {
                    type: type,
                    lastModified: Date.now()
                });
                canvas.toBlob(function(blob) {
                    if (blob && blob.type === 'image/webp') {
                        resolve(toFile(blob, 'image/webp', '.webp'));
                        return;
                    }
                    canvas.toBlob(function(png) {
                        png ? resolve(toFile(png, 'image/png', '.png')) : reject(new Error('Canvas to Blob failed'));
                    }, 'image/png');
                }, 'image/webp', quality);
            };
            img.onerror = reject;
        };
        reader.onerror = reject;
    });
}
</script>
