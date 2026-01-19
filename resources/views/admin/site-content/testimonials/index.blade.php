@extends('admin.layouts.app')

@section('title', 'Manage Videos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Manage Videos</h1>
    <a href="{{ route('admin.site-content.testimonials.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Video
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="100">Thumbnail</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th width="80">Order</th>
                        <th width="100">Featured</th>
                        <th width="100">Status</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($videos as $video)
                    <tr>
                        <td>
                            @if($video->thumbnail)
                            <img src="{{ asset('storage/' . $video->thumbnail) }}" alt="{{ $video->title }}" class="rounded" style="width: 80px; height: 50px; object-fit: cover;">
                            @else
                            <img src="https://img.youtube.com/vi/{{ $video->youtube_id }}/mqdefault.jpg" alt="{{ $video->title }}" class="rounded" style="width: 80px; height: 50px; object-fit: cover;">
                            @endif
                        </td>
                        <td>
                            <strong>{{ $video->title }}</strong>
                            <br><small class="text-muted">ID: {{ $video->youtube_id }}</small>
                        </td>
                        <td>
                            {{ $video->author_name ?? 'N/A' }}
                            @if($video->author_designation)
                            <br><small class="text-muted">{{ $video->author_designation }}</small>
                            @endif
                        </td>
                        <td>{{ $video->sort_order }}</td>
                        <td>
                            @if($video->is_featured)
                            <span class="badge bg-warning text-dark">Featured</span>
                            @else
                            <span class="badge bg-light text-dark">No</span>
                            @endif
                        </td>
                        <td>
                            @if($video->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.site-content.testimonials.edit', $video->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ $video->youtube_url }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                <i class="fab fa-youtube"></i>
                            </a>
                            <form action="{{ route('admin.site-content.testimonials.delete', $video->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this video?');">
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
                        <td colspan="7" class="text-center text-muted py-4">No videos found. Add your first video!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($videos->hasPages())
        <div class="mt-3">
            {{ $videos->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
