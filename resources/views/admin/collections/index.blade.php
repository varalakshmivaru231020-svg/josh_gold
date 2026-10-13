@extends('admin.layouts.app')

@section('title', 'Collections')

@php use App\Models\Collection; @endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h1 class="h3 mb-1">Collections</h1>
        <p class="text-muted mb-0">Each active collection has its own page at <code>/collections/…</code>. Collections marked <em>Home</em> also appear as tiles under "Shop by category" on the <a href="{{ route('home') }}" target="_blank" rel="noopener">home page</a>.</p>
    </div>
    <a href="{{ route('admin.collections.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Collection
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th width="84">Image</th>
                        <th>Collection</th>
                        <th>Type</th>
                        <th>Page</th>
                        <th width="80">Home</th>
                        <th width="70">Order</th>
                        <th width="90">Status</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($collections as $collection)
                    @php $path = '/collections/' . $collection->slug; @endphp
                    <tr>
                        <td>
                            <img src="{{ $collection->image_url }}" alt="" width="72" height="54" class="rounded border" style="object-fit: cover;">
                        </td>
                        <td>
                            <strong>{{ $collection->name }}</strong>
                            @if($collection->tagline)<div class="small text-muted">{{ $collection->tagline }}</div>@endif
                        </td>
                        <td>
                            @if($collection->isBullion())
                                {{ Collection::METALS[$collection->metal] ?? $collection->metal }} {{ strtolower(Collection::FORMS[$collection->form] ?? (string) $collection->form) }}s
                                <div class="small text-muted">{{ count($collection->weight_list) }} weights · {{ implode(', ', $collection->purity_list) ?: 'no purity set' }}</div>
                            @else
                                {{ Collection::METALS[$collection->metal] ?? $collection->metal }} jewellery
                                <div class="small text-muted">{{ count($collection->gallery_urls) }} gallery photos</div>
                            @endif
                        </td>
                        <td>
                            @if($collection->is_active)
                            <a href="{{ url($path) }}" target="_blank" rel="noopener"><code>{{ $path }}</code></a>
                            @else
                            <code class="text-muted">{{ $path }}</code>
                            @endif
                        </td>
                        <td>
                            @if($collection->show_on_home)
                            <span class="badge bg-primary">Home</span>
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $collection->sort_order }}</td>
                        <td>
                            @if($collection->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.collections.edit', $collection) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.collections.destroy', $collection) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this collection? Its page will stop working and its home tile will disappear.');">
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
                        <td colspan="8" class="text-center text-muted py-4">No collections yet. Add your first collection!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
