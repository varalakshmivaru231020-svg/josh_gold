@extends('admin.layouts.app')

@section('title', 'Edit Footer Link')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Edit Footer Link</h1>
    <a href="{{ route('admin.footer.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Footer
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.footer.links.update', $link) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.footer._form')

            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.footer.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Link
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
