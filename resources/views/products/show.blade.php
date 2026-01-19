@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>
    
    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-body p-0">
                    @if($product->images && count($product->images) > 0)
                    <div class="glide product-main-slider">
                        <div class="glide__track" data-glide-el="track">
                            <ul class="glide__slides">
                                @foreach($product->images as $image)
                                <li class="glide__slide">
                                    <img src="{{ asset('storage/' . $image) }}" 
                                         class="img-fluid rounded" 
                                         alt="{{ $product->name }}">
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @if(count($product->images) > 1)
                        <div class="glide__arrows" data-glide-el="controls">
                            <button class="glide__arrow glide__arrow--left" data-glide-dir="<">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="glide__arrow glide__arrow--right" data-glide-dir=">">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Thumbnail Navigation -->
                    @if(count($product->images) > 1)
                    <div class="glide product-thumbnail-slider mt-3">
                        <div class="glide__track" data-glide-el="track">
                            <ul class="glide__slides">
                                @foreach($product->images as $image)
                                <li class="glide__slide">
                                    <img src="{{ asset('storage/' . $image) }}" 
                                         class="img-thumbnail" 
                                         alt="{{ $product->name }}"
                                         style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;">
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif
                    @else
                    <div class="text-center py-5 bg-light rounded">
                        <i class="fas fa-image fa-5x text-muted mb-3"></i>
                        <p class="text-muted">No images available</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Product Details -->
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-body">
                    <!-- Product Header -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h1 class="h2 mb-1">{{ $product->name }}</h1>
                            @if($product->stock > 0)
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>In Stock
                            </span>
                            @else
                            <span class="badge bg-danger">
                                <i class="fas fa-times-circle me-1"></i>Out of Stock
                            </span>
                            @endif
                        </div>
                        <div class="text-end">
                            <div class="display-4 text-primary mb-0">₹{{ number_format($product->price, 2) }}</div>
                            <small class="text-muted">Including taxes</small>
                        </div>
                    </div>
                    
                    <!-- Stock Info -->
                    <!-- <div class="alert alert-info mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-box me-2"></i>
                                <strong>Stock Available:</strong> {{ $product->stock }} units
                            </div>
                            @if($product->stock <= 10 && $product->stock > 0)
                            <span class="badge bg-warning">Low Stock</span>
                            @endif
                        </div>
                    </div> -->
                    
                    <!-- Referral Discount -->
                    
                    <!-- Product Description -->
                    <div class="mb-4">
                        <h5 class="mb-3">Description</h5>
                        <div class="product-description">
                            {!! $product->description !!}
                        </div>
                    </div>
                    
                    <!-- Purchase Form -->
                    @if($product->stock > 0)
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h5 class="mb-3">Purchase Details</h5>
                            <form id="purchaseForm" action="{{ route('checkout', $product) }}" method="GET">
                                <!-- Quantity Selector -->
                                <div class="mb-4">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <div class="d-flex align-items-center">
                                        <button type="button" class="btn btn-outline-secondary" id="decrementQty">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number" 
                                               id="quantity" 
                                               name="quantity" 
                                               value="1" 
                                               min="1" 
                                               max="{{ $product->stock }}"
                                               class="form-control text-center mx-2" 
                                               style="width: 80px;">
                                        <button type="button" class="btn btn-outline-secondary" id="incrementQty">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <!-- <span class="ms-3 text-muted">Max: {{ $product->stock }}</span> -->
                                    </div>
                                </div>

                               @if(request()->has('ref'))
                                <div class="alert alert-success mb-4">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-tag fa-2x me-3"></i>
                                        <div>
                                            <h5 class="mb-1">Discount Available!</h5>
                                            <p class="mb-0">Use referral code <code>{{ request('ref') }}</code> to get discount on this product.</p>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="alert alert-warning mb-4">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Have a referral code? Apply it at checkout to get discount!
                                </div>
                                @endif
                                            
                                <!-- Referral Code -->
                                <div class="mb-4">
                                    <label for="referral_code" class="form-label">Referral Code (Optional)</label>

                                    <div class="input-group">
                                        <input type="text"
                                               class="form-control"
                                               id="referral_code"
                                               name="referral_code"
                                               value="{{ request('ref') }}"
                                               placeholder="Enter referral code for discount">

                                        <button class="btn btn-outline-secondary" type="button" id="applyReferral">
                                            Apply
                                        </button>
                                    </div>

                                    <!-- Message -->
                                    <small id="referralMessage" class="d-block mt-2"></small>
                                </div>

                                
                                <!-- Price Summary -->
                                <div class="mb-4">
                                    <h6>Price Summary</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td>Unit Price:</td>
                                            <td class="text-end">₹<span id="unitPrice">{{ number_format($product->price, 2) }}</span></td>
                                        </tr>
                                        <tr>
                                            <td>Quantity:</td>
                                            <td class="text-end"><span id="selectedQty">1</span> units</td>
                                        </tr>
                                        <tr id="discountRow" class="d-none">
                                            <td class="text-success">Discount:</td>
                                            <td class="text-end text-success">-₹<span id="discountAmount">0.00</span></td>
                                        </tr>
                                        <tr class="table-primary">
                                            <td><strong>Total Amount:</strong></td>
                                            <td class="text-end"><strong>₹<span id="totalAmount">{{ number_format($product->price, 2) }}</span></strong></td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-shopping-cart me-2"></i>Proceed to Checkout
                                    </button>
                                    <!-- <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                                    </a> -->
                                </div>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        This product is currently out of stock. Please check back later.
                    </div>
                    @endif
                    
                    <!-- Become Agent CTA -->
                    <!-- <div class="text-center mt-4">
                        <div class="card bg-gradient-info text-white">
                            <div class="card-body">
                                <h5 class="mb-2">Want to earn commissions?</h5>
                                <p class="mb-3">Become an agent and earn 5% on every sale using your referral code.</p>
                                <a href="{{ route('agent.register') }}" class="btn btn-light">
                                    <i class="fas fa-user-plus me-2"></i>Become an Agent
                                </a>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    <!-- @if($relatedProducts->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="mb-4">Related Products</h3>
            <div class="row g-4">
                @foreach($relatedProducts as $relatedProduct)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card product-card h-100">
                        @if($relatedProduct->images && count($relatedProduct->images) > 0)
                        <img src="{{ asset('storage/' . $relatedProduct->images[0]) }}" 
                             class="card-img-top product-img" 
                             alt="{{ $relatedProduct->name }}">
                        @else
                        <div class="card-img-top product-img bg-light d-flex align-items-center justify-content-center">
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $relatedProduct->name }}</h5>
                            <p class="card-text text-muted small mb-3">
                                {{ Str::limit(strip_tags($relatedProduct->description), 60) }}
                            </p>
                            
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="text-primary mb-0">₹{{ number_format($relatedProduct->price, 2) }}</h5>
                                    @if($relatedProduct->stock > 0)
                                    <span class="badge bg-success">In Stock</span>
                                    @endif
                                </div>
                                
                                <a href="{{ route('products.show', $relatedProduct) }}" 
                                   class="btn btn-outline-primary w-100">
                                    <i class="fas fa-eye me-2"></i>View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif -->
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@glidejs/glide/dist/css/glide.core.min.css">
<style>
.product-main-slider .glide__slide img {
    width: 100%;
    height: 400px;
    object-fit: contain;
}

