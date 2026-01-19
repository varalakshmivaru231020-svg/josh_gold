@extends('admin.layouts.app')

@section('title', 'Manage Agents')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Agents</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Agents</li>
    </ol>
    
    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-filter me-1"></i>
            Filter Agents
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.agents.index') }}">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Name, Mobile, Email, Referral Code...">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>Search
                            </button>
                            <a href="{{ route('admin.agents.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo me-1"></i>Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Agents Table -->
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-users me-1"></i>
                    Agent List
                </div>
                <div>
                    <span class="badge bg-primary me-2">Total: {{ $agents->total() }}</span>
                    <span class="badge bg-success">Active: {{ $agents->where('is_active', true)->count() }}</span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="agentsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Agent</th>
                            <th>Contact</th>
                            <th>Referral Code</th>
                            <th>Referrals</th>
                            <th>Earnings</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($agents as $agent)
                        <tr>
                            <td>{{ $agent->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        <div class="avatar-title bg-light text-primary rounded-circle">
                                            {{ strtoupper(substr($agent->name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <strong>{{ $agent->name }}</strong>
                                        @if($agent->referred_by)
                                            <br><small class="text-muted">Referred by: {{ $agent->referred_by }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <i class="fas fa-phone me-1"></i> {{ $agent->mobile }}<br>
                                @if($agent->email)
                                <i class="fas fa-envelope me-1"></i> {{ $agent->email }}
                                @endif
                            </td>
                            <td>
                                <code>{{ $agent->referral_code }}</code>
                                <button class="btn btn-sm btn-outline-secondary copy-btn" 
                                        data-text="{{ $agent->referral_code }}"
                                        title="Copy referral code">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $agent->referrals_count ?? 0 }}</span>
                            </td>
                            <td>
                                <div class="small">
                                    <span class="d-block">Total: ₹{{ number_format($agent->total_earnings, 2) }}</span>
                                    <span class="d-block text-success">Settled: ₹{{ number_format($agent->settled_amount, 2) }}</span>
                                    <span class="d-block text-warning">Pending: ₹{{ number_format($agent->pending_amount, 2) }}</span>
                                </div>
                            </td>
                            <td>
                                @if($agent->mobile_verified_at)
                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Verified</span>
                                @else
                                <span class="badge bg-warning"><i class="fas fa-clock me-1"></i>Pending</span>
                                @endif
                            </td>
                            <td>{{ $agent->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.agents.show', $agent) }}" 
                                       class="btn btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-success" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal{{ $agent->id }}"
                                            title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                                
                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal{{ $agent->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.agents.update', $agent) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Agent: {{ $agent->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="name{{ $agent->id }}" class="form-label">Name</label>
                                                        <input type="text" class="form-control" id="name{{ $agent->id }}" 
                                                               name="name" value="{{ $agent->name }}" required>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label for="email{{ $agent->id }}" class="form-label">Email</label>
                                                        <input type="email" class="form-control" id="email{{ $agent->id }}" 
                                                               name="email" value="{{ $agent->email }}">
                                                    </div>
                                                    
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="is_active{{ $agent->id }}" 
                                                               name="is_active" value="1" {{ $agent->is_active ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="is_active{{ $agent->id }}">
                                                            Active Agent
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $agents->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#agentsTable').DataTable({
        pageLength: 25,
        responsive: true,
        order: [[0, 'desc']]
    });
    
    // Copy referral code
    $('.copy-btn').click(function() {
        const text = $(this).data('text');
        navigator.clipboard.writeText(text).then(() => {
            $(this).html('<i class="fas fa-check"></i>');
            setTimeout(() => {
                $(this).html('<i class="fas fa-copy"></i>');
            }, 2000);
        });
    });
});
</script>
@endpush
@endsection