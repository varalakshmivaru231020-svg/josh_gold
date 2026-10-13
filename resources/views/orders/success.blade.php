@extends('layouts.lawfirm')

@section('title', 'Order Confirmation - JOSHA GOLD AND DIAMOND JEWELLERY')

@section('content')
<section class="success-section">
    <div class="jb-wrap">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Success Card -->
                <div class="success-card">
                    <div class="success-header">
                        <div class="d-flex align-items-center">
                            <span class="success-header__icon"><i class="fas fa-check-circle"></i></span>
                            <div>
                                <h1 class="success-title">Order Confirmed!</h1>
                                <p class="mb-0">Thank you for your purchase</p>
                            </div>
                        </div>
                    </div>
                    <div class="success-body">
                    <div class="text-center mb-4">
                        <div class="display-1 text-success mb-3">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h2>Payment Successful</h2>
                        <p class="lead">Your order has been placed successfully.</p>
                    </div>
                    
                    <!-- Order Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-receipt me-2"></i>Order Details</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm order-table">
                                        <tr>
                                            <td>Order Number:</td>
                                            <td><strong>{{ $order->order_number }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Order Date:</td>
                                            <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Order Status:</td>
                                            <td>
                                                <span class="badge success-chip">{{ ucfirst($order->order_status) }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Payment Status:</td>
                                            <td>
                                                <span class="badge success-chip">{{ ucfirst($order->payment_status) }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Total Amount:</td>
                                            <td class="text-success">
                                                <strong>₹{{ number_format($order->total, 2) }}</strong>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-user me-2"></i>Customer Details</h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-2">
                                        <strong>{{ $order->customer_name }}</strong><br>
                                        {{ $order->customer_mobile }}
                                        @if($order->customer_email)
                                        <br>{{ $order->customer_email }}
                                        @endif
                                    </p>
                                    
                                    <hr class="my-2">
                                    
                                    <p class="mb-0">
                                        <strong>Shipping Address:</strong><br>
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
                                    <p class="text-muted mb-2">{{ $order->quantity }} × ₹{{ number_format($order->unit_price, 2) }}</p>
                                    
                                    <div class="d-flex justify-content-between success-totals">
                                        <div>
                                            <span class="text-muted">Subtotal:</span>
                                            <span class="ms-2">₹{{ number_format($order->subtotal, 2) }}</span>
                                        </div>
                                        @if($order->discount > 0)
                                        <div>
                                            <span class="text-success">Discount:</span>
                                            <span class="ms-2 text-success">-₹{{ number_format($order->discount, 2) }}</span>
                                        </div>
                                        @endif
                                        <div>
                                            <span class="text-primary"><strong>Total:</strong></span>
                                            <span class="ms-2 text-primary"><strong>₹{{ number_format($order->total, 2) }}</strong></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Next Steps -->
                    <!-- <div class="alert alert-info mb-4">
                        <div class="d-flex">
                            <i class="fas fa-info-circle fa-2x me-3 mt-1"></i>
                            <div>
                                <h6>What happens next?</h6>
                                <ul class="mb-0">
                                    <li>Order confirmation email has been sent</li>
                                    <li>Your order will be processed within 24 hours</li>
                                    <li>You will receive tracking details once shipped</li>
                                    <li>Estimated delivery: 3-7 business days</li>
                                </ul>
                            </div>
                        </div>
                    </div> -->
                    
                    <!-- Action Buttons -->
                    <!-- <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ route('track.order') }}?tracking_id={{ $order->order_number }}" 
                               class="btn btn-outline-primary w-100">
                                <i class="fas fa-shipping-fast me-2"></i>Track Your Order
                            </a>
                        </div> -->
                        <div class="col-md-12">
                            <a href="https://wa.me/919632527961" target="_blank" class="btn btn-primary w-100">
                                <i class="fas fa-shopping-bag me-2"></i>Go to Whatsapp
                            </a>
                        </div>
                    </div>
                    
                    <!-- Share & Referral -->
                    @if($order->referral_code)
                    <!-- <div class="card mt-4 border-success">
                        <div class="card-body text-center">
                            <h5 class="text-success mb-3">
                                <i class="fas fa-gift me-2"></i>
                                You saved ₹{{ number_format($order->discount, 2) }} with referral code!
                            </h5>
                            <p class="mb-3">
                                Want to earn commissions like the agent who referred you?<br>
                                Become an agent and earn 5% on every sale.
                            </p>
                            <a href="{{ route('agent.register') }}" class="btn btn-success">
                                <i class="fas fa-user-plus me-2"></i>Become an Agent
                            </a>
                        </div>
                    </div> -->
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.success-section { background: var(--jb-ground); padding: 40px 0 56px; }

.success-card { overflow: hidden; border: 1px solid var(--jb-line); border-radius: var(--jb-r-card); background: var(--jb-panel); box-shadow: 0 16px 36px -14px rgba(0, 0, 0, .9); }

/* Compact heading, in the page-head style */
.success-header { padding: 22px 28px; border-bottom: 1px solid var(--jb-line); background:
    radial-gradient(600px 220px at 100% 0%, rgba(201, 163, 92, .16), transparent 65%), var(--jb-black); }
.success-header__icon { width: 46px; height: 46px; flex: none; display: grid; place-items: center; margin-right: 14px; border: 1px solid rgba(92, 227, 168, .35); border-radius: 13px; background: rgba(92, 227, 168, .08); color: var(--jb-up); font-size: 1.35rem; }
.success-title { margin: 0; color: var(--jb-white); font: 600 clamp(21px, 2.6vw, 26px)/1.25 var(--jb-serif); }
.success-header p { color: var(--jb-muted); }

.success-body { padding: 28px; }

.success-body .display-1.text-success { color: var(--jb-up) !important; font-size: 4rem; }
.success-body h2 { color: var(--jb-white); font: 600 clamp(24px, 3vw, 30px)/1.25 var(--jb-serif); }
.success-body .lead { color: var(--jb-muted); font-size: 1.05rem; }

/* Inner cards */
.success-body .card { --bs-card-bg: var(--jb-panel-2); border: 1px solid var(--jb-line); border-radius: 14px; overflow: hidden; }
.success-body .card-header { padding: .8rem 1rem; border-bottom: 1px solid var(--jb-line); background: rgba(0, 0, 0, .35); }
.success-body .card-header h6 { color: var(--jb-white); font: 700 12px/1.3 var(--jb-sans); letter-spacing: .12em; text-transform: uppercase; }
.success-body .card-header h6 i { color: var(--jb-gold-light); }
.success-body .card-body { color: var(--jb-text); }
.success-body .card-body strong { color: var(--jb-white); }
.success-body h5 { color: var(--jb-white); font: 600 17px/1.3 var(--jb-serif); }
.success-body hr { border-color: var(--jb-line); opacity: 1; }

.order-table { margin-bottom: 0; }
.order-table td { padding: .45rem 0; border-color: var(--jb-line-soft); vertical-align: middle; }
.order-table tr:last-child td { border-bottom: 0; }
.order-table td:first-child { padding-right: 12px; color: var(--jb-muted); white-space: nowrap; }
.order-table td:last-child { word-break: break-word; }

.success-body .badge.success-chip { padding: .38em .7em; border: 1px solid rgba(201, 163, 92, .4); border-radius: 7px; background: rgba(201, 163, 92, .16); color: var(--jb-gold-light); font-weight: 700; }

.success-body .text-success { color: var(--jb-up) !important; }
.success-body .text-primary { color: var(--jb-white) !important; }
.success-body :is(.text-success, .text-primary) strong { color: inherit; }

.success-totals { flex-wrap: wrap; gap: 4px 16px; }

.success-body .btn-primary { min-height: 50px; display: inline-flex; align-items: center; justify-content: center; margin-top: 8px; font-weight: 700; }

@media (max-width: 767px) {
    .success-section { padding: 24px 0 40px; }
    .success-header { padding: 18px 20px; }
    .success-body { padding: 20px; }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Print order receipt
    function printReceipt() {
        window.print();
    }

    // Add print button event listener
    document.querySelectorAll('.print-receipt').forEach(function(btn) {
        btn.addEventListener('click', printReceipt);
    });
});
</script>
@endpush