.product-thumbnail-slider .glide__slide img {
    transition: border-color 0.3s;
}

.product-thumbnail-slider .glide__slide img:hover,
.product-thumbnail-slider .glide__slide.glide__slide--active img {
    border-color: #4e73df;
}

.glide__arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.8);
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #333;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.glide__arrow--left {
    left: 10px;
}

.glide__arrow--right {
    right: 10px;
}

.product-description {
    line-height: 1.8;
}

.product-description img {
    max-width: 100%;
    height: auto;
}
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@glidejs/glide"></script>

<script>
$(document).ready(function () {

    /* -----------------------------
       SLIDERS (UNCHANGED)
    ------------------------------*/
    if ($('.product-main-slider').length) {
        new Glide('.product-main-slider', {
            type: 'carousel',
            perView: 1,
            autoplay: 3000,
            hoverpause: true
        }).mount();
    }

    /* -----------------------------
       PRICE & REFERRAL LOGIC
    ------------------------------*/
    const quantityInput = $('#quantity');
    const unitPrice = parseFloat({{ $product->price }});

    let referralApplied = false;
    let discountPerUnit = 25; // FIXED DISCOUNT (₹25 per item)

    function updatePrice() {
        const quantity = parseInt(quantityInput.val()) || 1;
        const subtotal = unitPrice * quantity;

        let discount = 0;
        let total = subtotal;

        if (referralApplied) {
            discount = discountPerUnit * quantity;
            total = subtotal - discount;

            $('#discountRow').removeClass('d-none');
            $('#discountAmount').text(discount.toFixed(2));
        } else {
            $('#discountRow').addClass('d-none');
            $('#discountAmount').text('0.00');
        }

        $('#selectedQty').text(quantity);
        $('#totalAmount').text(total.toFixed(2));
    }

    /* -----------------------------
       QUANTITY HANDLERS
    ------------------------------*/
    $('#incrementQty').click(function () {
        let v = parseInt(quantityInput.val());
        const max = parseInt(quantityInput.attr('max'));
        if (v < max) {
            quantityInput.val(v + 1);
            updatePrice();
        }
    });

    $('#decrementQty').click(function () {
        let v = parseInt(quantityInput.val());
        if (v > 1) {
            quantityInput.val(v - 1);
            updatePrice();
        }
    });

    quantityInput.on('input', updatePrice);

    /* -----------------------------
       APPLY REFERRAL (AJAX)
    ------------------------------*/
    $('#applyReferral').click(function () {

        const referralCode = $('#referral_code').val().trim();
        const messageBox = $('#referralMessage');

        if (referralCode === '') {
            messageBox
                .removeClass()
                .addClass('text-danger')
                .text('Please enter a referral code.');
            return;
        }

        // Disable button during request
        $('#applyReferral').prop('disabled', true).text('Checking...');

        $.ajax({
            url: "{{ route('checkReferral') }}",
            type: "POST",
            data: {
                referral_code: referralCode,
                _token: "{{ csrf_token() }}"
            },
            success: function (response) {
                if (response.success) {
                    referralApplied = true;

                    messageBox
                        .removeClass()
                        .addClass('text-success')
                        .text(response.message);

                    $('#applyReferral')
                        .text('Applied')
                        .removeClass('btn-outline-secondary')
                        .addClass('btn-success');

                    updatePrice();
                } else {
                    referralApplied = false;

                    messageBox
                        .removeClass()
                        .addClass('text-danger')
                        .text(response.message);

                    $('#applyReferral')
                        .prop('disabled', false)
                        .text('Apply');

                    updatePrice();
                }
            },
            error: function () {
                referralApplied = false;

                messageBox
                    .removeClass()
                    .addClass('text-danger')
                    .text('Something went wrong. Please try again.');

                $('#applyReferral')
                    .prop('disabled', false)
                    .text('Apply');

                updatePrice();
            }
        });
    });

    /* -----------------------------
       RESET REFERRAL IF CODE CHANGES
    ------------------------------*/
    $('#referral_code').on('input', function () {
        referralApplied = false;

        $('#discountRow').addClass('d-none');
        $('#discountAmount').text('0.00');

        $('#applyReferral')
            .prop('disabled', false)
            .removeClass('btn-success')
            .addClass('btn-outline-secondary')
            .text('Apply');

        $('#referralMessage').text('');
        updatePrice();
    });

    updatePrice();
});
</script>

@endpush
@endsection