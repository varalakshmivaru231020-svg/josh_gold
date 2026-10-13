@extends('layouts.lawfirm')

@section('title', 'Track Your Order - JOSHA GOLD AND DIAMOND JEWELLERY')

@section('content')
@include('layouts.partials.page-head', [
    'title'  => 'Track Your',
    'accent' => 'Order',
    'crumbs' => [['Track Your Order']],
])

<section class="track-order-wrapper">
    <div class="jb-wrap">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8">
                <!-- Track Card -->
                <div class="premium-track-card">
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
                                    <button class="btn-track" type="submit">
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
                                        <a href="{{ $order->tracking_url }}" target="_blank" class="jb-btn jb-btn--ghost btn-track-link">
                                            Track on Carrier Website <i class="fas fa-external-link-alt ms-2"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @elseif(request('tracking_id'))
                            <!-- Not Found -->
                            <div class="not-found-state py-5 text-center fadeIn">
                                <div class="not-found-icon mb-4">
                                    <i class="fas fa-search-minus fa-4x"></i>
                                </div>
                                <h4 class="not-found-title">Order Not Found</h4>
                                <p class="text-muted px-lg-5">We couldn't find any order with the provided ID. Please double check your order number and try again.</p>
                            </div>
                        @else
                            <!-- Default Landing State -->
                            <div class="landing-state py-4 text-center">
                                <div class="main-illustration mb-4">
                                    <i class="fas fa-truck fa-4x"></i>
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
                        <p class="mb-0 small text-muted">Need help finding your order? <a href="{{ route('contact') }}" class="jb-link">Contact Support</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    .track-order-wrapper { background: var(--jb-ground); padding: 32px 0 48px; }
    @media (min-width: 768px) { .track-order-wrapper { padding: 48px 0 64px; } }

    .premium-track-card { overflow: hidden; border: 1px solid var(--jb-line); border-radius: var(--jb-r-card); background: linear-gradient(180deg, rgba(201, 163, 92, .05), transparent 220px), var(--jb-panel); box-shadow: 0 16px 36px -14px rgba(0, 0, 0, .9); }
    .track-card-body { padding: 22px; }
    @media (min-width: 768px) { .track-card-body { padding: 36px; } }

    /* Search */
    .search-container .input-group { flex-wrap: nowrap; gap: 6px; padding: 6px; border: 1px solid var(--jb-line); border-radius: var(--jb-r-btn); background: var(--jb-black); transition: border-color .15s, box-shadow .15s; }
    .search-container .input-group:focus-within { border-color: rgba(234, 215, 166, .55); box-shadow: 0 0 0 .2rem rgba(234, 215, 166, .12); }
    .search-container .form-control { min-width: 0; padding: 10px 12px; border: 0; border-radius: 10px !important; background: transparent; color: var(--jb-white); font-size: 15px; }
    .search-container .form-control:focus { background: transparent; box-shadow: none; }
    .btn-track { flex: none; display: inline-flex; align-items: center; gap: 6px; padding: 0 22px; border: 0; border-radius: 10px !important; background: var(--jb-champagne); color: #111; font-weight: 700; font-size: 14px; cursor: pointer; transition: background-color .15s; }
    .btn-track:hover { background: #fff8ea; }
    .search-hint { color: var(--jb-muted); font-size: 12.5px; line-height: 1.55; }

    /* Landing state */
    .main-illustration i { color: var(--jb-gold); opacity: .9; }
    .track-title { color: var(--jb-white); font: 600 clamp(22px, 3vw, 26px)/1.25 var(--jb-serif); }
    .landing-state > .text-muted { max-width: 520px; margin-left: auto; margin-right: auto; }

    .feature-card { height: 100%; padding: 20px 16px; border: 1px solid var(--jb-line); border-radius: var(--jb-r-card); background: var(--jb-panel-2); transition: transform .2s, border-color .2s; }
    @media (hover: hover) { .feature-card:hover { transform: translateY(-3px); border-color: var(--jb-line-strong); } }
    .feature-icon { width: 46px; height: 46px; display: grid; place-items: center; margin: 0 auto 14px; border: 1px solid var(--jb-line); border-radius: 13px; background: #0a0a0b; color: var(--jb-gold-light); font-size: 1.1rem; }
    .feature-card h5 { margin-bottom: 6px; color: var(--jb-white); font: 600 16px/1.3 var(--jb-serif); }

    /* Not found */
    .not-found-icon i { color: var(--jb-muted); opacity: .6; }
    .not-found-title { color: var(--jb-white); font: 600 22px/1.3 var(--jb-serif); }

    /* Result summary */
    .status-summary { padding-bottom: 18px; border-bottom: 1px solid var(--jb-line-soft); }
    .order-id { margin-bottom: 4px; color: var(--jb-white); font: 600 20px/1.3 var(--jb-serif); word-break: break-word; }

    .status-badge { display: inline-flex; align-items: center; min-height: 28px; margin-top: 10px; padding: 0 14px; border: 1px solid var(--jb-line); border-radius: 999px; background: var(--jb-panel-2); color: var(--jb-text); font-size: 12.5px; font-weight: 700; letter-spacing: .02em; }
    @media (min-width: 768px) { .status-badge { margin-top: 0; } }
    .status-badge.pending { color: #ffd59a; background: rgba(255, 170, 60, .12); border-color: rgba(255, 170, 60, .4); }
    .status-badge.confirmed, .status-badge.processing { color: var(--jb-gold-light); background: rgba(201, 163, 92, .16); border-color: rgba(201, 163, 92, .4); }
    .status-badge.shipped, .status-badge.delivered { color: var(--jb-up); background: rgba(92, 227, 168, .1); border-color: rgba(92, 227, 168, .35); }
    .status-badge.cancelled { color: var(--jb-down); background: rgba(255, 112, 112, .1); border-color: rgba(255, 112, 112, .35); }

    /* Timeline: gold = done, muted = still to come */
    .tracking-timeline { padding: 20px 0; }
    .timeline-steps { position: relative; display: flex; justify-content: space-between; }
    .timeline-steps::before { content: ""; position: absolute; z-index: 1; top: 22px; left: 10%; right: 10%; height: 2px; background: var(--jb-line); }
    .timeline-step { position: relative; z-index: 2; flex: 1; text-align: center; }
    .step-icon { width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; border: 2px solid var(--jb-line); border-radius: 50%; background: var(--jb-panel-2); color: #6b6b6b; transition: all .3s ease; }
    .timeline-step.completed .step-icon { border-color: var(--jb-gold); background: #120f0a; color: var(--jb-gold-light); }
    .timeline-step.active .step-icon { border-color: var(--jb-champagne); background: var(--jb-champagne); color: #111; box-shadow: 0 0 0 5px rgba(234, 215, 166, .12), 0 0 18px rgba(201, 163, 92, .35); }
    .step-label { color: var(--jb-muted); font-size: 12.5px; font-weight: 600; }
    .timeline-step.completed .step-label { color: var(--jb-gold-light); }
    .timeline-step.active .step-label { color: var(--jb-white); }

    /* Info boxes */
    .info-box { height: 100%; padding: 18px; border: 1px solid var(--jb-line); border-radius: var(--jb-r-card); background: var(--jb-panel-2); }
    .info-box h6 { margin-bottom: 0; color: var(--jb-white); font: 700 12px/1.3 var(--jb-sans); letter-spacing: .12em; text-transform: uppercase; }
    .info-box .fw-bold { color: var(--jb-white); }
    .info-box h6 .text-gold { color: var(--jb-gold-light) !important; }
    .info-box div.text-gold { color: var(--jb-gold-light) !important; font-family: var(--jb-serif); font-size: 1.05rem; }
    .product-mini-img { width: 60px; height: 60px; flex: none; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 1px solid var(--jb-line); border-radius: var(--jb-r-ctl); background: var(--jb-panel-3); color: var(--jb-muted); }
    .product-mini-img img { width: 100%; height: 100%; object-fit: cover; }

    .btn-track-link { min-height: 44px; }

    .track-card-footer { padding: 15px 18px; border-top: 1px solid var(--jb-line-soft); background: rgba(0, 0, 0, .35); text-align: center; }

    .fadeIn { animation: fadeIn 0.5s ease; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 767px) {
        .timeline-steps { flex-direction: column; align-items: flex-start; padding-left: 8px; }
        .timeline-steps::before { top: 22px; bottom: 22px; left: 29px; right: auto; width: 2px; height: auto; }
        .timeline-step { display: flex; align-items: center; margin-bottom: 18px; text-align: left; }
        .timeline-step:last-child { margin-bottom: 0; }
        .step-icon { margin: 0 14px 0 0; }
        .step-label { font-size: 14px; }
    }
    @media (max-width: 420px) {
        .btn-track { padding: 0 14px; }
        .search-container .form-control { font-size: 14px; padding: 10px 8px; }
    }
</style>
@endpush
@endsection