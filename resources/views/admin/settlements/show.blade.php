@extends('admin.layouts.app')

@section('title', 'Settlement Details')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Settlement Details</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.settlements.index') }}">Settlements</a></li>
        <li class="breadcrumb-item active">#{{ $settlement->id }}</li>
    </ol>
    
    <div class="row">
        <!-- Settlement Details -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-file-invoice-dollar me-1"></i>
                            Settlement Information
                        </div>
                        <span class="badge bg-light text-dark">
                            {{ $settlement->created_at->format('d M Y, h:i A') }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Settlement Details</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td>Settlement ID:</td>
                                    <td><strong>#{{ $settlement->id }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Amount:</td>
                                    <td><strong class="text-success">₹{{ number_format($settlement->amount, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Payment Method:</td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ ucfirst(str_replace('_', ' ', $settlement->payment_method)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Status:</td>
                                    <td>
                                        <span class="badge bg-{{ $settlement->status === 'completed' ? 'success' : 
                                                              ($settlement->status === 'processing' ? 'warning' : 
                                                              ($settlement->status === 'failed' ? 'danger' : 'secondary')) }}">
                                            {{ ucfirst($settlement->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @if($settlement->transaction_details)
                                <tr>
                                    <td>Transaction ID:</td>
                                    <td><code>{{ $settlement->transaction_details['transaction_id'] ?? 'N/A' }}</code></td>
                                </tr>
                                <tr>
                                    <td>Transaction Date:</td>
                                    <td>{{ $settlement->transaction_details['transaction_date'] ?? 'N/A' }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h6>Agent Information</h6>
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm me-3">
                                    <div class="avatar-title bg-light text-primary rounded-circle">
                                        {{ strtoupper(substr($settlement->agent->name, 0, 1)) }}
                                    </div>
                                </div>
                                <div>
                                    <strong>{{ $settlement->agent->name }}</strong><br>
                                    <small class="text-muted">{{ $settlement->agent->referral_code }}</small>
                                </div>
                            </div>
                            <table class="table table-sm">
                                <tr>
                                    <td>Mobile:</td>
                                    <td>{{ $settlement->agent->mobile }}</td>
                                </tr>
                                @if($settlement->agent->email)
                                <tr>
                                    <td>Email:</td>
                                    <td>{{ $settlement->agent->email }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td>Total Earnings:</td>
                                    <td>₹{{ number_format($settlement->agent->total_earnings, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Pending Amount:</td>
                                    <td>₹{{ number_format($settlement->agent->pending_amount, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6>Notes</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    {{ $settlement->notes }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Processing Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-cogs me-1"></i>
                    Processing Information
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Processed By</h6>
                            @if($settlement->processor)
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-3">
                                    <div class="avatar-title bg-light text-dark rounded-circle">
                                        {{ strtoupper(substr($settlement->processor->name, 0, 1)) }}
                                    </div>
                                </div>
                                <div>
                                    <strong>{{ $settlement->processor->name }}</strong><br>
                                    <small class="text-muted">{{ $settlement->processor->email }}</small>
                                </div>
                            </div>
                            @else
                            <p class="text-muted mb-0">Not processed yet</p>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <h6>Timestamps</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td>Created:</td>
                                    <td>{{ $settlement->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                                @if($settlement->processed_at)
                                <tr>
                                    <td>Processed:</td>
                                    <td>{{ $settlement->processed_at->format('d M Y, h:i A') }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td>Last Updated:</td>
                                    <td>{{ $settlement->updated_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Actions & Status -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-edit me-1"></i>
                    Update Status
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settlements.update-status', $settlement) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="pending" {{ $settlement->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $settlement->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ $settlement->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="failed" {{ $settlement->status == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-1"></i>Update Status
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-bolt me-1"></i>
                    Quick Actions
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.agents.show', $settlement->agent) }}" 
                           class="btn btn-outline-primary">
                            <i class="fas fa-user-tie me-1"></i>View Agent
                        </a>
                        <a href="{{ route('admin.commissions.index', ['agent_id' => $settlement->agent_id]) }}" 
                           class="btn btn-outline-success">
                            <i class="fas fa-money-bill-wave me-1"></i>View Commissions
                        </a>
                        <a href="{{ route('admin.settlements.create', ['agent_id' => $settlement->agent_id]) }}" 
                           class="btn btn-outline-warning">
                            <i class="fas fa-plus me-1"></i>New Settlement
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Settlement Summary -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    Settlement Summary
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="display-6 text-success">₹{{ number_format($settlement->amount, 2) }}</div>
                        <small class="text-muted">Settlement Amount</small>
                    </div>
                    
                    <div class="progress mb-3" style="height: 20px;">
                        @php
                            $agent = $settlement->agent;
                            $percentage = $agent->total_earnings > 0 ? ($settlement->amount / $agent->total_earnings) * 100 : 0;
                        @endphp
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: {{ $percentage }}%">
                            {{ number_format($percentage, 1) }}%
                        </div>
                    </div>
                    
                    <table class="table table-sm">
                        <tr>
                            <td>Agent Total Earnings:</td>
                            <td class="text-end">₹{{ number_format($agent->total_earnings, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Agent Settled Amount:</td>
                            <td class="text-end">₹{{ number_format($agent->settled_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Agent Pending Amount:</td>
                            <td class="text-end">₹{{ number_format($agent->pending_amount, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-update status message
    $('#status').change(function() {
        const status = $(this).val();
        let message = '';
        
        switch(status) {
            case 'completed':
                message = 'Marking as completed will update agent balances.';
                break;
            case 'failed':
                message = 'Marking as failed will require manual review.';
                break;
            case 'processing':
                message = 'Marking as processing indicates settlement is in progress.';
                break;
        }
        
        if (message) {
            alert(message);
        }
    });
});
</script>
@endpush
@endsection