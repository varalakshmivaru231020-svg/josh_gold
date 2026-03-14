@extends('admin.layouts.app')

@section('title', 'Commissions')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Commissions</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Commissions</li>
    </ol>
    
    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-filter me-1"></i>
            Filter Commissions
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.commissions.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Agent, Order #...">
                    </div>
                    
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="settled" {{ request('status') == 'settled' ? 'selected' : '' }}>Settled</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="agent_id" class="form-label">Agent</label>
                        <select class="form-select" id="agent_id" name="agent_id">
                            <option value="">All Agents</option>
                            @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" {{ request('agent_id') == $agent->id ? 'selected' : '' }}>
                                {{ $agent->name }} ({{ $agent->referral_code }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-12">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>Filter
                            </button>
                            <a href="{{ route('admin.commissions.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo me-1"></i>Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Commissions Table -->
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-table me-1"></i>
                    Commission List
                </div>
                @php
                    $totalPending = $commissions->where('status', 'pending')->sum('amount');
                    $totalSettled = $commissions->where('status', 'settled')->sum('amount');
                @endphp
                <div>
                    <span class="badge bg-warning me-2">Pending: ₹{{ number_format($totalPending, 2) }}</span>
                    <span class="badge bg-success">Settled: ₹{{ number_format($totalSettled, 2) }}</span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="commissionsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Agent</th>
                            <th>Order</th>
                            <th>Product</th>
                            <th>Commission</th>
                            <!-- <th>Percentage</th> -->
                            <th>Status</th>
                            <th>Created</th>
                            <th>Settled</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($commissions as $commission)
                        <tr>
                            <td>{{ $commission->id }}</td>
                            <td>
                                <a href="{{ route('admin.agents.show', $commission->agent) }}">
                                    {{ $commission->agent->name }}
                                </a>
                                <br><small class="text-muted">{{ $commission->agent->referral_code }}</small>
                            </td>
                            <td>
                                @php
                                    $order = $commission->order;
                                @endphp

                                @if($order)
                                    <a href="{{ route('admin.orders.show', $order) }}">
                                        {{ $order->order_number }}
                                    </a>
                                    <br><small>₹{{ number_format($order->total, 2) }}</small>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $productName = isset($order) && $order && $order->product ? $order->product->name : null;
                                @endphp

                                @if($productName)
                                    {{ Str::limit($productName, 25) }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <strong>₹{{ number_format($commission->amount, 2) }}</strong>
                            </td>
                            <!-- <td>{{ $commission->commission_percentage }}%</td> -->
                            <td>
                                <span class="badge bg-{{ $commission->status === 'settled' ? 'success' : 'warning' }}">
                                    {{ ucfirst($commission->status) }}
                                </span>
                            </td>
                            <td>{{ $commission->created_at->format('d M Y') }}</td>
                            <td>
                                @if($commission->settled_at)
                                {{ $commission->settled_at->format('d M Y') }}
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" 
                                        class="btn btn-sm btn-outline-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#statusModal{{ $commission->id }}"
                                        title="Update Status">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        
                        <!-- Status Update Modal -->
                        <div class="modal fade" id="statusModal{{ $commission->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.commissions.update-status', $commission) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Commission Status</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="status{{ $commission->id }}" class="form-label">Status</label>
                                                <select class="form-select" id="status{{ $commission->id }}" name="status" required>
                                                    <option value="pending" {{ $commission->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="settled" {{ $commission->status == 'settled' ? 'selected' : '' }}>Settled</option>
                                                </select>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="notes{{ $commission->id }}" class="form-label">Notes</label>
                                                <textarea class="form-control" id="notes{{ $commission->id }}" name="notes" 
                                                          rows="3" placeholder="Add any notes...">{{ $commission->notes }}</textarea>
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
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $commissions->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#commissionsTable').DataTable({
        pageLength: 25,
        responsive: true,
        order: [[0, 'desc']]
    });
    
    // Select2 for agent dropdown
    $('#agent_id').select2({
        placeholder: "Select an agent",
        allowClear: true
    });
});
</script>
@endpush
@endsection