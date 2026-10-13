@extends('layouts.lawfirm')

@section('title', 'Shipping Details - JOSHA GOLD AND DIAMOND JEWELLERY')

@section('content')
<section class="shipping-section">
    <div class="jb-wrap">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="shipping-card">
                    <div class="shipping-header">
                        <h1 class="shipping-title"><i class="fas fa-check-circle me-2"></i>Payment Successful!</h1>
                        <p class="shipping-sub">Order #{{ $order->order_number }} - ₹{{ number_format($order->total, 2) }}</p>
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
.shipping-section { background: var(--jb-ground); padding: 40px 0 56px; }

.shipping-card { overflow: hidden; border: 1px solid var(--jb-line); border-radius: var(--jb-r-card); background: var(--jb-panel); box-shadow: 0 16px 36px -14px rgba(0, 0, 0, .9); }

/* Compact heading, in the page-head style */
.shipping-header { padding: 22px 28px; border-bottom: 1px solid var(--jb-line); background:
    radial-gradient(600px 220px at 100% 0%, rgba(201, 163, 92, .16), transparent 65%), var(--jb-black); }
.shipping-title { margin: 0 0 4px; color: var(--jb-white); font: 600 clamp(21px, 2.6vw, 26px)/1.25 var(--jb-serif); }
.shipping-title i { color: var(--jb-up); font-size: .8em; }
.shipping-sub { margin: 0; color: var(--jb-gold-light); font-weight: 600; font-variant-numeric: tabular-nums; }

.shipping-body { padding: 28px; }

.alert-success-custom { padding: 1rem 1.25rem; border: 1px solid rgba(92, 227, 168, .3); border-radius: var(--jb-r-ctl); background: rgba(92, 227, 168, .08); color: #c9f5e1; }
.alert-success-custom i { color: var(--jb-up); }

.alert-info-custom { padding: 1rem 1.25rem; border: 1px solid var(--jb-line); border-radius: var(--jb-r-ctl); background: rgba(201, 163, 92, .06); color: var(--jb-text); }
.alert-info-custom i { color: var(--jb-gold-light); }

.form-section-title { padding-bottom: .75rem; border-bottom: 1px solid var(--jb-line); color: var(--jb-white); font: 600 18px/1.3 var(--jb-serif); }
.form-section-title i { color: var(--jb-gold-light); font-size: .85em; }

/* Inputs: dark fields from bullion-pages, with a little more room */
.shipping-body .form-control { padding: .75rem 1rem; background-color: var(--jb-black); }
.shipping-body .form-control:focus { background-color: var(--jb-panel-2); }
.shipping-body .form-control[readonly] { background-color: var(--jb-panel-2); color: var(--jb-muted); }
.shipping-body .form-label { color: var(--jb-text); }

.btn-primary-law { display: inline-flex; align-items: center; justify-content: center; border: 0; border-radius: var(--jb-r-btn); background: var(--jb-champagne); color: #111; font-weight: 700; cursor: pointer; box-shadow: 0 10px 28px -12px rgba(234, 215, 166, .45); transition: background-color .15s, transform .15s; }
.btn-primary-law:hover { background: #fff8ea; }
.btn-primary-law:active { background: var(--jb-champagne-down); transform: translateY(1px); }
.btn-primary-law.btn-lg { min-height: 52px; padding: 0 2rem; font-size: 1.05rem; }

@media (max-width: 767px) {
    .shipping-section { padding: 24px 0 40px; }
    .shipping-header { padding: 18px 20px; }
    .shipping-body { padding: 20px; }
    .btn-primary-law.btn-lg { width: 100%; padding: 0 1rem; }
}
</style>
@endpush