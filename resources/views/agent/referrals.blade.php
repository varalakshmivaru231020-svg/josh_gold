@extends('layouts.agent')

@section('title', 'My Referrals')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">My Referrals</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('agent.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Referrals</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-users me-1"></i>
                    Direct Referrals
                </div>
                <span class="badge bg-primary">{{ $referrals->total() }} Distributors</span>
            </div>
        </div>
        <div class="card-body">
            @if($referrals->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Distributor</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>Referral Code</th>
                            <th>Commissions</th>
                            <th>Joined</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($referrals as $referral)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $referral->name }}</strong>
                            </td>
                            <td>{{ $referral->mobile }}</td>
                            <td>{{ $referral->email ?? 'N/A' }}</td>
                            <td>
                                <code>{{ $referral->referral_code }}</code>
                            </td>
                            <td>
                                <span class="badge bg-info me-1">Total: {{ $referral->total_commissions }}</span>
                                <span class="badge bg-warning me-1">Pending: {{ $referral->pending_commissions }}</span>
                                <span class="badge bg-success">Settled: {{ $referral->settled_commissions }}</span>
                            </td>
                            <td>{{ $referral->created_at->format('d M Y') }}</td>
                            <td>
                                @if($referral->mobile_verified_at)
                                <span class="badge bg-success">Verified</span>
                                @else
                                <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $referrals->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-4x text-muted mb-4"></i>
                <h4>No referrals yet</h4>
                <p class="text-muted mb-4">Start sharing your referral code to earn commissions!</p>
                <a href="{{ route('agent.share.referral') }}" class="btn btn-primary">
                    <i class="fas fa-share-alt me-2"></i>Share Referral Code
                </a>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Referral Tips -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-lightbulb me-1"></i>
                    How to Get More Referrals
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Share on social media (Facebook, Instagram, WhatsApp)
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Include referral code in your email signature
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Tell friends and family about the products
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Create posts about product benefits
                        </li>
                        <li>
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Join online communities and share your experience
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-money-bill-wave me-1"></i>
                    Earnings Potential
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Commission Structure</h6>
                        <p class="mb-2">You earn <strong>5% commission</strong> on every purchase made using your referral code.</p>
                        <p class="mb-0">The customer gets <strong>10% discount</strong> on their purchase.</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Example Calculation</h6>
                        <table class="table table-sm">
                            <tr>
                                <td>Product Price:</td>
                                <td class="text-end">₹1,000</td>
                            </tr>
                            <tr>
                                <td>Customer Discount (10%):</td>
                                <td class="text-end text-success">-₹100</td>
                            </tr>
                            <tr>
                                <td>Final Price:</td>
                                <td class="text-end">₹900</td>
                            </tr>
                            <tr class="table-success">
                                <td><strong>Your Commission (5%):</strong></td>
                                <td class="text-end"><strong>₹45</strong></td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle me-2"></i>
                        Commissions are settled monthly. You can track settlement status in your dashboard.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection