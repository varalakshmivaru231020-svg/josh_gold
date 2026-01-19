@extends('layouts.lawfirm')

@section('title', 'Shipping Details - Bengaluru Law House')

@section('content')
<section class="shipping-section">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="shipping-card">
                    <div class="shipping-header">
                        <h4 class="mb-1"><i class="fas fa-check-circle me-2"></i>Payment Successful!</h4>
                        <p class="mb-0">Order #{{ $order->order_number }} - ₹{{ number_format($order->total, 2) }}</p>
                    </div>

                    <div class="shipping-body">
                        <div class="alert alert-success-custom mb-4">
                            <i class="fas fa-check-circle me-2"></i>
                            Your payment of ₹{{ number_format($order->total, 2) }} has been received successfully.
                        </div>

                        <div class="alert alert-info-custom mb-4">
                            <i class="fas fa-shipping-fast me-2"></i>
                            Please provide shipping details to complete your order.
                        </div>

                        <!-- Shipping Form -->
                        <form action="{{ route('orders.update-shipping', $order) }}" method="POST">
                            @csrf

                            <h5 class="form-section-title mb-3">
                                <i class="fas fa-map-marker-alt me-2"></i>Shipping Information
                            </h5>

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
                                <button type="submit" class="btn-primary-law btn-lg">
                                    <i class="fas fa-shipping-fast me-2"></i>Complete Order
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.shipping-section {
    margin-top: 80px;
    min-height: calc(100vh - 80px);
    background: var(--cream-primary);
    padding: 40px 0;
}

.shipping-card {
    background: var(--white);
    border-radius: 12px;
    box-shadow: var(--shadow-medium);
    overflow: hidden;
}

.shipping-header {
    background: linear-gradient(135deg, #28a745 0%, #218838 100%);
    color: white;
    padding: 1.5rem 2rem;
    border-bottom: 3px solid var(--gold-primary);
}

.shipping-header h4 {
    color: white;
    font-family: var(--font-heading);
}

.shipping-body {
    padding: 2rem;
}

.alert-success-custom {
    background: rgba(40, 167, 69, 0.1);
    border: 1px solid rgba(40, 167, 69, 0.3);
    border-radius: 8px;
    color: #155724;
    padding: 1rem 1.25rem;
}

.alert-info-custom {
    background: rgba(26, 35, 50, 0.05);
    border: 1px solid rgba(26, 35, 50, 0.1);
    border-radius: 8px;
    color: var(--navy-deep);
    padding: 1rem 1.25rem;
}

.form-section-title {
    color: var(--navy-deep);
    font-weight: 600;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid var(--gold-primary);
}

.shipping-body .form-control {
    border: 2px solid rgba(201, 162, 39, 0.2);
    border-radius: 8px;
    padding: 0.75rem 1rem;
}

.shipping-body .form-control:focus {
    border-color: var(--gold-primary);
    box-shadow: 0 0 0 0.2rem rgba(201, 162, 39, 0.15);
}

.shipping-body .form-label {
    color: var(--navy-deep);
    font-weight: 500;
}

.btn-primary-law.btn-lg {
    padding: 1rem 2.5rem;
    font-size: 1.1rem;
}

@media (max-width: 767px) {
    .shipping-section {
        margin-top: 70px;
        padding: 20px 0;
    }

    .shipping-body {
        padding: 1.5rem;
    }
}
</style>
@endpush