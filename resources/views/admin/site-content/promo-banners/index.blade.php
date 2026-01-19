@extends('admin.layouts.app')

@section('title', 'Manage Promo Banners')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Manage Promo Banners</h1>
    <a href="{{ route('admin.site-content.promo-banners.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Banner
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="100">Image</th>
                        <th>Title</th>
                        <th width="100">Position</th>
                        <th width="100">Status</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($banners as $banner)
                    <tr>
                        <td>
                            @if($banner->image)
                            <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" class="rounded" style="width: 80px; height: 50px; object-fit: cover;">
                            @else
                            <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $banner->title ?? 'Untitled' }}</strong>
                            @if($banner->description)
                            <br><small class="text-muted">{{ Str::limit($banner->description, 50) }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $banner->position == 'bottom' ? 'info' : 'warning' }}">
                                {{ ucfirst($banner->position) }}
                            </span>
                        </td>
                        <td>
                            @if($banner->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.site-content.promo-banners.edit', $banner->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.site-content.promo-banners.delete', $banner->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this banner?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No promo banners found. Add your first banner!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($banners->hasPages())
        <div class="mt-3">
            {{ $banners->links() }}
        </div>
        @endif
    </div>
</div>

<div class="alert alert-info mt-3">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Position Guide:</strong>
    <ul class="mb-0 mt-2">
        <li><strong>Bottom:</strong> Shows at the bottom of the homepage (after all sections)</li>
        <li><strong>Middle:</strong> Shows in the middle of the homepage (between sections)</li>
    </ul>
</div>
@endsection
