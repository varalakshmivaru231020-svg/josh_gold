@extends('admin.layouts.app')

@section('title', 'Edit Office')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Edit Office</h1>
    <a href="{{ route('admin.site-content.settings', ['tab' => 'offices']) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Site Settings
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.site-content.offices.update', $office->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4">
                {{-- Left column: main fields --}}
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="title" class="form-label fw-semibold">Office Title <span class="text-muted fw-normal">(optional)</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $office->title) }}">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Add a title, an address, or both.</div>
                    </div>

                    <div class="mb-3">
                        <label for="subtitle" class="form-label fw-semibold">Subtitle <span class="text-muted fw-normal">(optional)</span></label>
                        <input type="text" class="form-control" id="subtitle" name="subtitle" value="{{ old('subtitle', $office->subtitle) }}" placeholder="e.g. Head Office">
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label fw-semibold">Full Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $office->address) }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label fw-semibold">Office Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $office->phone) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="mobile" class="form-label fw-semibold">Mobile Number</label>
                            <input type="text" class="form-control" id="mobile" name="mobile" value="{{ old('mobile', $office->mobile) }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-semibold">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $office->email) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="website" class="form-label fw-semibold">Website</label>
                            <input type="text" class="form-control" id="website" name="website" value="{{ old('website', $office->website) }}" placeholder="www.example.com">
                        </div>
                    </div>
                </div>

                {{-- Right column: photo + settings --}}
                <div class="col-md-4">
                    {{-- Current image --}}
                    @if($office->image)
                    <div class="mb-2" id="currentOfficeImageWrap">
                        <label class="form-label fw-semibold">Current Photo</label>
                        <img src="{{ versioned_asset('storage/' . $office->image) }}" class="img-fluid rounded" style="max-height:160px; object-fit:cover; width:100%;">
                    </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-semibold">{{ $office->image ? 'Replace Photo' : 'Office Photo' }} <span class="text-muted">(optional)</span></label>
                        <input type="file" name="image" class="form-control" accept="image/*" id="officeImageInput">
                        <div id="officePreview" class="mt-2 d-none">
                            <img id="officePrevImg" src="#" class="img-fluid rounded" style="max-height:160px; object-fit:cover; width:100%;">
                        </div>
                        <button type="button" id="clearOfficeImage" class="btn btn-sm btn-outline-danger mt-2 d-none">Remove selected image</button>
                        <small class="text-muted">Shown in photo grid. Max 8MB. Leave blank to keep current.</small>
                    </div>

                    @if($office->image)
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image" value="1">
                            <label class="form-check-label" for="remove_image">Remove current photo</label>
                        </div>
                        <small class="text-muted">If selected, this office will be shown without image.</small>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label for="color" class="form-label fw-semibold">Card Accent Color</label>
                        <div class="input-group">
                            <input type="color" class="form-control form-control-color" id="colorPicker" value="{{ old('color', $office->color ?? '#1a2e5e') }}" style="width:50px;">
                            <input type="text" class="form-control" id="color" name="color" value="{{ old('color', $office->color ?? '#1a2e5e') }}" placeholder="#1a2e5e">
                        </div>
                        <small class="text-muted">Accent colour for the office card, e.g. #1a2e5e (navy)</small>
                    </div>

                    <div class="mb-3">
                        <label for="icon" class="form-label fw-semibold">Icon (FA class)</label>
                        <input type="text" class="form-control" id="icon" name="icon" value="{{ old('icon', $office->icon ?? 'fas fa-university') }}" placeholder="fas fa-university">
                    </div>

                    <div class="mb-3">
                        <label for="sort_order" class="form-label fw-semibold">Sort Order</label>
                        <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', $office->sort_order) }}">
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $office->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active (Show on Homepage)</label>
                    </div>
                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.site-content.settings', ['tab' => 'offices']) }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Office
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('colorPicker').addEventListener('input', function() {
    document.getElementById('color').value = this.value;
});
document.getElementById('color').addEventListener('input', function() {
    document.getElementById('colorPicker').value = this.value;
});
document.getElementById('officeImageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const removeImage = document.getElementById('remove_image');
        if (removeImage) {
            removeImage.checked = false;
        }
        const reader = new FileReader();
        reader.onload = function(ev) {
            document.getElementById('officePrevImg').src = ev.target.result;
            document.getElementById('officePreview').classList.remove('d-none');
            document.getElementById('clearOfficeImage').classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById('officePreview').classList.add('d-none');
        document.getElementById('clearOfficeImage').classList.add('d-none');
    }
});

document.getElementById('clearOfficeImage').addEventListener('click', function() {
    const input = document.getElementById('officeImageInput');
    input.value = '';
    document.getElementById('officePreview').classList.add('d-none');
    this.classList.add('d-none');
});

const removeImageCheckbox = document.getElementById('remove_image');
if (removeImageCheckbox) {
    removeImageCheckbox.addEventListener('change', function() {
        if (this.checked) {
            const input = document.getElementById('officeImageInput');
            input.value = '';
            document.getElementById('officePreview').classList.add('d-none');
            const currentImageWrap = document.getElementById('currentOfficeImageWrap');
            if (currentImageWrap) {
                currentImageWrap.classList.add('opacity-50');
            }
        } else {
            const currentImageWrap = document.getElementById('currentOfficeImageWrap');
            if (currentImageWrap) {
                currentImageWrap.classList.remove('opacity-50');
            }
        }
    });
}
</script>
@endpush
