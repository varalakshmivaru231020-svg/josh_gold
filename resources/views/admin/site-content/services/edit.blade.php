@extends('admin.layouts.app')

@section('title', 'Edit Service')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Edit Service</h1>
    <a href="{{ route('admin.site-content.services') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Services
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.site-content.services.update', $service->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.site-content.services._form')

            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.site-content.services') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Service
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
