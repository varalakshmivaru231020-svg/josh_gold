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
            <form action="{{ route('admin.pages.update', $page->id) }}" method="POST">
                @csrf
                @method('PUT')

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

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="is_published" 
                           {{ $page->is_published ? 'checked' : '' }}>
                    <label class="form-check-label">Publish Page</label>
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
</script>
@endpush
@endsection
