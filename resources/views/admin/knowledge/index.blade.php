@extends('admin.layouts.app')

@section('title', 'Knowledge Articles')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h1 class="h3 mb-1">Knowledge Articles</h1>
        <p class="text-muted mb-0">Guides shown on the <a href="{{ route('knowledge') }}" target="_blank" rel="noopener">Knowledge page</a>, in this order.</p>
    </div>
    <a href="{{ route('admin.knowledge.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Article
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th width="120">Tag</th>
                        <th>Title</th>
                        <th>Intro</th>
                        <th width="80">Points</th>
                        <th width="80">Order</th>
                        <th width="100">Status</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                    <tr>
                        <td><span class="badge bg-warning text-dark">{{ $article->tag }}</span></td>
                        <td><strong>{{ $article->title }}</strong></td>
                        <td>{{ Str::limit($article->intro, 70) }}</td>
                        <td>{{ count($article->pointList()) }}</td>
                        <td>{{ $article->sort_order }}</td>
                        <td>
                            @if($article->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.knowledge.edit', $article) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.knowledge.destroy', $article) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this article?');">
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
                        <td colspan="7" class="text-center text-muted py-4">No articles yet. Add your first article!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($articles->hasPages())
        <div class="mt-3">
            {{ $articles->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
