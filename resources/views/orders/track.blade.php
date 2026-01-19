@extends('layouts.lawfirm')

@section('title', 'Track Order - Bengaluru Law House')

@section('content')
<section class="track-order-section">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="track-card">
                    <div class="track-header">
                        <h4 class="mb-0"><i class="fas fa-shipping-fast me-2"></i>Track Your Order</h4>
                    </div>
                    <div class="track-body">
                    <!-- Search Form -->
                    <div class="mb-5">
                        <form action="{{ route('track.order') }}" method="GET">
                            <div class="input-group input-group-lg">
                                <input type="text" 
                                       class="form-control" 
                                       name="tracking_id" 
                                       value="{{ request('tracking_id') }}"
                                       placeholder="Enter your Order Number or Tracking ID"
                                       required>
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search me-2"></i>Track
                                </button>
                            </div>
                            <div class="form-text mt-2">
                                Enter your order number (e.g., ORD20231231ABCD) or tracking ID provided in your confirmation email.
                            </div>
                        </form>
                    </div>
                    
                    @if($order)
                    <!-- Order Found -->
                    <div class="tracking-results">
                        <!-- Order Status Timeline -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Order Status</h5>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    @php
                                        $statuses = [
                                            'pending' => 'Order Placed',
                                            'confirmed' => 'Order Confirmed',
                                            'processing' => 'Processing',
                                            'shipped' => 'Shipped',
                                            'delivered' => 'Delivered',
                                            'cancelled' => 'Cancelled'
                                        ];
                                        
                                        $currentStatus = $order->order_status;
                                        $statusIndex = array_search($currentStatus, array_keys($statuses));
                                    @endphp
                                    
                                    @foreach($statuses as $status => $label)
                                    <div class="timeline-item {{ $loop->index <= $statusIndex ? 'active' : '' }} 
                                                  {{ $currentStatus === 'cancelled' && $status === 'cancelled' ? 'cancelled' : '' }}">
                                        <div class="timeline-marker">
                                            @if($loop->index < $statusIndex)
                                            <i class="fas fa-check-circle"></i>
                                            @elseif($loop->index == $statusIndex)
                                            <i class="fas fa-circle"></i>
                                            @else
                                            <i class="far fa-circle"></i>
                                            @endif
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">{{ $label }}</h6>
                                            @if($loop->index == 0)
                                            <small class="text-muted">{{ $order->created_at->format('d M Y, h:i A') }}</small>
                                            @elseif($loop->index == $statusIndex)
                                            <small class="text-muted">Current status</small>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <!-- Order Details -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Order Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm">
                                            <tr>
                                                <td>Order Number:</td>
                                                <td><strong>{{ $order->order_number }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Order Date:</td>
                                                <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Current Status:</td>
                                                <td>
                                                    <span class="badge bg-{{ $order->order_status === 'delivered' ? 'success' : 
                                                                          ($order->order_status === 'cancelled' ? 'danger' : 
                                                                          ($order->order_status === 'pending' ? 'warning' : 'info')) }}">
                                                        {{ ucfirst($order->order_status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @if($order->tracking_id)
                                            <tr>
                                                <td>Tracking ID:</td>
                                                <td><code>{{ $order->tracking_id }}</code></td>
                                            </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="fas fa-user me-2"></i>Shipping Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-2">
                                            <strong>{{ $order->customer_name }}</strong><br>
                                            {{ $order->customer_mobile }}
                                        </p>
                                        <hr class="my-2">
                                        <p class="mb-0">
                                            {{ $order->shipping_address }}<br>
                                            {{ $order->city }}, {{ $order->state }}<br>
                                            {{ $order->pincode }}, {{ $order->country }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Product Details -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-box me-2"></i>Product Details</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex">
                                    @if($order->product->images && count($order->product->images) > 0)
                                    <img src="{{ asset('storage/' . $order->product->images[0]) }}" 
                                         alt="{{ $order->product->name }}" 
                                         class="rounded me-3"
                                         style="width: 80px; height: 80px; object-fit: cover;">
                                    @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center me-3"
                                         style="width: 80px; height: 80px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                    @endif
                                    
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1">{{ $order->product->name }}</h5>
                                        <p class="text-muted mb-2">Quantity: {{ $order->quantity }}</p>
                                        
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <span class="text-muted">Unit Price:</span>
                                                <span class="ms-2">₹{{ number_format($order->unit_price, 2) }}</span>
                                            </div>
                                            <div>
                                                <span class="text-muted">Total:</span>
                                                <span class="ms-2 text-primary">
                                                    <strong>₹{{ number_format($order->total, 2) }}</strong>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tracking Actions -->
                        @if($order->tracking_url)
                        <div class="text-center mb-4">
                            <a href="{{ $order->tracking_url }}" target="_blank" class="btn btn-success btn-lg">
                                <i class="fas fa-external-link-alt me-2"></i>Track on Carrier Website
                            </a>
                        </div>
                        @endif
                        
                        <!-- Contact Support -->
                        <div class="alert alert-warning">
                            <div class="d-flex">
                                <i class="fas fa-question-circle fa-2x me-3 mt-1"></i>
                                <div>
                                    <h6>Need Help?</h6>
                                    <p class="mb-2">
                                        If you have any questions about your order or need assistance with tracking, 
                                        please contact our customer support team.
                                    </p>
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="mailto:support@agentreferral.com" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-envelope me-1"></i>Email Support
                                        </a>
                                        <a href="tel:+919876543210" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-phone me-1"></i>Call Support
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @elseif(request('tracking_id'))
                    <!-- Order Not Found -->
                    <div class="text-center py-5">
                        <div class="display-1 text-muted mb-4">
                            <i class="fas fa-search"></i>
                        </div>
                        <h4>Order Not Found</h4>
                        <p class="text-muted mb-4">
                            We couldn't find an order with tracking ID: <strong>{{ request('tracking_id') }}</strong>
                        </p>
                        <div class="alert alert-info">
                            <p class="mb-2">Please check:</p>
                            <ul class="mb-0 text-start">
                                <li>The order number is correct</li>
                                <li>You entered the tracking ID correctly</li>
                                <li>The order was placed within the last 6 months</li>
                            </ul>
                        </div>
                    </div>
                    @else
                    <!-- Initial State -->
                    <div class="text-center py-5">
                        <div class="display-1 text-primary mb-4">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <h4>Track Your Order</h4>
                        <p class="text-muted mb-4">
                            Enter your order number or tracking ID above to check the status of your order.
                        </p>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="text-primary mb-3">
                                            <i class="fas fa-search fa-2x"></i>
                                        </div>
                                        <h6>Enter Details</h6>
                                        <small class="text-muted">Provide your order number or tracking ID</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="text-primary mb-3">
                                            <i class="fas fa-sync-alt fa-2x"></i>
                                        </div>
                                        <h6>Track Status</h6>
                                        <small class="text-muted">View real-time order updates</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="text-primary mb-3">
                                            <i class="fas fa-truck fa-2x"></i>
                                        </div>
                                        <h6>Get Updates</h6>
                                        <small class="text-muted">Receive delivery notifications</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    </div>
                    <div class="track-footer">
                        <small>
                            Need help finding your order number? Check your confirmation email or contact support.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.track-order-section {
    margin-top: 80px;
    min-height: calc(100vh - 80px);
    background: var(--cream-primary);
    padding: 40px 0;
}

.track-card {
    background: var(--white);
    border-radius: 12px;
    box-shadow: var(--shadow-medium);
    overflow: hidden;
}

.track-header {
    background: var(--navy-deep);
    color: var(--cream-primary);
    padding: 1.5rem 2rem;
    border-bottom: 3px solid var(--gold-primary);
}

.track-header h4 {
    color: var(--cream-primary);
    font-family: var(--font-heading);
}

.track-body {
    padding: 2rem;
}

.track-footer {
    background: rgba(26, 35, 50, 0.03);
    padding: 1rem 2rem;
    text-align: center;
    color: var(--text-muted);
}

.track-body .input-group-lg .form-control {
    border: 2px solid rgba(201, 162, 39, 0.3);
    border-radius: 8px 0 0 8px;
}

.track-body .input-group-lg .form-control:focus {
    border-color: var(--gold-primary);
    box-shadow: 0 0 0 0.2rem rgba(201, 162, 39, 0.15);
}

.track-body .input-group-lg .btn {
    background: var(--gold-primary);
    border: 2px solid var(--gold-primary);
    color: var(--navy-deep);
    font-weight: 600;
    border-radius: 0 8px 8px 0;
}

.track-body .input-group-lg .btn:hover {
    background: var(--gold-dark);
    border-color: var(--gold-dark);
}

.track-body .card {
    border: 1px solid rgba(201, 162, 39, 0.2);
    border-radius: 8px;
}

.track-body .card-header {
    background: rgba(26, 35, 50, 0.03);
    border-bottom: 2px solid var(--gold-primary);
    color: var(--navy-deep);
}

.track-body .card-header h5,
.track-body .card-header h6 {
    color: var(--navy-deep);
}

.track-body .badge.bg-success {
    background: var(--gold-primary) !important;
    color: var(--navy-deep);
}

.track-body .badge.bg-info {
    background: var(--navy-deep) !important;
}

.track-body .text-primary {
    color: var(--gold-primary) !important;
}

.track-body .btn-success {
    background: var(--gold-primary);
    border-color: var(--gold-primary);
    color: var(--navy-deep);
    font-weight: 600;
}

.track-body .btn-success:hover {
    background: var(--gold-dark);
    border-color: var(--gold-dark);
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: rgba(201, 162, 39, 0.3);
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
    min-height: 50px;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-item.active .timeline-marker {
    color: var(--gold-primary);
}

.timeline-item.cancelled .timeline-marker {
    color: #e74a3b;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    top: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    background: white;
}

.timeline-content {
    padding-left: 15px;
}

.timeline-content h6 {
    color: var(--navy-deep);
}

.alert-warning {
    background: rgba(201, 162, 39, 0.1);
    border: 1px solid rgba(201, 162, 39, 0.3);
    color: var(--navy-deep);
}

.display-1.text-primary {
    color: var(--gold-primary) !important;
}

@media (max-width: 767px) {
    .track-order-section {
        margin-top: 70px;
        padding: 20px 0;
    }

    .track-body {
        padding: 1.5rem;
    }
}
</style>
@endpush