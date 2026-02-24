@extends('admin.layouts.app')

@section('title', 'Manage Connect Content')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Manage Connect Content</h1>
    <a href="{{ route('admin.site-content.social-links.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Link
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="80">Icon</th>
                        <th>Platform</th>
                        <th>URL</th>
                        <th width="80">Order</th>
                        <th width="100">Status</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($links as $link)
                    <tr>
                        <td class="fs-4 text-primary"><i class="{{ $link->icon }}"></i></td>
                        <td><strong>{{ $link->platform }}</strong></td>
                        <td><a href="{{ $link->url }}" target="_blank">{{ $link->url }}</a></td>
                        <td>{{ $link->sort_order }}</td>
                        <td>
                            @if($link->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.site-content.social-links.edit', $link->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.site-content.social-links.delete', $link->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this link?');">
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
                        <td colspan="6" class="text-center text-muted py-4">No connect content links found. Add your first link!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
