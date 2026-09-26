@extends('admin.layouts.app')

@section('title', 'Add Page')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Add Page</h1>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">Pages</a></li>
        <li class="breadcrumb-item active">Add Page</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus me-1"></i>
            Add New Page
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pages.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Page Title *</label>
                    <input type="text" 
                           name="title" 
                           class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title') }}"
                           required>
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Content *</label>
                    <textarea id="content" name="content" 
                              class="form-control @error('content') is-invalid @enderror"
                              rows="8" required>{{ old('content') }}</textarea>
                    @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="is_published" checked>
                    <label class="form-check-label">Publish Page</label>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="show_in_footer" id="show_in_footer" value="1" {{ old('show_in_footer', '1') ? 'checked' : '' }}>
                    <label class="form-check-label" for="show_in_footer">Show a link in the website footer</label>
                    <div class="form-text">Handy for pages like Terms &amp; Conditions or Privacy Policy. Only published pages appear.</div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Page</button>
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
