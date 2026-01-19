@extends('admin.layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Order Details</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
        <li class="breadcrumb-item active">Order #{{ $order->order_number }}</li>
    </ol>
    
    <div class="row">
        <!-- Order Details -->
        <div class="col-lg-8">
            <div class="card mb-4" style="padding:10px">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-receipt me-1"></i>
                        Order Information
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('admin.orders.invoice', $order->id) }}" 
                           class="btn btn-success btn-sm">
                            <i class="fas fa-file-invoice me-1"></i> Invoice
                        </a>

                        <span class="badge bg-light text-dark">
                            {{ $order->created_at->format('d M Y, h:i A') }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Order Summary</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td>Order Number:</td>
                                    <td><strong>{{ $order->order_number }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Product:</td>
                                    <td>
                                        <strong>{{ $order->product->name }}</strong><br>
                                        <small class="text-muted">SKU: PROD-{{ $order->product_id }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Quantity:</td>
                                    <td><strong>{{ $order->quantity }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Unit Price:</td>
                                    <td>₹{{ number_format($order->unit_price, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Subtotal:</td>
                                    <td>₹{{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                @if($order->discount > 0)
                                <tr class="table-success">
                                    <td>Discount:</td>
                                    <td>-₹{{ number_format($order->discount, 2) }}</td>
                                </tr>
                                @endif
                                <tr class="table-primary">
                                    <td><strong>Total Amount:</strong></td>
                                    <td><strong>₹{{ number_format($order->total, 2) }}</strong></td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h6>Payment Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td>Payment Method:</td>
                                    <td>{{ ucfirst($order->payment_method) }}</td>
                                </tr>
                                <tr>
                                    <td>Payment Status:</td>
                                    <td>
                                        <span class="badge bg-{{ $order->payment_status === 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </td>
                                </tr>
                                @if($order->payment_id)
                                <tr>
                                    <td>Payment ID:</td>
                                    <td><small>{{ $order->payment_id }}</small></td>
                                </tr>
                                @endif
                            </table>
                            
                            <h6 class="mt-4">Referral Information</h6>
                            @if($order->referral_code)
                            <table class="table table-sm">
                                <tr>
                                    <td>Referral Code:</td>
                                    <td><code>{{ $order->referral_code }}</code></td>
                                </tr>
                                @if($order->commission)
                                <tr>
                                    <td>Commission:</td>
                                    <td>
                                        ₹{{ number_format($order->commission->amount, 2) }}
                                        <span class="badge bg-{{ $order->commission->status === 'settled' ? 'success' : 'warning' }}">
                                            {{ ucfirst($order->commission->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endif
                            </table>
                            @else
                            <p class="text-muted">No referral code used</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Customer Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    Customer Information
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Agent Details</h6>
                            <p>
                                <strong>{{ $order->customer_name }}</strong><br>
                                <i class="fas fa-phone me-1"></i> {{ $order->customer_mobile }}<br>
                                @if($order->customer_email)
                                <i class="fas fa-envelope me-1"></i> {{ $order->customer_email }}
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>Shipping Address</h6>
                            <address>
                                {{ $order->shipping_address }}<br>
                                {{ $order->city }}, {{ $order->state }}<br>
                                {{ $order->country }} - {{ $order->pincode }}
                            </address>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Order Status & Actions -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-truck me-1"></i>
                    Order Status & Tracking
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Current Status</label>
                            <div class="alert alert-{{ $order->order_status === 'delivered' ? 'success' : 
                                                    ($order->order_status === 'cancelled' ? 'danger' : 
                                                    ($order->order_status === 'pending' ? 'warning' : 'info')) }} mb-3">
                                <strong>{{ ucfirst($order->order_status) }}</strong>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="order_status" class="form-label">Update Status</label>
                            <select class="form-select" id="order_status" name="order_status" required>
                                <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $order->order_status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tracking_id" class="form-label">Tracking ID</label>
                            <input type="text" class="form-control" id="tracking_id" name="tracking_id" 
                                   value="{{ $order->tracking_id }}" placeholder="Enter tracking ID">
                        </div>
                        
                        <div class="mb-3">
                            <label for="tracking_url" class="form-label">Tracking URL</label>
                            <input type="url" class="form-control" id="tracking_url" name="tracking_url" 
                                   value="{{ $order->tracking_url }}" placeholder="https://example.com/track/...">
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3">{{ $order->notes }}</textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-1"></i>Update Order
                        </button>
                    </form>
                    
                    @if($order->tracking_id)
                    <hr>
                    <div class="text-center">
                        <a href="{{ $order->tracking_url ?? '#' }}" target="_blank" 
                           class="btn btn-outline-success w-100">
                            <i class="fas fa-shipping-fast me-1"></i>Track Shipment
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Order Timeline -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-history me-1"></i>
                    Order Timeline
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item {{ $order->created_at ? 'active' : '' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Order Placed</h6>
                                <small>{{ $order->created_at->format('d M Y, h:i A') }}</small>
                            </div>
                        </div>
                        @if($order->payment_status == 'completed')
                        <div class="timeline-item active">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6>Payment Completed</h6>
                                <small>{{ $order->updated_at->format('d M Y, h:i A') }}</small>
                            </div>
                        </div>
                        @endif
                        @if($order->order_status == 'delivered')
                        <div class="timeline-item active">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6>Delivered</h6>
                                <small>{{ $order->updated_at->format('d M Y, h:i A') }}</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 7px;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -20px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: #6c757d;
    border: 2px solid #fff;
}

.timeline-item.active .timeline-marker {
    background-color: #4e73df;
}

.timeline-content {
    padding-left: 10px;
}
</style>
@endpush
@endsection