@extends('admin.layouts.app')

@section('title', 'Manage Services')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Manage Services</h1>
    <a href="{{ route('admin.site-content.services.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Service
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="110">Image</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th width="80">Order</th>
                        <th width="100">Status</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                    <tr>
                        <td>
                            @if($service->image)
                            <img src="{{ versioned_asset('storage/' . $service->image) }}" alt="" class="rounded border" style="width: 84px; height: 54px; object-fit: cover;">
                            @else
                            <span class="d-inline-flex align-items-center justify-content-center rounded border bg-light text-muted" style="width: 84px; height: 54px;"><i class="fas fa-image"></i></span>
                            @endif
                        </td>
                        <td>{{ $service->title }}</td>
                        <td>{{ Str::limit($service->description, 60) }}</td>
                        <td>{{ $service->sort_order }}</td>
                        <td>
                            @if($service->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.site-content.services.edit', $service->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.site-content.services.delete', $service->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this service?');">
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
                        <td colspan="6" class="text-center text-muted py-4">No services found. Add your first service!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($services->hasPages())
        <div class="mt-3">
            {{ $services->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
