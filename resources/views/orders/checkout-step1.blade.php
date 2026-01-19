@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white border-bottom">
                    <h4 class="mb-0">Complete Your Payment</h4>
                </div>
                
                <div class="card-body">
                    <!-- Order Summary -->
                    <div class="mb-4">
                        <h5>Order Summary</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Product:</strong> {{ $product->name }}</p>
                                <p><strong>Quantity:</strong> {{ $quantity }}</p>
                            </div>
                            <div class="col-md-6 text-end">
                                <p><strong>Subtotal:</strong> ₹{{ number_format($subtotal, 2) }}</p>
                                @if($discount > 0)
                                    <p><strong>Discount (10%):</strong> -₹{{ number_format($discount, 2) }}</p>
                                @endif
                                <h4>Total: ₹{{ number_format($total, 2) }}</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Loading State -->
                    <div id="loading-state" class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3">Preparing payment gateway...</p>
                    </div>

                    <!-- Payment Status Messages -->
                    <div id="payment-status" class="text-center" style="display: none;">
                        <!-- Will show success/error messages -->
                    </div>

                    <div class="alert alert-info mt-4">
                        <i class="fas fa-info-circle"></i> 
                        Shipping address will be collected after successful payment.
                    </div>

                    <!-- Fallback payment button (if auto-trigger fails) -->
                    <div class="text-center mt-4" id="fallback-button" style="display: none;">
                        <button type="button" id="manual-pay-button" class="btn btn-primary btn-lg">
                            Pay Now ₹{{ number_format($total, 2) }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Razorpay Integration -->
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
                    "theme": data.theme || { "color": "#F37254" },
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
            <div class="spinner-border text-success" role="status">
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

<style>
    #loading-state .spinner-border {
        width: 3rem;
        height: 3rem;
    }
    .alert {
        border-radius: 8px;
    }
</style>
@endsection