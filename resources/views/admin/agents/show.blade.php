@extends('admin.layouts.app')

@section('title', 'Agent Details')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Agent Details</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.agents.index') }}">Agents</a></li>
        <li class="breadcrumb-item active">{{ $agent->name }}</li>
    </ol>
    
    <div class="row">
        <!-- Agent Profile -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-id-card me-1"></i>
                    Agent Profile
                </div>
                <div class="card-body text-center">
                    <div class="avatar-xl mb-3">
                        <div class="avatar-title bg-light text-primary rounded-circle display-4">
                            {{ strtoupper(substr($agent->name, 0, 1)) }}
                        </div>
                    </div>
                    <h4>{{ $agent->name }}</h4>
                    <p class="text-muted">
                        <i class="fas fa-user-tie me-1"></i>Agent
                        @if($agent->referred_by)
                            <br><small>Referred by: {{ $agent->referred_by }}</small>
                        @endif
                    </p>
                    
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary copy-btn" data-text="{{ $agent->referral_code }}">
                            <i class="fas fa-copy me-1"></i>Copy Referral Code: <code>{{ $agent->referral_code }}</code>
                        </button>
                    </div>
                </div>
                <!-- <div class="card-footer">
                    <div class="row text-center">
                        <div class="col-6">
                            <h6 class="mb-0">{{ $agent->referrals_count ?? 0 }}</h6>
                            <small class="text-muted">Referrals</small>
                        </div>
                        <div class="col-6">
                            <h6 class="mb-0">{{ $agent->commissions_count ?? 0 }}</h6>
                            <small class="text-muted">Commissions</small>
                        </div>
                    </div>
                </div> -->
            </div>
            
            <!-- Contact Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-address-book me-1"></i>
                    Contact Information
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><i class="fas fa-phone me-2"></i>Mobile</td>
                            <td>{{ $agent->mobile }}</td>
                        </tr>
                        @if($agent->email)
                        <tr>
                            <td><i class="fas fa-envelope me-2"></i>Email</td>
                            <td>{{ $agent->email }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td><i class="fas fa-calendar me-2"></i>Joined</td>
                            <td>{{ $agent->created_at->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-check-circle me-2"></i>Status</td>
                            <td>
                                @if($agent->mobile_verified_at)
                                <span class="badge bg-success">Verified</span>
                                @else
                                <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Bank Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-university me-1"></i>
                    Payment Information
                </div>
                <div class="card-body">
                    @if($agent->bank_details)
                    <h6>Bank Details</h6>
                    <table class="table table-sm">
                        <tr>
                            <td>Account Holder</td>
                            <td>{{ $agent->bank_details['account_holder_name'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Account Number</td>
                            <td>{{ $agent->bank_details['account_number'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>IFSC Code</td>
                            <td>{{ $agent->bank_details['ifsc_code'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Bank Name</td>
                            <td>{{ $agent->bank_details['bank_name'] ?? 'N/A' }}</td>
                        </tr>
                    </table>
                    @endif
                    
                    @if($agent->upi_id)
                    <h6 class="mt-3">UPI ID</h6>
                    <p class="mb-0"><code>{{ $agent->upi_id }}</code></p>
                    @endif
                    
                    @if(!$agent->bank_details && !$agent->upi_id)
                    <p class="text-muted mb-0">No payment information added</p>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Earnings & Activity -->
        <div class="col-lg-8">
            <!-- Earnings Summary -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-line me-1"></i>
                    Earnings Summary
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5>₹{{ number_format($stats['total_earnings'], 2) }}</h5>
                                    <small>Total Earnings</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5>₹{{ number_format($stats['settled_amount'], 2) }}</h5>
                                    <small>Settled</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5>₹{{ number_format($stats['pending_amount'], 2) }}</h5>
                                    <small>Pending</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5>{{ count($recentCommissions) }}</h5>
                                    <small>Total Referrals</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Commissions -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-money-bill-wave me-1"></i>
                           Total Referral
                        </div>
                        <a href="{{ route('admin.commissions.index', ['agent_id' => $agent->id]) }}" 
                           class="btn btn-sm btn-outline-primary">
                            View All
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>User Name</th>
                                    <th>Product</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentCommissions as $commission)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $commission->order) }}">
                                            {{ $commission->order->order_number }}
                                        </a>
                                    </td>
                                    <td>{{ $commission->order->customer_name }}</td>
                                    <td>{{ Str::limit($commission->order->product->name, 20) }}</td>
                                    <td>₹{{ number_format($commission->amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $commission->status === 'settled' ? 'success' : 'warning' }}">
                                            {{ ucfirst($commission->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $commission->created_at->format('d M Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No commissions yet</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Recent Settlements -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-hand-holding-usd me-1"></i>
                            Recent Settlements
                        </div>
                        <a href="{{ route('admin.settlements.create', ['agent_id' => $agent->id]) }}" 
                           class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i>New Settlement
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentSettlements as $settlement)
                                <tr>
                                    <td>#{{ $settlement->id }}</td>
                                    <td>₹{{ number_format($settlement->amount, 2) }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $settlement->payment_method)) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $settlement->status === 'completed' ? 'success' : 
                                                              ($settlement->status === 'processing' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($settlement->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $settlement->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.settlements.show', $settlement) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No settlements yet</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Copy referral code
    $('.copy-btn').click(function() {
        const text = $(this).data('text');
        navigator.clipboard.writeText(text).then(() => {
            const original = $(this).html();
            $(this).html('<i class="fas fa-check me-1"></i>Copied!');
            setTimeout(() => {
                $(this).html(original);
            }, 2000);
        });
    });
});
</script>
@endpush
@endsection