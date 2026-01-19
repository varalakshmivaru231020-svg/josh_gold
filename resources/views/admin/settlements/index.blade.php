@extends('admin.layouts.app')

@section('title', 'Settlements')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Settlements</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Settlements</li>
    </ol>
    
    <!-- Stats Card -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-normal">Total Settlements</h6>
                            <h4 class="mb-0">₹{{ number_format($settlements->sum('amount'), 2) }}</h4>
                        </div>
                        <i class="fas fa-hand-holding-usd fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-normal">Completed</h6>
                            <h4 class="mb-0">{{ $settlements->where('status', 'completed')->count() }}</h4>
                        </div>
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-normal">Processing</h6>
                            <h4 class="mb-0">{{ $settlements->where('status', 'processing')->count() }}</h4>
                        </div>
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-normal">Pending</h6>
                            <h4 class="mb-0">{{ $settlements->where('status', 'pending')->count() }}</h4>
                        </div>
                        <i class="fas fa-hourglass-half fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-filter me-1"></i>
            Filter Settlements
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.settlements.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Agent name...">
                    </div>
                    
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>Filter
                            </button>
                            <a href="{{ route('admin.settlements.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo me-1"></i>Reset
                            </a>
                            <a href="{{ route('admin.settlements.create') }}" class="btn btn-success ms-auto">
                                <i class="fas fa-plus me-1"></i>New Settlement
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Settlements Table -->
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-table me-1"></i>
                    Settlement List
                </div>
                <span class="badge bg-primary">Total: {{ $settlements->total() }}</span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="settlementsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Agent</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Notes</th>
                            <th>Processed By</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($settlements as $settlement)
                        <tr>
                            <td>#{{ $settlement->id }}</td>
                            <td>
                                <a href="{{ route('admin.agents.show', $settlement->agent) }}">
                                    {{ $settlement->agent->name }}
                                </a>
                                <br><small class="text-muted">{{ $settlement->agent->referral_code }}</small>
                            </td>
                            <td>
                                <strong>₹{{ number_format($settlement->amount, 2) }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ ucfirst(str_replace('_', ' ', $settlement->payment_method)) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $settlement->status === 'completed' ? 'success' : 
                                                      ($settlement->status === 'processing' ? 'warning' : 
                                                      ($settlement->status === 'failed' ? 'danger' : 'secondary')) }}">
                                    {{ ucfirst($settlement->status) }}
                                </span>
                            </td>
                            <td>{{ Str::limit($settlement->notes, 30) }}</td>
                            <td>
                                @if($settlement->processor)
                                {{ $settlement->processor->name }}
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $settlement->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.settlements.show', $settlement) }}" 
                                       class="btn btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-success" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#statusModal{{ $settlement->id }}"
                                            title="Update Status">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                                
                                <!-- Status Update Modal -->
                                <div class="modal fade" id="statusModal{{ $settlement->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.settlements.update-status', $settlement) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Update Settlement Status</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="status{{ $settlement->id }}" class="form-label">Status</label>
                                                        <select class="form-select" id="status{{ $settlement->id }}" name="status" required>
                                                            <option value="pending" {{ $settlement->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                            <option value="processing" {{ $settlement->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                                            <option value="completed" {{ $settlement->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                            <option value="failed" {{ $settlement->status == 'failed' ? 'selected' : '' }}>Failed</option>
                                                        </select>
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
                {{ $settlements->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#settlementsTable').DataTable({
        pageLength: 25,
        responsive: true,
        order: [[0, 'desc']]
    });
});
</script>
@endpush
@endsection