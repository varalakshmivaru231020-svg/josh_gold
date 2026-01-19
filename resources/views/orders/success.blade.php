@extends('layouts.app')

@section('title', 'Order Confirmation')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Success Card -->
            <div class="card border-success mb-4">
                <div class="card-header bg-success text-white">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle fa-2x me-3"></i>
                        <div>
                            <h4 class="mb-0">Order Confirmed!</h4>
                            <p class="mb-0">Thank you for your purchase</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
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
                                            <td>Order Status:</td>
                                            <td>
                                                <span class="badge bg-success">{{ ucfirst($order->order_status) }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Payment Status:</td>
                                            <td>
                                                <span class="badge bg-success">{{ ucfirst($order->payment_status) }}</span>
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
                                    
                                    <div class="d-flex justify-content-between">
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
                            <a href="https://wa.me/8217215195" target="_blank" class="btn btn-primary w-100">
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
                <!-- <div class="card-footer bg-transparent text-center">
                    <small class="text-muted">
                        Need help? <a href="mailto:support@agentreferral.com">Contact Support</a> or call +91 9876543210
                    </small>
                </div> -->
            </div>
            
            <!-- Order Summary Email -->
            <!-- <div class="text-center mt-4">
                <p class="text-muted">
                    <i class="fas fa-envelope me-2"></i>
                    An order confirmation has been sent to 
                    @if($order->customer_email)
                    <strong>{{ $order->customer_email }}</strong>
                    @else
                    your registered mobile number
                    @endif
                </p>
            </div> -->
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Print order receipt
    function printReceipt() {
        window.print();
    }
    
    // Add print button event listener
    $('.print-receipt').click(printReceipt);
});
</script>
@endpush
@endsection