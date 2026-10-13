@extends('admin.layouts.app')

@section('title', 'Edit Update')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h1 class="h3 mb-1">Edit Update</h1>
        <p class="text-muted mb-0">{{ $blog->title }}</p>
    </div>
    <div class="d-flex gap-2">
        @if($blog->is_active)
        <a href="{{ route('updates.show', $blog->slug) }}" target="_blank" rel="noopener" class="btn btn-outline-secondary">
            <i class="fas fa-up-right-from-square me-2"></i>View on site
        </a>
        @endif
        <a href="{{ route('admin.site-content.blogs') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Updates
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.site-content.blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('admin.site-content.blogs._form', ['blog' => $blog])

            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.site-content.blogs') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Save Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
