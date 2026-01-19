@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">✅ Payment Successful!</h4>
                    <p class="mb-0">Order #{{ $order->order_number }} - ₹{{ number_format($order->total, 2) }}</p>
                </div>
                
                <div class="card-body">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        Your payment of ₹{{ number_format($order->total, 2) }} has been received successfully.
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-shipping-fast"></i>
                        Please provide shipping details to complete your order.
                    </div>
                    
                    <!-- Shipping Form -->
                    <form action="{{ route('orders.update-shipping', $order) }}" method="POST">
                        @csrf
                        
                        <h5 class="mb-3">Shipping Information</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customer_name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="customer_name" 
                                       name="customer_name" value="{{ old('customer_name') }}" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="customer_mobile" class="form-label">Mobile Number *</label>
                                <input type="tel" class="form-control" id="customer_mobile" 
                                       name="customer_mobile" value="{{ old('customer_mobile') }}" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="customer_email" class="form-label">Email Address (Optional)</label>
                            <input type="email" class="form-control" id="customer_email" 
                                   name="customer_email" value="{{ old('customer_email') }}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">Complete Address *</label>
                            <textarea class="form-control" id="shipping_address" 
                                      name="shipping_address" rows="3" required>{{ old('shipping_address') }}</textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="country" class="form-label">Country *</label>
                                <input type="text" class="form-control" id="country" 
                                       name="country" value="India" readonly>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="state" class="form-label">State *</label>
                                <input type="text" class="form-control" id="state" 
                                       name="state" value="{{ old('state') }}" required>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="city" class="form-label">City *</label>
                                <input type="text" class="form-control" id="city" 
                                       name="city" value="{{ old('city') }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="pincode" class="form-label">Pincode *</label>
                                <input type="text" class="form-control" id="pincode" 
                                    name="pincode" value="{{ old('pincode') }}" required maxlength="6">
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-shipping-fast"></i> Complete Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection