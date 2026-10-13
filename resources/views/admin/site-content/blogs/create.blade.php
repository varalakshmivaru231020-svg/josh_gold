@extends('admin.layouts.app')

@section('title', 'Add Update')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h1 class="h3 mb-1">Add Update</h1>
        <p class="text-muted mb-0">A news post with a cover image, shown on the website's Updates page.</p>
    </div>
    <a href="{{ route('admin.site-content.blogs') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Updates
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.site-content.blogs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            @include('admin.site-content.blogs._form', ['blog' => new \App\Models\Blog()])

            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.site-content.blogs') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Publish Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
