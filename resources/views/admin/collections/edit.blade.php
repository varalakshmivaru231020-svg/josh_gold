@extends('admin.layouts.app')

@section('title', 'Edit Collection')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h1 class="h3 mb-0">Edit Collection</h1>
    <div class="d-flex gap-2">
        @if($collection->is_active)
        <a href="{{ route('collections.show', $collection->slug) }}" class="btn btn-outline-primary" target="_blank" rel="noopener">
            <i class="fas fa-up-right-from-square me-2"></i>View page
        </a>
        @endif
        <a href="{{ route('admin.collections.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Collections
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.collections.update', $collection) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.collections._form')

            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.collections.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Collection
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
