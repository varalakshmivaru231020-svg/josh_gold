@extends('admin.layouts.app')

@section('title', 'Photo Gallery')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Photo Gallery</h1>
        <p class="text-muted small mb-0">Manage photos shown in "Moments Across Courtrooms, Culture & Community"</p>
    </div>
    <a href="{{ route('admin.site-content.gallery.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Upload Photo
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="card">
    <div class="card-body">
        @if($photos->count())
        <div class="row g-3">
            @foreach($photos as $photo)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card h-100 border shadow-sm">
                    <div style="height:200px; overflow:hidden; background:#f8f9fa;">
                        <img src="{{ versioned_asset('storage/' . $photo->image) }}" alt="{{ $photo->title }}" style="width:100%; height:100%; object-fit:cover;">
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                @if($photo->title)
                                    <p class="fw-bold mb-0 small">{{ $photo->title }}</p>
                                @endif
                                @if($photo->caption)
                                    <p class="text-muted mb-0 small">{{ Str::limit($photo->caption, 50) }}</p>
                                @endif
                                @if($photo->category)
                                    <span class="badge bg-light text-dark border mt-1">{{ $photo->category }}</span>
                                @endif
                            </div>
                            <span class="badge {{ $photo->is_active ? 'bg-success' : 'bg-secondary' }} ms-2">{{ $photo->is_active ? 'On' : 'Off' }}</span>
                        </div>
                        <p class="text-muted mb-2" style="font-size:0.75rem;">Sort: {{ $photo->sort_order }}</p>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.site-content.gallery.edit', $photo->id) }}" class="btn btn-sm btn-outline-primary flex-grow-1">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                            <form action="{{ route('admin.site-content.gallery.delete', $photo->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this photo?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $photos->links() }}</div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-images fa-3x text-muted mb-3"></i>
            <p class="text-muted">No photos uploaded yet. Click "Upload Photo" to add your first gallery image.</p>
            <a href="{{ route('admin.site-content.gallery.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Upload First Photo
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
