@extends('layouts.lawfirm')

@section('title', 'Track Your Order - Bengaluru Law House')

@section('content')
<section class="track-order-wrapper">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Track Card -->
                <div class="premium-track-card">
                    <div class="track-card-header">
                        <h4 class="mb-0"><i class="fas fa-truck-moving me-2"></i>Track Your Order</h4>
                    </div>
                    
                    <div class="track-card-body">
                        <!-- Search Form -->
                        <div class="search-container mb-5">
                            <form action="{{ route('track.order') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control" 
                                           name="tracking_id" 
                                           value="{{ request('tracking_id') }}"
                                           placeholder="Enter your Order Number or Tracking ID"
                                           required>
                                    <button class="btn btn-track" type="submit">
                                        <i class="fas fa-search me-1"></i> Track
                                    </button>
                                </div>
                                <div class="search-hint mt-2">
                                    Enter your order number (e.g., ORD20231231ABCD) or tracking ID provided in your confirmation email.
                                </div>
                            </form>
                        </div>

                        @if($order)
                            <!-- Order Results Section -->
                            <div class="tracking-content mt-4 fadeIn">
                                <div class="status-summary mb-4">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <h5 class="order-id">Order #{{ $order->order_number }}</h5>
                                            <p class="order-date text-muted mb-0">Placed on {{ $order->created_at->format('d M Y') }}</p>
                                        </div>
                                        <div class="col-md-6 text-md-end">
                                            <span class="status-badge {{ $order->order_status }}">
                                                {{ ucfirst($order->order_status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Timeline -->
                                <div class="tracking-timeline mb-5">
                                    @php
                                        $statuses = [
                                            'pending' => ['label' => 'Order Placed', 'icon' => 'fa-shopping-bag'],
                                            'confirmed' => ['label' => 'Confirmed', 'icon' => 'fa-check-circle'],
                                            'processing' => ['label' => 'Processing', 'icon' => 'fa-cog'],
                                            'shipped' => ['label' => 'Shipped', 'icon' => 'fa-truck'],
                                            'delivered' => ['label' => 'Delivered', 'icon' => 'fa-home'],
                                        ];
                                        
                                        $currentStatus = $order->order_status;
                                        $allStatuses = array_keys($statuses);
                                        $currentIndex = array_search($currentStatus, $allStatuses);
                                        if ($currentIndex === false) $currentIndex = 0;
                                    @endphp

                                    <div class="timeline-steps">
                                        @foreach($statuses as $status => $data)
                                            @php
                                                $isCompleted = array_search($status, $allStatuses) <= $currentIndex;
                                                $isActive = $status === $currentStatus;
                                            @endphp
                                            <div class="timeline-step {{ $isCompleted ? 'completed' : '' }} {{ $isActive ? 'active' : '' }}">
                                                <div class="step-icon">
                                                    <i class="fas {{ $data['icon'] }}"></i>
                                                </div>
                                                <div class="step-label">{{ $data['label'] }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <div class="info-box">
                                            <h6><i class="fas fa-box-open me-2 text-gold"></i>Product Info</h6>
                                            <div class="d-flex mt-3">
                                                <div class="product-mini-img">
                                                    @if($order->product->images && count($order->product->images) > 0)
                                                        <img src="{{ asset('storage/' . $order->product->images[0]) }}" alt="">
                                                    @else
                                                        <i class="fas fa-image"></i>
                                                    @endif
                                                </div>
                                                <div class="ms-3">
                                                    <div class="fw-bold">{{ $order->product->name }}</div>
                                                    <div class="text-muted small">Qty: {{ $order->quantity }}</div>
                                                    <div class="text-gold fw-bold mt-1">₹{{ number_format($order->total, 2) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="info-box">
                                            <h6><i class="fas fa-map-marker-alt me-2 text-gold"></i>Shipping Address</h6>
                                            <div class="mt-3 small">
                                                <div class="fw-bold">{{ $order->customer_name }}</div>
                                                <div class="text-muted">{{ $order->shipping_address }}</div>
                                                <div class="text-muted">{{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}</div>
                                                <div class="text-muted">Contact: {{ $order->customer_mobile }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($order->tracking_url)
                                    <div class="text-center mt-3">
                                        <a href="{{ $order->tracking_url }}" target="_blank" class="btn btn-track-link">
                                            Track on Carrier Website <i class="fas fa-external-link-alt ms-2"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @elseif(request('tracking_id'))
                            <!-- Not Found -->
                            <div class="not-found-state py-5 text-center fadeIn">
                                <div class="not-found-icon mb-4">
                                    <i class="fas fa-search-minus fa-4x text-muted"></i>
                                </div>
                                <h4 class="text-dark">Order Not Found</h4>
                                <p class="text-muted px-lg-5">We couldn't find any order with the provided ID. Please double check your order number and try again.</p>
                            </div>
                        @else
                            <!-- Default Landing State -->
                            <div class="landing-state py-4 text-center">
                                <div class="main-illustration mb-4">
                                    <i class="fas fa-truck fa-5x text-gold"></i>
                                </div>
                                <h4 class="track-title mb-4">Track Your Order</h4>
                                <p class="text-muted mb-5">Enter your order number or tracking ID above to check the status of your order.</p>
                                
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <div class="feature-card">
                                            <div class="feature-icon">
                                                <i class="fas fa-search"></i>
                                            </div>
                                            <h5>Enter Details</h5>
                                            <p class="text-muted small mb-0">Provide your order number or tracking ID</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="feature-card">
                                            <div class="feature-icon">
                                                <i class="fas fa-sync-alt"></i>
                                            </div>
                                            <h5>Track Status</h5>
                                            <p class="text-muted small mb-0">View real-time order updates</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="feature-card">
                                            <div class="feature-icon">
                                                <i class="fas fa-truck"></i>
                                            </div>
                                            <h5>Get Updates</h5>
                                            <p class="text-muted small mb-0">Receive delivery notifications</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="track-card-footer">
                        <p class="mb-0 small text-muted">Need help finding your order? <a href="{{ route('contact') }}" class="text-gold">Contact Support</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    :root {
        --navy-dark: #1A2332;
        --gold-primary: #C9A227;
        --gold-light: #E5C158;
        --cream-bg: #FCF9F2;
        --text-dark: #333333;
    }

    .track-order-wrapper {
        background-color: var(--cream-bg);
        min-height: 80vh;
        padding-top: 100px;
    }

    .premium-track-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .track-card-header {
        background-color: var(--navy-dark);
        color: #fff;
        padding: 20px 30px;
        border-bottom: 4px solid var(--gold-primary);
    }

    .track-card-header h4 {
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .track-card-body {
        padding: 40px;
    }

    /* Search Input Styling */
    .search-container .input-group {
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
    }

    .search-container .form-control {
        border: none;
        padding: 15px 20px;
        font-size: 1.05rem;
    }

    .search-container .form-control:focus {
        box-shadow: none;
    }

    .btn-track {
        background-color: var(--gold-primary);
        color: var(--navy-dark);
        border: none;
        padding: 0 30px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
    }

    .btn-track:hover {
        background-color: #b08e21;
        color: #fff;
    }

    .search-hint {
        color: #888;
        font-size: 0.85rem;
    }

    /* Main Illustration */
    .text-gold {
        color: var(--gold-primary) !important;
    }

    .track-title {
        color: var(--navy-dark);
        font-weight: 800;
        font-size: 1.75rem;
    }

    /* Feature Cards */
    .feature-card {
        padding: 20px;
        border: 1px solid #f0f0f0;
        border-radius: 12px;
        transition: transform 0.3s ease;
    }

    .feature-card:hover {
        transform: translateY(-5px);
        border-color: var(--gold-primary);
    }

    .feature-icon {
        width: 50px;
        height: 50px;
        background: rgba(201, 162, 39, 0.1);
        color: var(--gold-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin: 0 auto 15px;
        font-size: 1.25rem;
    }

    .feature-card h5 {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--navy-dark);
    }

    /* Timeline Styling */
    .tracking-timeline {
        padding: 20px 0;
    }

    .timeline-steps {
        display: flex;
        justify-content: space-between;
        position: relative;
    }

    .timeline-steps::before {
        content: "";
        position: absolute;
        top: 25px;
        left: 50px;
        right: 50px;
        height: 3px;
        background: #eee;
        z-index: 1;
    }

    .timeline-step {
        position: relative;
        z-index: 2;
        text-align: center;
        flex: 1;
    }

    .step-icon {
        width: 50px;
        height: 50px;
        background: #fff;
        border: 3px solid #eee;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        color: #ccc;
        transition: all 0.3s ease;
    }

    .timeline-step.completed .step-icon {
        border-color: var(--gold-primary);
        color: var(--gold-primary);
    }

    .timeline-step.active .step-icon {
        background: var(--gold-primary);
        color: #fff;
        border-color: var(--gold-primary);
        box-shadow: 0 0 15px rgba(201, 162, 39, 0.4);
    }

    .step-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #999;
    }

    .timeline-step.completed .step-label,
    .timeline-step.active .step-label {
        color: var(--navy-dark);
    }

    /* Info Boxes */
    .info-box {
        background: #fbfbfb;
        padding: 20px;
        border-radius: 12px;
        height: 100%;
        border-left: 4px solid var(--gold-primary);
    }

    .info-box h6 {
        font-weight: 700;
        color: var(--navy-dark);
        margin-bottom: 0;
    }

    .product-mini-img {
        width: 60px;
        height: 60px;
        background: #eee;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .product-mini-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .status-badge {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 700;
    }

    .status-badge.pending { background: #fff3cd; color: #856404; }
    .status-badge.processing { background: #cce5ff; color: #004085; }
    .status-badge.shipped { background: #d4edda; color: #155724; }
    .status-badge.delivered { background: #d1ecf1; color: #0c5460; }
    .status-badge.cancelled { background: #f8d7da; color: #721c24; }

    .track-card-footer {
        background-color: #f9f9f9;
        padding: 15px;
        text-align: center;
        border-top: 1px solid #eee;
    }

    .fadeIn {
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
        .timeline-steps {
            flex-direction: column;
            align-items: flex-start;
            padding-left: 30px;
        }
        .timeline-steps::before {
            top: 0;
            bottom: 0;
            left: 55px;
            width: 3px;
            height: auto;
        }
        .timeline-step {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            text-align: left;
        }
        .step-icon {
            margin: 0 15px 0 0;
        }
    }
</style>
@endpush
@endsection