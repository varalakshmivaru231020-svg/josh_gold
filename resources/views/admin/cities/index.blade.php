@extends('admin.layouts.app')

@section('title', 'Cities')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h1 class="h3 mb-1">Cities</h1>
        <p class="text-muted mb-0">Each active city gets a gold rate page and is listed on <a href="{{ route('rates.cities') }}" target="_blank" rel="noopener">Rates by city</a> and in the mobile menu. Cities marked <em>Menu</em> also appear in the header.</p>
    </div>
    <a href="{{ route('admin.cities.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add City
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>City</th>
                        <th>State</th>
                        <th>Page</th>
                        <th width="90">Header</th>
                        <th width="80">Order</th>
                        <th width="100">Status</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cities as $city)
                    @php $cityPath = '/gold-rate/' . $city->state_slug . '/' . $city->slug; @endphp
                    <tr>
                        <td><strong>{{ $city->name }}</strong></td>
                        <td>{{ $city->state }}</td>
                        <td>
                            @if($city->is_active)
                            <a href="{{ url($cityPath) }}" target="_blank" rel="noopener"><code>{{ $cityPath }}</code></a>
                            @else
                            <code class="text-muted">{{ $cityPath }}</code>
                            @endif
                        </td>
                        <td>
                            @if($city->show_in_menu)
                            <span class="badge bg-primary">Menu</span>
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $city->sort_order }}</td>
                        <td>
                            @if($city->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.cities.edit', $city) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.cities.destroy', $city) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this city? Its rate page will stop working.');">
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
                        <td colspan="7" class="text-center text-muted py-4">No cities yet. Add your first city!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
