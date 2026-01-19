@extends('admin.layouts.app')

@section('title', 'Edit Page')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Page</h1>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">Pages</a></li>
        <li class="breadcrumb-item active">Edit Page</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
            Edit Page
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pages.update', $page->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Page Title *</label>
                            <input type="text" name="title" class="form-control"
                                   value="{{ $page->title }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Content *</label>
                            <textarea id="content" name="content"
                                      class="form-control" rows="8" required>{!! $page->content !!}</textarea>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Banner Title</label>
                            <input type="text" name="banner_title" class="form-control"
                                   value="{{ $page->banner_title }}" placeholder="Leave empty to use page title">
                            <small class="text-muted">Displayed on the page banner</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Banner Image</label>
                            @if($page->banner_image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $page->banner_image) }}" alt="Banner" class="img-fluid rounded" style="max-height: 150px;">
                            </div>
                            @endif
                            <input type="file" name="banner_image" class="form-control" accept="image/*">
                            <small class="text-muted">Recommended: 1920x600px, Max 5MB</small>
                            <div id="bannerPreview" class="mt-2"></div>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="is_published"
                                   {{ $page->is_published ? 'checked' : '' }}>
                            <label class="form-check-label">Publish Page</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Page</button>
                </div>

            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $('#content').summernote({
        height: 300,
        toolbar: [
            ['style', ['bold','italic','underline','clear']],
            ['para', ['ul','ol','paragraph']],
            ['insert', ['link','picture','video']],
            ['view', ['fullscreen','codeview']]
        ]
    });

    // Banner image preview
    document.querySelector('input[name="banner_image"]').addEventListener('change', function(e) {
        const preview = document.getElementById('bannerPreview');
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
@endsection
