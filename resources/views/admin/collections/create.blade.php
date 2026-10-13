@extends('admin.layouts.app')

@section('title', 'Add Collection')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Add Collection</h1>
    <a href="{{ route('admin.collections.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Collections
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.collections.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.collections._form')

            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.collections.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Save Collection
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
