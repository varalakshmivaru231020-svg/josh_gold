@extends('admin.layouts.app')

@section('title', 'Edit Stat')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.site-content.stats') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to List
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h1 class="h4 mb-0">Edit Stat: {{ $stat->title }}</h1>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.site-content.stats.update', $stat->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $stat->title) }}" placeholder="e.g. Years of Trust" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Count (Number Only)</label>
                    <input type="number" name="count" class="form-control @error('count') is-invalid @enderror" value="{{ old('count', $stat->count) }}" placeholder="e.g. 25" required>
                    @error('count')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Suffix</label>
                    <input type="text" name="suffix" class="form-control @error('suffix') is-invalid @enderror" value="{{ old('suffix', $stat->suffix) }}" placeholder="e.g. +">
                    @error('suffix')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Icon (FontAwesome Class)</label>
                    <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror" value="{{ old('icon', $stat->icon) }}" placeholder="e.g. fas fa-balance-scale">
                    <small class="text-muted">Example: fas fa-balance-scale, fas fa-users, etc.</small>
                    @error('icon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $stat->sort_order) }}">
                    @error('sort_order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" {{ $stat->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Active</label>
                    </div>
                </div>
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary lg">
                        <i class="fas fa-save me-2"></i>Update Stat
                    </button>
                    <button type="button" class="btn btn-outline-danger ms-2" onclick="if(confirm('Are you sure you want to delete this stat?')) { event.preventDefault(); document.getElementById('delete-form').submit(); }">
                        <i class="fas fa-trash me-2"></i>Delete
                    </button>
                </div>
            </div>
        </form>
        <form id="delete-form" action="{{ route('admin.site-content.stats.delete', $stat->id) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>
@endsection
