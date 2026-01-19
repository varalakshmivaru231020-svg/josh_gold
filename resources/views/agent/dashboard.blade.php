@extends('layouts.agent')

@section('title', 'Distributor Dashboard')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Distributor Dashboard</h1>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Overview</li>
    </ol>
    
    <!-- Stats Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-normal">Total Earnings</h6>
                            <h4 class="mb-0">₹{{ number_format($stats['total_earnings'], 2) }}</h4>
                        </div>
                        <i class="fas fa-rupee-sign fa-2x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span class="small">Lifetime Earnings</span>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-normal">Settled Amount</h6>
                            <h4 class="mb-0">₹{{ number_format($stats['settled_amount'], 2) }}</h4>
                        </div>
                        <i class="fas fa-hand-holding-usd fa-2x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span class="small">Paid to you</span>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-normal">Pending Amount</h6>
                            <h4 class="mb-0">₹{{ number_format($stats['pending_amount'], 2) }}</h4>
                        </div>
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span class="small">Awaiting settlement</span>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-normal">Total Referrals</h6>
                            <h4 class="mb-0">{{ $stats['pending_commissions'] + $stats['settled_commissions'] }}</h4>
                        </div>
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('agent.commissions') }}">
                        View Details
                    </a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Referral Code & Quick Actions -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <i class="fas fa-share-alt me-1"></i>
                    Your Distributor Referral Code
                </div>
                <div class="card-body">
                    <div class="text-center py-4">
                        <div class="display-3 mb-3 font-monospace text-primary">
                            {{ $agent->referral_code }}
                        </div>
                        <p class="text-muted mb-4">
                            Share this distributor code to earn commissions on every successful purchase
                        </p>
                        
                        <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                            <button class="btn btn-primary copy-btn" data-text="{{ $agent->referral_code }}">
                                <i class="fas fa-copy me-1"></i>Copy Code
                            </button>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            When someone uses your distributor code, they get ₹25 discount and you earn ₹50 commission!
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
                            My Distributor Referrals
                        </div>
                        <a href="{{ route('agent.commissions') }}" class="btn btn-sm btn-outline-primary">
                            View All
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer Name</th>
                                    <th>Product</th>
                                    <th>Commission</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentCommissions as $commission)
                                    <tr>
                                        <td>{{ $commission->order->order_number }}</td>
                                        <td>{{ $commission->order->customer_name }}</td>
                                        <td>{{ Str::limit($commission->order->product->name, 25) }}</td>
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
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="fas fa-money-bill-wave fa-2x mb-3"></i>
                                            <p>No commissions yet. Start sharing your distributor code!</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Profile Completion -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-tasks me-1"></i>
                    Profile Completion
                </div>
                <div class="card-body">
                    @php
                        $completion = 50;
                        if ($agent->email) $completion += 10;
                        if ($agent->bank_details || $agent->upi_id) $completion += 20;
                        if ($agent->mobile_verified_at) $completion += 20;
                    @endphp
                    
                    <div class="progress mb-3" style="height: 20px;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $completion }}%">
                            {{ $completion }}%
                        </div>
                    </div>
                    
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            {!! $agent->mobile_verified_at 
                                ? '<i class="fas fa-check-circle text-success me-2"></i>' 
                                : '<i class="fas fa-times-circle text-danger me-2"></i>' !!}
                            Mobile Verified
                        </li>
                        <li class="mb-2">
                            {!! $agent->email 
                                ? '<i class="fas fa-check-circle text-success me-2"></i>' 
                                : '<i class="fas fa-times-circle text-danger me-2"></i>' !!}
                            Email Added
                        </li>
                        <li>
                            {!! ($agent->bank_details || $agent->upi_id)
                                ? '<i class="fas fa-check-circle text-success me-2"></i>'
                                : '<i class="fas fa-times-circle text-danger me-2"></i>' !!}
                            Payment Info Added
                        </li>
                    </ul>

                    <div class="mt-3">
                        <a href="{{ route('agent.profile') }}" class="btn btn-sm btn-outline-primary w-100">
                            <i class="fas fa-edit me-1"></i>Update Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function () {
    $('.copy-btn').click(function () {
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
