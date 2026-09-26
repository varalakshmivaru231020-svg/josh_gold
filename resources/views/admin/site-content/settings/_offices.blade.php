{{-- Site Settings → Offices tab (shown in the footer and on the Offices page) --}}
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <p class="text-muted mb-0">Offices listed in the website footer.</p>
    <a href="{{ route('admin.site-content.offices.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Office
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="60">Icon</th>
                        <th>Office Title</th>
                        <th>Subtitle</th>
                        <th>Address</th>
                        <th width="80">Order</th>
                        <th width="100">Status</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($offices as $office)
                    <tr>
                        <td class="fs-4">{{ $office->icon }}</td>
                        <td>{{ $office->title }}</td>
                        <td>{{ $office->subtitle }}</td>
                        <td>{{ Str::limit($office->address, 50) }}</td>
                        <td>{{ $office->sort_order }}</td>
                        <td>
                            @if($office->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.site-content.offices.edit', $office->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.site-content.offices.delete', $office->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this office?');">
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
                        <td colspan="7" class="text-center text-muted py-4">No offices found. Add your first office!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
