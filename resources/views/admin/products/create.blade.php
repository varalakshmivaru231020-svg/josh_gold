@extends('admin.layouts.app')

@section('title', 'Add Publication / Product')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Add Publication / Product</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
        <li class="breadcrumb-item active">Add Product</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus me-1"></i>
            Add New Publication / Product
        </div>
        <div class="card-body">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="name" class="form-label">Book / Product Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required
                                   placeholder="e.g. Thanks Appa, Swayam Krushi">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Publication Metadata Row --}}
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="year" class="form-label">Year / Label</label>
                                <input type="text" class="form-control @error('year') is-invalid @enderror" 
                                       id="year" name="year" value="{{ old('year') }}"
                                       placeholder="e.g. 2008, 2026, Legal, 12 Volumes">
                                @error('year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Shown as a badge on the book card</small>
                            </div>
                            <div class="col-md-4">
                                <label for="category" class="form-label">Category / Genre</label>
                                <input type="text" class="form-control @error('category') is-invalid @enderror" 
                                       id="category" name="category" value="{{ old('category') }}"
                                       placeholder="e.g. Kannada Literature, Legal Publication">
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="book_icon" class="form-label">Book Icon / Emoji</label>
                                <input type="text" class="form-control @error('book_icon') is-invalid @enderror" 
                                       id="book_icon" name="book_icon" value="{{ old('book_icon') }}"
                                       placeholder="e.g. 🌾 💛 ⚖️ 📚">
                                @error('book_icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Emoji displayed on book card</small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="8" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="price" class="form-label">Price (₹) *</label>
                            <input type="number" step="0.01" min="0" 
                                   class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" value="{{ old('price') }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock *</label>
                            <input type="number" min="0" 
                                   class="form-control @error('stock') is-invalid @enderror" 
                                   id="stock" name="stock" value="{{ old('stock', 0) }}" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Cover Image (Primary Display Image) --}}
                        <div class="mb-3">
                            <label for="cover_image" class="form-label fw-bold">
                                📸 Cover Image <span class="text-muted fw-normal">(shown on home page)</span>
                            </label>
                            <input type="file" class="form-control @error('cover_image') is-invalid @enderror" 
                                   id="cover_image" name="cover_image" accept="image/*">
                            @error('cover_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="coverPreview" class="mt-2"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="images" class="form-label">Additional Gallery Images</label>
                            <input type="file" class="form-control @error('images') is-invalid @enderror" 
                                   id="images" name="images[]" multiple accept="image/*">
                            @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Upload multiple images (Max: 5, Max size: 2MB each)</small>
                            <div id="imagePreview" class="mt-2"></div>
                        </div>
                        
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">
                                Active / Published
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary me-md-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Publication</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
#imagePreview, #coverPreview {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.preview-image {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 4px;
    border: 2px solid #dee2e6;
}
.preview-cover {
    width: 140px;
    height: 180px;
    object-fit: cover;
    border-radius: 6px;
    border: 2px solid #c9a84c;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Summernote
    $('#description').summernote({
        height: 300,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    // Cover image preview
    $('#cover_image').on('change', function() {
        $('#coverPreview').empty();
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = $('<img>').attr('src', e.target.result).addClass('preview-cover');
                $('#coverPreview').append(img);
            }
            reader.readAsDataURL(file);
        }
    });
    
    // Additional image preview
    $('#images').on('change', function() {
        $('#imagePreview').empty();
        const files = this.files;
        for (let i = 0; i < files.length; i++) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = $('<img>').attr('src', e.target.result).addClass('preview-image');
                $('#imagePreview').append(img);
            }
            reader.readAsDataURL(files[i]);
        }
    });
});
</script>
@endpush
@endsection