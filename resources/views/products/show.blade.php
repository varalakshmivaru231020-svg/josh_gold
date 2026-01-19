@extends('layouts.lawfirm')

@section('title', $product->name . ' - Bengaluru Law House')

@section('content')
    <!-- Page Hero -->
    <section class="page-hero-mini">
        <div class="container">
            <nav aria-label="breadcrumb" class="breadcrumb-wrapper fade-up">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Publications</a></li>
                    <li class="breadcrumb-item active">{{ Str::limit($product->name, 30) }}</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Product Detail Section -->
    <section class="section section-light product-detail-section">
        <div class="container">
            <div class="row g-5">
                <!-- Product Images -->
                <div class="col-lg-6">
                    <div class="product-gallery fade-up">
                        @if($product->images && count($product->images) > 0)
                            <div class="glide product-main-slider">
                                <div class="glide__track" data-glide-el="track">
                                    <ul class="glide__slides">
                                        @foreach($product->images as $image)
                                            <li class="glide__slide">
                                                <div class="main-image-wrapper">
                                                    <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}">
                                                </div>
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
                                <div class="thumbnail-wrapper">
                                    @foreach($product->images as $index => $image)
                                        <div class="thumbnail-item {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}">
                                            <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <div class="no-image-placeholder">
                                <i class="fas fa-book"></i>
                                <span>No Image Available</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Product Details -->
                <div class="col-lg-6">
                    <div class="product-info slide-right">
                        <!-- Product Header -->
                        <div class="product-header">
                            <span class="product-category-label">Legal Publication</span>
                            <h1 class="product-title">{{ $product->name }}</h1>
                        </div>

                        <!-- Price -->
                        <div class="product-price-section">
                            <div class="current-price">{{ number_format($product->price, 2) }}</div>
                            <span class="price-note">Inclusive of all taxes</span>
                        </div>

                        <!-- Description -->
                        <div class="product-description-section">
                            <h5>Description</h5>
                            <div class="description-content">
                                {!! $product->description !!}
                            </div>
                        </div>

                        <!-- Purchase Form -->
                        @if($product->stock > 0)
                            <div class="purchase-form-section">
                                <form id="purchaseForm" action="{{ route('checkout', $product) }}" method="GET">
                                    <!-- Quantity -->
                                    <div class="form-group quantity-group">
                                        <label>Quantity</label>
                                        <div class="quantity-selector">
                                            <button type="button" class="qty-btn" id="decrementQty">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}" readonly>
                                            <button type="button" class="qty-btn" id="incrementQty">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Referral Code -->
                                    <div class="form-group referral-group">
                                        <label>Referral Code (Optional)</label>
                                        <div class="referral-input-wrapper">
                                            <input type="text" class="form-control" id="referral_code" name="referral_code" value="{{ request('ref') }}" placeholder="Enter referral code for discount">
                                            <button class="btn-apply" type="button" id="applyReferral">Apply</button>
                                        </div>
                                        <small id="referralMessage" class="referral-message"></small>
                                    </div>

                                    <!-- Price Summary -->
                                    <div class="price-summary">
                                        <div class="summary-row">
                                            <span>Unit Price</span>
                                            <span><span id="unitPrice">{{ number_format($product->price, 2) }}</span></span>
                                        </div>
                                        <div class="summary-row">
                                            <span>Quantity</span>
                                            <span><span id="selectedQty">1</span> unit(s)</span>
                                        </div>
                                        <div class="summary-row discount-row d-none" id="discountRow">
                                            <span class="text-success">Discount</span>
                                            <span class="text-success">-<span id="discountAmount">0.00</span></span>
                                        </div>
                                        <div class="summary-row total-row">
                                            <span>Total Amount</span>
                                            <span class="total-amount"><span id="totalAmount">{{ number_format($product->price, 2) }}</span></span>
                                        </div>
                                    </div>

                                    <!-- CTA Button -->
                                    <button type="submit" class="btn-checkout">
                                        <i class="fas fa-shopping-cart"></i>
                                        Proceed to Checkout
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="out-of-stock-notice">
                                <i class="fas fa-exclamation-triangle"></i>
                                <div>
                                    <strong>Currently Out of Stock</strong>
                                    <p>This publication is currently unavailable. Please check back later or contact us for updates.</p>
                                </div>
                            </div>
                        @endif

                        <!-- Trust Badges -->
                        <div class="trust-badges">
                            <div class="trust-badge">
                                <i class="fas fa-shield-alt"></i>
                                <span>Secure Payment</span>
                            </div>
                            <div class="trust-badge">
                                <i class="fas fa-truck"></i>
                                <span>Fast Delivery</span>
                            </div>
                            <div class="trust-badge">
                                <i class="fas fa-certificate"></i>
                                <span>Authentic</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <section class="section section-cream">
            <div class="container">
                <div class="section-header">
                    <p class="section-label fade-up">You May Also Like</p>
                    <h2 class="section-title fade-up delay-1">Related Publications</h2>
                    <div class="section-divider fade-up delay-2"></div>
                </div>

                <div class="row g-4">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="publication-card fade-up">
                                <div class="publication-image">
                                    @if($relatedProduct->images && count($relatedProduct->images) > 0)
                                        <img src="{{ asset('storage/' . $relatedProduct->images[0]) }}" alt="{{ $relatedProduct->name }}">
                                    @else
                                        <div class="publication-placeholder">
                                            <i class="fas fa-book"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="publication-content">
                                    <span class="publication-category">Legal Publication</span>
                                    <h5 class="publication-title">{{ $relatedProduct->name }}</h5>
                                    <div class="publication-price">
                                        {{ number_format($relatedProduct->price, 2) }}
                                    </div>
                                    <a href="{{ route('products.show', $relatedProduct) }}" class="btn-view-details">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection

