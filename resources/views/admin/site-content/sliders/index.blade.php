@extends('admin.layouts.app')

@section('title', 'Manage Sliders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Manage Sliders</h1>
    <a href="{{ route('admin.site-content.sliders.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Slider
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="d-flex align-items-center gap-2 mb-3 flex-wrap">
            <label for="pageFilter" class="fw-semibold mb-0">Page:</label>
            <select id="pageFilter" name="show" class="form-select w-auto" onchange="this.form.submit()">
                <option value="">All pages</option>
                @foreach(\App\Models\Slider::PAGES as $key => $label)
                <option value="{{ $key }}" {{ ($filter ?? null) === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="80">Image</th>
                        <th width="130">Page</th>
                        <th>Title</th>
                        <th>Subtitle</th>
                        <th width="80">Order</th>
                        <th width="100">Status</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sliders as $slider)
                    <tr>
                        <td>
                            @if($slider->image)
                            <img src="{{ asset('storage/' . $slider->image) }}" alt="{{ $slider->title }}" class="rounded" style="width: 60px; height: 40px; object-fit: cover;">
                            @else
                            <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td><span class="badge bg-warning text-dark">{{ \App\Models\Slider::PAGES[$slider->page] ?? $slider->page }}</span></td>
                        <td>{{ $slider->title ?? 'N/A' }}</td>
                        <td>{{ Str::limit($slider->subtitle, 40) ?? 'N/A' }}</td>
                        <td>{{ $slider->sort_order }}</td>
                        <td>
                            @if($slider->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.site-content.sliders.edit', $slider->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.site-content.sliders.delete', $slider->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this slider?');">
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
                        <td colspan="7" class="text-center text-muted py-4">No sliders found. Add your first slider!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sliders->hasPages())
        <div class="mt-3">
            {{ $sliders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
