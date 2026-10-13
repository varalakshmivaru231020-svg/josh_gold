@extends('admin.layouts.app')

@section('title', 'Updates')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h1 class="h3 mb-1">Updates</h1>
        <p class="text-muted mb-0">News posts with a cover image, shown on the website's <a href="{{ route('updates') }}" target="_blank" rel="noopener">Updates page</a>.</p>
    </div>
    <a href="{{ route('admin.site-content.blogs.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Update
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th width="96">Cover</th>
                        <th>Title</th>
                        <th>Summary</th>
                        <th width="100">Status</th>
                        <th width="120">Posted</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($blogs as $blog)
                    <tr>
                        <td>
                            @if($blog->image)
                            <img src="{{ asset('storage/' . $blog->image) }}" alt="" class="rounded" style="width: 72px; height: 45px; object-fit: cover;">
                            @else
                            <span class="badge bg-warning text-dark" title="Add a cover image so this update looks its best">No cover</span>
                            @endif
                        </td>
                        <td><strong>{{ $blog->title }}</strong></td>
                        <td class="text-muted">{{ Str::limit($blog->excerpt, 70) }}</td>
                        <td>
                            @if($blog->is_active)
                            <span class="badge bg-success">Live</span>
                            @else
                            <span class="badge bg-secondary">Hidden</span>
                            @endif
                        </td>
                        <td>{{ $blog->created_at->format('d M Y') }}</td>
                        <td class="text-nowrap">
                            @if($blog->is_active)
                            <a href="{{ route('updates.show', $blog->slug) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-info" title="View on site">
                                <i class="fas fa-eye"></i>
                            </a>
                            @endif
                            <a href="{{ route('admin.site-content.blogs.edit', $blog->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.site-content.blogs.delete', $blog->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this update? It will be removed from the website.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No updates yet. Add your first update!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($blogs->hasPages())
        <div class="mt-3">
            {{ $blogs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