@push('styles')
<style>
    /* Mini Page Hero */
    .page-hero-mini {
        background: var(--navy-deep);
        padding: 120px 0 30px;
    }

    .breadcrumb-wrapper {
        margin: 0;
    }

    .breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
    }

    .breadcrumb-item a {
        color: var(--gold-primary);
        text-decoration: none;
    }

    .breadcrumb-item a:hover {
        color: var(--gold-light);
    }

    .breadcrumb-item.active {
        color: rgba(250, 248, 243, 0.7);
    }

    .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(250, 248, 243, 0.5);
    }

    /* Product Detail Section */
    .product-detail-section {
        padding-top: 4rem;
    }

    /* Product Gallery */
    .product-gallery {
        position: sticky;
        top: 120px;
    }

    .main-image-wrapper {
        background: var(--white);
        border-radius: 8px;
        overflow: hidden;
        box-shadow: var(--shadow-soft);
        padding: 2rem;
    }

    .main-image-wrapper img {
        width: 100%;
        height: 400px;
        object-fit: contain;
    }

    .product-main-slider {
        position: relative;
    }

    .product-main-slider .glide__arrow {
        background: var(--navy-primary);
        color: var(--white);
        width: 45px;
        height: 45px;
        border: none;
        border-radius: 50%;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        transition: var(--transition-fast);
        box-shadow: var(--shadow-medium);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-main-slider .glide__arrow:hover {
        background: var(--gold-primary);
        color: var(--navy-deep);
    }

    .product-main-slider .glide__arrow--left { left: 15px; }
    .product-main-slider .glide__arrow--right { right: 15px; }

    /* Thumbnails */
    .thumbnail-wrapper {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
        justify-content: center;
    }

    .thumbnail-item {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid transparent;
        transition: var(--transition-fast);
        background: var(--white);
        padding: 0.5rem;
    }

    .thumbnail-item:hover,
    .thumbnail-item.active {
        border-color: var(--gold-primary);
    }

    .thumbnail-item img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .no-image-placeholder {
        background: var(--cream-dark);
        border-radius: 8px;
        height: 400px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--navy-muted);
    }

    .no-image-placeholder i {
        font-size: 5rem;
        margin-bottom: 1rem;
        opacity: 0.3;
    }

    /* Product Info */
    .product-info {
        padding-left: 2rem;
    }

    .product-category-label {
        display: inline-block;
        font-size: 0.85rem;
        color: var(--gold-dark);
        text-transform: uppercase;
        letter-spacing: 0.15em;
        margin-bottom: 0.75rem;
        font-weight: 500;
    }

    .product-title {
        font-size: 2.25rem;
        color: var(--navy-deep);
        margin-bottom: 1rem;
        line-height: 1.3;
    }

    /* Price Section */
    .product-price-section {
        background: var(--cream-dark);
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 2rem;
    }

    .current-price {
        font-family: var(--font-heading);
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--navy-deep);
        line-height: 1;
    }

    .current-price::before {
        content: '\20B9';
    }

    .price-note {
        font-size: 0.9rem;
        color: var(--text-muted);
        display: block;
        margin-top: 0.5rem;
    }

    /* Description */
    .product-description-section {
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid rgba(201, 162, 39, 0.15);
    }

    .product-description-section h5 {
        font-size: 1.1rem;
        color: var(--navy-deep);
        margin-bottom: 1rem;
    }

    .description-content {
        color: var(--text-muted);
        line-height: 1.8;
    }

    .description-content img {
        max-width: 100%;
        height: auto;
    }

    /* Purchase Form */
    .purchase-form-section {
        background: var(--white);
        border: 1px solid rgba(201, 162, 39, 0.15);
        border-radius: 8px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: var(--navy-deep);
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
    }

    /* Quantity Selector */
    .quantity-selector {
        display: inline-flex;
        align-items: center;
        border: 1px solid rgba(201, 162, 39, 0.3);
        border-radius: 8px;
        overflow: hidden;
    }

    .qty-btn {
        background: var(--cream-dark);
        border: none;
        padding: 0.75rem 1rem;
        cursor: pointer;
        transition: var(--transition-fast);
        color: var(--navy-primary);
    }

    .qty-btn:hover {
        background: var(--gold-primary);
        color: var(--navy-deep);
    }

    .quantity-selector input {
        width: 60px;
        text-align: center;
        border: none;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--navy-deep);
        background: var(--white);
    }

    /* Referral Input */
    .referral-input-wrapper {
        display: flex;
        gap: 0.5rem;
    }

    .referral-input-wrapper .form-control {
        flex: 1;
        border: 1px solid rgba(201, 162, 39, 0.3);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
    }

    .referral-input-wrapper .form-control:focus {
        border-color: var(--gold-primary);
        outline: none;
        box-shadow: 0 0 0 3px var(--gold-glow);
    }

    .btn-apply {
        background: var(--navy-primary);
        color: var(--white);
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition-fast);
    }

    .btn-apply:hover {
        background: var(--gold-primary);
        color: var(--navy-deep);
    }

    .btn-apply.applied {
        background: #28a745;
    }

    .referral-message {
        display: block;
        margin-top: 0.5rem;
        font-size: 0.85rem;
    }

    .referral-message.success {
        color: #28a745;
    }

    .referral-message.error {
        color: #dc3545;
    }

    /* Price Summary */
    .price-summary {
        background: var(--cream-primary);
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        font-size: 0.95rem;
        color: var(--text-muted);
    }

    .summary-row.total-row {
        border-top: 2px solid var(--gold-primary);
        margin-top: 0.75rem;
        padding-top: 1rem;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--navy-deep);
    }

    .total-amount::before {
        content: '\20B9';
    }

    .discount-row span::before {
        content: '\20B9';
    }

    .summary-row:not(.total-row):not(.discount-row) span:last-child::before {
        content: '\20B9';
    }

    .summary-row.discount-row span:last-child::before {
        content: '';
    }

    /* Checkout Button */
    .btn-checkout {
        width: 100%;
        background: var(--gold-primary);
        color: var(--navy-deep);
        border: none;
        padding: 1rem 2rem;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        cursor: pointer;
        transition: var(--transition-medium);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }

    .btn-checkout:hover {
        background: var(--navy-primary);
        color: var(--white);
        transform: translateY(-2px);
        box-shadow: var(--shadow-medium);
    }

    /* Out of Stock Notice */
    .out-of-stock-notice {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        background: rgba(220, 53, 69, 0.1);
        border: 1px solid rgba(220, 53, 69, 0.2);
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .out-of-stock-notice i {
        color: #dc3545;
        font-size: 1.5rem;
        margin-top: 0.25rem;
    }

    .out-of-stock-notice strong {
        display: block;
        color: #dc3545;
        margin-bottom: 0.5rem;
    }

    .out-of-stock-notice p {
        margin: 0;
        color: var(--text-muted);
        font-size: 0.95rem;
    }

    /* Trust Badges */
    .trust-badges {
        display: flex;
        gap: 1.5rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .trust-badge {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    .trust-badge i {
        color: var(--gold-primary);
        font-size: 1.1rem;
    }

    /* Related Products (reusing existing styles) */
    .publication-card {
        background: var(--white);
        border-radius: 8px;
        overflow: hidden;
        box-shadow: var(--shadow-soft);
        transition: var(--transition-medium);
        height: 100%;
    }

    .publication-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-medium);
    }

    .publication-image {
        position: relative;
        height: 200px;
        overflow: hidden;
        background: var(--cream-dark);
    }

    .publication-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition-slow);
    }

    .publication-card:hover .publication-image img {
        transform: scale(1.05);
    }

    .publication-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .publication-placeholder i {
        font-size: 3rem;
        color: var(--navy-muted);
        opacity: 0.3;
    }

    .publication-content {
        padding: 1.5rem;
    }

    .publication-category {
        font-size: 0.8rem;
        color: var(--gold-dark);
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 0.5rem;
        display: block;
    }

    .publication-title {
        font-size: 1.1rem;
        color: var(--navy-deep);
        margin-bottom: 0.75rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .publication-price {
        font-family: var(--font-heading);
        font-size: 1.5rem;
        color: var(--navy-primary);
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .publication-price::before {
        content: '\20B9';
    }

    .btn-view-details {
        width: 100%;
        background: var(--navy-primary);
        color: var(--white);
        padding: 0.75rem;
        border: none;
        border-radius: 4px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        transition: var(--transition-medium);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-view-details:hover {
        background: var(--gold-primary);
        color: var(--navy-deep);
    }

    /* Responsive */
    @media (max-width: 991px) {
        .product-gallery {
            position: relative;
            top: 0;
            margin-bottom: 2rem;
        }

        .product-info {
            padding-left: 0;
        }

        .product-title {
            font-size: 1.75rem;
        }
    }

    @media (max-width: 767px) {
        .page-hero-mini {
            padding: 100px 0 20px;
        }

        .main-image-wrapper img {
            height: 300px;
        }

        .thumbnail-item {
            width: 60px;
            height: 60px;
        }

        .trust-badges {
            flex-direction: column;
            align-items: center;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Main Slider
    if (document.querySelector('.product-main-slider')) {
        const mainSlider = new Glide('.product-main-slider', {
            type: 'carousel',
            perView: 1,
            autoplay: false
        }).mount();

        // Thumbnail click handler
        document.querySelectorAll('.thumbnail-item').forEach(function(thumb, index) {
            thumb.addEventListener('click', function() {
                mainSlider.go('=' + index);
                document.querySelectorAll('.thumbnail-item').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Update active thumbnail on slide change
        mainSlider.on('run', function() {
            const index = mainSlider.index;
            document.querySelectorAll('.thumbnail-item').forEach((t, i) => {
                t.classList.toggle('active', i === index);
            });
        });
    }
});

$(document).ready(function () {
    const quantityInput = $('#quantity');
    const unitPrice = parseFloat({{ $product->price }});

    let referralApplied = false;
    let discountPerUnit = 25;

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

    $('#applyReferral').click(function () {
        const referralCode = $('#referral_code').val().trim();
        const messageBox = $('#referralMessage');

        if (referralCode === '') {
            messageBox.removeClass().addClass('referral-message error').text('Please enter a referral code.');
            return;
        }

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
                    messageBox.removeClass().addClass('referral-message success').text(response.message);
                    $('#applyReferral').text('Applied').addClass('applied');
                    updatePrice();
                } else {
                    referralApplied = false;
                    messageBox.removeClass().addClass('referral-message error').text(response.message);
                    $('#applyReferral').prop('disabled', false).text('Apply');
                    updatePrice();
                }
            },
            error: function () {
                referralApplied = false;
                messageBox.removeClass().addClass('referral-message error').text('Something went wrong. Please try again.');
                $('#applyReferral').prop('disabled', false).text('Apply');
                updatePrice();
            }
        });
    });

    $('#referral_code').on('input', function () {
        referralApplied = false;
        $('#discountRow').addClass('d-none');
        $('#discountAmount').text('0.00');
        $('#applyReferral').prop('disabled', false).removeClass('applied').text('Apply');
        $('#referralMessage').text('');
        updatePrice();
    });

    updatePrice();
});
</script>
@endpush
