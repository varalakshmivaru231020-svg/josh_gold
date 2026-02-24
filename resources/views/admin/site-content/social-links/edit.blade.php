@extends('admin.layouts.app')

@section('title', 'Edit Connect Link')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Edit Connect Link</h1>
    <a href="{{ route('admin.site-content.social-links') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Links
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.site-content.social-links.update', $link->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="platform" class="form-label">Platform Name</label>
                        <input type="text" class="form-control @error('platform') is-invalid @enderror" id="platform" name="platform" value="{{ old('platform', $link->platform) }}" required>
                        @error('platform')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="url" class="form-label">URL (Full Link)</label>
                        <input type="url" class="form-control @error('url') is-invalid @enderror" id="url" name="url" value="{{ old('url', $link->url) }}" required>
                        @error('url')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="handle" class="form-label">Display Handle / Label <small class="text-muted">(shown on card, e.g. @username or page name)</small></label>
                        <input type="text" class="form-control @error('handle') is-invalid @enderror" id="handle" name="handle" value="{{ old('handle', $link->handle) }}" placeholder="e.g. @veerendrababunanjegowda">
                        @error('handle')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="icon" class="form-label">Icon (FontAwesome Class)</label>
                        <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" value="{{ old('icon', $link->icon) }}">
                        @error('icon')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="sort_order" class="form-label">Sort Order</label>
                        <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', $link->sort_order) }}">
                        @error('sort_order')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $link->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active (Show in Footer)
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.site-content.social-links') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Link
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
