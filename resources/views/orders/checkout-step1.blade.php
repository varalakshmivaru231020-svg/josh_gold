@extends('layouts.lawfirm')

@section('title', 'Checkout - Bengaluru Law House')

@section('content')
<section class="checkout-section">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="checkout-card">
                    <div class="checkout-header">
                        <h4 class="mb-0"><i class="fas fa-credit-card me-2"></i>Complete Your Payment</h4>
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
    .checkout-section {
        margin-top: 80px;
        min-height: calc(100vh - 80px);
        background: var(--cream-primary);
        padding: 40px 0;
    }

    .checkout-card {
        background: var(--white);
        border-radius: 12px;
        box-shadow: var(--shadow-medium);
        overflow: hidden;
    }

    .checkout-header {
        background: var(--navy-deep);
        color: var(--cream-primary);
        padding: 1.5rem 2rem;
        border-bottom: 3px solid var(--gold-primary);
    }

    .checkout-header h4 {
        color: var(--cream-primary);
        font-family: var(--font-heading);
    }

    .checkout-body {
        padding: 2rem;
    }

    .order-summary {
        background: var(--cream-primary);
        padding: 1.5rem;
        border-radius: 8px;
        border: 1px solid rgba(201, 162, 39, 0.2);
    }

    .summary-title {
        color: var(--navy-deep);
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--gold-primary);
    }

    .total-amount {
        color: var(--navy-deep);
        font-weight: 700;
        margin-top: 0.5rem;
    }

    .text-gold {
        color: var(--gold-primary) !important;
    }

    .spinner-border.text-gold {
        color: var(--gold-primary);
        width: 3rem;
        height: 3rem;
    }

    .alert-info-custom {
        background: rgba(26, 35, 50, 0.05);
        border: 1px solid rgba(26, 35, 50, 0.1);
        border-radius: 8px;
        color: var(--navy-deep);
        padding: 1rem 1.25rem;
    }

    .btn-primary-law.btn-lg {
        padding: 1rem 2.5rem;
        font-size: 1.1rem;
    }

    #payment-status .alert {
        border-radius: 8px;
    }

    @media (max-width: 767px) {
        .checkout-section {
            margin-top: 70px;
            padding: 20px 0;
        }

        .checkout-body {
            padding: 1.5rem;
        }
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