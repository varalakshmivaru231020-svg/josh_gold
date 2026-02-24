@extends('admin.layouts.app')

@section('title', 'Manage Stats/Counters')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Manage Stats/Counters</h1>
    <a href="{{ route('admin.site-content.stats.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Stat
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="80">Icon</th>
                        <th>Title</th>
                        <th>Count</th>
                        <th>Suffix</th>
                        <th width="80">Order</th>
                        <th width="100">Status</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stats as $stat)
                    <tr>
                        <td><i class="{{ $stat->icon }}"></i></td>
                        <td>{{ $stat->title }}</td>
                        <td>{{ $stat->count }}</td>
                        <td>{{ $stat->suffix }}</td>
                        <td>{{ $stat->sort_order }}</td>
                        <td>
                            @if($stat->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.site-content.stats.edit', $stat->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.site-content.stats.delete', $stat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this stat?');">
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
                        <td colspan="7" class="text-center text-muted py-4">No stats found. Add your first counter!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
