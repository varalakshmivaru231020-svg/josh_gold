@extends('layouts.lawfirm')

@section('title', 'Checkout - JOSHA GOLD AND DIAMOND JEWELLERY')

@section('content')
<section class="checkout-section">
    <div class="jb-wrap">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="checkout-card">
                    <div class="checkout-header">
                        <h1 class="checkout-title"><i class="fas fa-credit-card me-2"></i>Complete Your Payment</h1>
                    </div>

                    <div class="checkout-body">
                        <!-- Order Summary -->
                        <div class="order-summary mb-4">
                            <h5 class="summary-title"><i class="fas fa-receipt me-2"></i>Order Summary</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Product:</strong> {{ $product->name }}</p>
                                    <p><strong>Quantity:</strong> {{ $quantity }}</p>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <p><strong>Subtotal:</strong> ₹{{ number_format($subtotal, 2) }}</p>
                                    @if($discount > 0)
                                        <p class="text-success"><strong>Discount (10%):</strong> -₹{{ number_format($discount, 2) }}</p>
                                    @endif
                                    <h4 class="total-amount">Total: ₹{{ number_format($total, 2) }}</h4>
                                </div>
                            </div>
                        </div>

                        <!-- Loading State -->
                        <div id="loading-state" class="text-center py-4">
                            <div class="spinner-border text-gold" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-3 text-muted">Preparing payment gateway...</p>
                        </div>

                        <!-- Payment Status Messages -->
                        <div id="payment-status" class="text-center" style="display: none;">
                            <!-- Will show success/error messages -->
                        </div>

                        <div class="alert alert-info-custom mt-4">
                            <i class="fas fa-info-circle me-2"></i>
                            Shipping address will be collected after successful payment.
                        </div>

                        <!-- Fallback payment button (if auto-trigger fails) -->
                        <div class="text-center mt-4" id="fallback-button" style="display: none;">
                            <button type="button" id="manual-pay-button" class="btn-primary-law btn-lg">
                                <i class="fas fa-lock me-2"></i>Pay Now ₹{{ number_format($total, 2) }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .checkout-section { background: var(--jb-ground); padding: 40px 0 56px; }

    .checkout-card { overflow: hidden; border: 1px solid var(--jb-line); border-radius: var(--jb-r-card); background: var(--jb-panel); box-shadow: 0 16px 36px -14px rgba(0, 0, 0, .9); }

    /* Compact heading, in the page-head style */
    .checkout-header { padding: 22px 28px; border-bottom: 1px solid var(--jb-line); background:
        radial-gradient(600px 220px at 100% 0%, rgba(201, 163, 92, .16), transparent 65%), var(--jb-black); }
    .checkout-title { margin: 0; color: var(--jb-white); font: 600 clamp(21px, 2.6vw, 26px)/1.25 var(--jb-serif); }
    .checkout-title i { color: var(--jb-gold-light); font-size: .8em; }

    .checkout-body { padding: 28px; }

    .order-summary { padding: 20px 22px; border: 1px solid var(--jb-line); border-radius: 14px; background: linear-gradient(180deg, rgba(201, 163, 92, .06), transparent 70%), var(--jb-panel-2); }
    .summary-title { margin-bottom: 1rem; padding-bottom: .75rem; border-bottom: 1px solid var(--jb-line); color: var(--jb-white); font: 600 18px/1.3 var(--jb-serif); }
    .summary-title i { color: var(--jb-gold-light); font-size: .85em; }
    .order-summary p { margin-bottom: .5rem; color: var(--jb-text); }
    .order-summary p strong { color: var(--jb-muted); font-weight: 600; }
    .order-summary .text-success, .order-summary .text-success strong { color: var(--jb-up) !important; }

    .total-amount { margin: .75rem 0 0; color: var(--jb-gold-light); font: 600 1.5rem/1.2 var(--jb-serif); font-variant-numeric: tabular-nums; }

    .spinner-border.text-gold { width: 3rem; height: 3rem; color: var(--jb-gold) !important; }

    .alert-info-custom { padding: 1rem 1.25rem; border: 1px solid var(--jb-line); border-radius: var(--jb-r-ctl); background: rgba(201, 163, 92, .06); color: var(--jb-text); }
    .alert-info-custom i { color: var(--jb-gold-light); }

    .btn-primary-law { display: inline-flex; align-items: center; justify-content: center; border: 0; border-radius: var(--jb-r-btn); background: var(--jb-champagne); color: #111; font-weight: 700; cursor: pointer; box-shadow: 0 10px 28px -12px rgba(234, 215, 166, .45); transition: background-color .15s, transform .15s; }
    .btn-primary-law:hover { background: #fff8ea; }
    .btn-primary-law:active { background: var(--jb-champagne-down); transform: translateY(1px); }
    .btn-primary-law.btn-lg { min-height: 52px; padding: 0 2rem; font-size: 1.05rem; }

    #payment-status .alert { border-radius: var(--jb-r-ctl); }

    @media (max-width: 767px) {
        .checkout-section { padding: 24px 0 40px; }
        .checkout-header { padding: 18px 20px; }
        .checkout-body { padding: 20px; }
        .order-summary { padding: 16px 18px; }
        .btn-primary-law.btn-lg { width: 100%; padding: 0 1rem; }
    }
</style>
@endpush

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-trigger payment on page load
        initiatePayment();

        // Fallback manual payment button
        document.getElementById('manual-pay-button')?.addEventListener('click', function() {
            initiatePayment();
        });
    });

    function initiatePayment() {
        // Show loading, hide fallback button
        document.getElementById('loading-state').style.display = 'block';
        document.getElementById('fallback-button').style.display = 'none';

        // Create Razorpay order
        fetch('{{ route("orders.create-payment") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                _token: '{{ csrf_token() }}'
            })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('loading-state').style.display = 'none';
            console.log("data is",data);

            if(data.success) {
                var options = {
                    "key": "{{ env('RAZORPAY_KEY_ID') }}",
                    "amount": data.amount,
                    "currency": "INR",
                    "name": data.name,
                    "description": data.description,
                    "order_id": data.order_id,
                    "handler": function(response){
                        handlePaymentSuccess(response);
                    },
                    "prefill": {
                        // You can prefill with empty values
                        "name": "",
                        "email": "",
                        "contact": ""
                    },
                    "theme": data.theme || { "color": "#C9A227" },
                    "modal": {
                        "ondismiss": function(){
                            // Show fallback button if user closes modal
                            showFallbackButton();
                        }
                    },
                    "remember_customer": false
                };

                var rzp = new Razorpay(options);
                rzp.open();
            } else {
                showError('Payment initialization failed: ' + (data.message || 'Please try again'));
            }
        })
        .catch(error => {
            document.getElementById('loading-state').style.display = 'none';
            console.error('Error:', error);
            showError('Something went wrong. Please try again.');
        });
    }

    function handlePaymentSuccess(response) {
        // Show processing message
        document.getElementById('payment-status').innerHTML = `
            <div class="spinner-border text-gold" role="status">
                <span class="visually-hidden">Processing...</span>
            </div>
            <p class="mt-3">Verifying your payment...</p>
        `;
        document.getElementById('payment-status').style.display = 'block';

        // Verify payment on server
        fetch('{{ route("orders.verify-payment") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(response)
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                // Show success and redirect
                document.getElementById('payment-status').innerHTML = `
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        Payment successful! Redirecting...
                    </div>
                `;

                // Redirect to shipping info page
                setTimeout(() => {
                    window.location.href = data.redirect_url;
                }, 1000);
            } else {
                showError('Payment verification failed: ' + (data.message || 'Please contact support'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Payment verification failed. Please contact support.');
        });
    }

    function showError(message) {
        document.getElementById('payment-status').innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                ${message}
            </div>
        `;
        document.getElementById('payment-status').style.display = 'block';
        showFallbackButton();
    }

    function showFallbackButton() {
        document.getElementById('fallback-button').style.display = 'block';
    }
</script>
@endpush