@extends('layouts.agent')

@section('title', 'My Distributor Commissions')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">My Distributor Commissions</h1>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item">
            <a href="{{ route('agent.dashboard') }}">Distributor Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Commissions</li>
    </ol>
    
    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-normal">Total Earnings</h6>
                            <h4 class="mb-0">₹{{ number_format($commissions->sum('amount'), 2) }}</h4>
                        </div>
                        <i class="fas fa-money-bill-wave fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-normal">Settled</h6>
                            <h4 class="mb-0">
                                ₹{{ number_format($commissions->where('status', 'settled')->sum('amount'), 2) }}
                            </h4>
                        </div>
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-normal">Pending</h6>
                            <h4 class="mb-0">
                                ₹{{ number_format($commissions->where('status', 'pending')->sum('amount'), 2) }}
                            </h4>
                        </div>
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Distributor Referrals Table -->
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-table me-1"></i>
                    My Distributor Referrals
                </div>
                <div class="btn-group" role="group">
                    <a href="{{ route('agent.commissions') }}"
                       class="btn btn-outline-primary btn-sm {{ request('status') == null ? 'active' : '' }}">
                        All
                    </a>
                    <a href="{{ route('agent.commissions', ['status' => 'pending']) }}"
                       class="btn btn-outline-warning btn-sm {{ request('status') == 'pending' ? 'active' : '' }}">
                        Pending
                    </a>
                    <a href="{{ route('agent.commissions', ['status' => 'settled']) }}"
                       class="btn btn-outline-success btn-sm {{ request('status') == 'settled' ? 'active' : '' }}">
                        Settled
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            @if($commissions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Order Details</th>
                                <th>Customer Name</th>
                                <th>Product</th>
                                <th>Commission</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Settled On</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($commissions as $commission)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>Order #{{ $commission->order->order_number }}</strong><br>
                                        <small class="text-muted">
                                            Customer: {{ $commission->order->customer_name }}
                                        </small><br>
                                        <small>
                                            Order Total: ₹{{ number_format($commission->order->total, 2) }}
                                        </small>
                                    </td>
                                    <td>{{ $commission->order->customer_name }}</td>
                                    <td>
                                        {{ Str::limit($commission->order->product->name, 30) }}<br>
                                        <small>Qty: {{ $commission->order->quantity }}</small>
                                    </td>
                                    <td>
                                        <strong class="text-success">
                                            ₹{{ number_format($commission->amount, 2) }}
                                        </strong>
                                    </td>
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $commissions->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-money-bill-wave fa-4x text-muted mb-4"></i>
                    <h4>No distributor commissions yet</h4>
                    <p class="text-muted mb-4">
                        Start sharing your distributor referral code to earn commissions!
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
