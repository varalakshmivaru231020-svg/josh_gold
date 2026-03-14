@extends('layouts.lawfirm')

@section('title', $product->name . ' | Bengaluru Law House')

@section('content')
<section class="product-detail-page">

    {{-- Hero Banner --}}
    <div class="product-hero-header">
        <div class="product-hero-deco" aria-hidden="true"><i class="fas fa-book-open"></i></div>
        <div class="container" data-aos="fade-up">
            <nav class="product-breadcrumb mb-3">
                <a href="{{ route('home') }}">Home</a>
                <span class="sep">/</span>
                <a href="{{ route('products.index') }}">Publications</a>
                <span class="sep">/</span>
                <span class="curr">{{ Str::limit($product->name, 40) }}</span>
            </nav>
            <div class="label-gold mb-2">Legal Publication</div>
            <h1 class="heading-premium text-white">{{ $product->name }}</h1>
            <div class="accent-line-gold"></div>
        </div>
    </div>

    <div class="container product-content-container">
        <div class="product-main-card" data-aos="fade-up">
            <div class="row g-0">
                <!-- Product Gallery -->
                <div class="col-lg-6">
                    <div class="product-gallery-premium p-5 border-end">
                        @if($product->images && count($product->images) > 0)
                            <div class="glide product-main-slider">
                                <div class="glide__track" data-glide-el="track">
                                    <ul class="glide__slides">
                                        @foreach($product->images as $image)
                                            <li class="glide__slide">
                                                <div class="main-image-wrap">
                                                    <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}">
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @if(count($product->images) > 1)
                                    <div class="glide__arrows" data-glide-el="controls">
                                        <button class="glide-nav prev" data-glide-dir="<"><i class="fas fa-chevron-left"></i></button>
                                        <button class="glide-nav next" data-glide-dir=">"><i class="fas fa-chevron-right"></i></button>
                                    </div>
                                @endif
                            </div>

                            @if(count($product->images) > 1)
                                <div class="thumb-nav-flex mt-4">
                                    @foreach($product->images as $index => $image)
                                        <div class="thumb-nav-item {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}">
                                            <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <div class="product-no-image">
                                <i class="fas fa-book-open fa-5x opacity-25"></i>
                                <span class="d-block mt-3 opacity-50">Visual Archive Pending</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Product Details -->
                <div class="col-lg-6">
                    <div class="product-details-premium p-5">
                        <div class="label-gold mb-3">Legal Publication @if($product->edition)| {{ $product->edition }} @endif</div>
                        <h2 class="font-display text-navy mb-4 h1 fw-bold">{{ $product->name }}</h2>
                        
                        <div class="price-box-premium mb-4">
                            <span class="currency">₹</span>
                            <span class="amount" id="totalAmountDisplay">{{ number_format($product->price, 2) }}</span>
                            <span class="tax-tag">Inclusive of all taxes</span>
                        </div>

                        <div class="product-meta-pills mb-4">
                            @if($product->author)<span class="meta-pill"><i class="fas fa-user-pen"></i> {{ $product->author }}</span>@endif
                            <span class="meta-pill"><i class="fas fa-certificate"></i> Premium Edition</span>
                        </div>

                        <div class="description-premium mb-5">
                            <h5 class="text-navy font-display fw-bold mb-3 border-bottom pb-2">Description</h5>
                            <div class="abstract-content text-muted line-height-18">
                                {!! $product->description !!}
                            </div>
                        </div>

                        @if($product->stock > 0)
                            <div class="purchase-box-premium bg-cream rounded-4 p-4 shadow-sm border border-gold-light">
                                <form id="purchaseForm" action="{{ route('checkout', $product) }}" method="GET">
                                    <div class="row align-items-center mb-4">
                                        <div class="col-sm-6">
                                            <label class="label-gold-sm mb-2 d-block">Quantity</label>
                                            <div class="qty-selector-lux">
                                                <button type="button" class="qty-btn-lux" id="decrementQty" onclick="changeQty(-1)"><i class="fas fa-minus"></i></button>
                                                <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ (int)($product->stock ?? 9999) }}" readonly>
                                                <button type="button" class="qty-btn-lux" id="incrementQty" onclick="changeQty(1)"><i class="fas fa-plus"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mt-3 mt-sm-0">
                                            <label class="label-gold-sm mb-2 d-block">Referral Code (Optional)</label>
                                            <div class="referral-flex">
                                                <input type="text" class="form-luxury-sm" id="referral_code" name="referral_code" value="{{ request('ref') }}" placeholder="Enter referral code for discount">
                                                <button class="btn-apply-lux" type="button" id="applyReferral">Apply</button>
                                            </div>
                                            <small id="referralMessage" class="referral-msg"></small>
                                        </div>
                                    </div>

                                    <div class="summary-box-lux mb-4">
                                        <div class="summary-line">
                                            <span>Unit Price</span>
                                            <span>₹<span id="unitPriceDisplay">{{ number_format($product->price, 2) }}</span></span>
                                        </div>
                                        <div class="summary-line discount d-none" id="discountRow">
                                            <span>Referral Discount</span>
                                            <span>-₹<span id="discountAmount">0.00</span></span>
                                        </div>
                                        <div class="summary-line total pt-2 border-top">
                                            <span>Total Amount</span>
                                            <span class="text-navy fw-bold h4 mb-0">₹<span id="totalAmountFinal">{{ number_format($product->price, 2) }}</span></span>
                                        </div>
                                    </div>

                                    @if($errors->any())
                                        <div class="alert alert-danger mb-4 py-2 small">
                                            <ul class="mb-0">
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    @if(session('error'))
                                        <div class="alert alert-danger mb-4 py-2 small">
                                            {{ session('error') }}
                                        </div>
                                    @endif

                                    <button type="submit" class="btn-primary-gold w-100 py-3 shadow-lg border-0 d-flex align-items-center justify-content-center gap-2">
                                        <i class="fas fa-shopping-cart"></i> Proceed to Checkout
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="out-of-stock-premium">
                                <i class="fas fa-archive fa-2x mb-3 text-gold"></i>
                                <h4>Edition Currently Unavailable</h4>
                                <p class="mb-0 opacity-75">This prestigious publication is temporarily out of vault. Please contact our chambers for replenishment updates.</p>
                            </div>
                        @endif

                        <div class="trust-flex-premium mt-5">
                            <div class="trust-item"><i class="fas fa-shield-halved"></i> Secure Payment</div>
                            <div class="trust-item"><i class="fas fa-truck-fast"></i> Fast Delivery</div>
                            <div class="trust-item"><i class="fas fa-medal"></i> Authentic</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($relatedProducts->count() > 0)
            <div class="related-section-lux mt-5 pt-5">
                <div class="section-header mb-4" data-aos="fade-up">
                    <span class="label-gold">Curated Collection</span>
                    <h2 class="title-premium text-navy">Related Treatises</h2>
                </div>
                <div class="row g-4">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="col-xl-3 col-lg-4 col-md-6" data-aos="fade-up">
                            <div class="treatise-premium-card h-100">
                                <div class="treatise-img-box small-card">
                                    @if($relatedProduct->images && count($relatedProduct->images) > 0)
                                        <img src="{{ asset('storage/' . $relatedProduct->images[0]) }}" alt="{{ $relatedProduct->name }}">
                                    @else
                                        <div class="treatise-placeholder">
                                            <i class="fas fa-book"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="treatise-body">
                                    <h5 class="treatise-name font-display">{{ Str::limit($relatedProduct->name, 50) }}</h5>
                                    <div class="h5 text-gold fw-bold mb-3">₹{{ number_format($relatedProduct->price, 0) }}</div>
                                    <a href="{{ route('products.show', $relatedProduct) }}" class="btn-royal-sm w-100">View Detail</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
    .product-detail-page { background: var(--cream, #f8f5ef); min-height: 80vh; padding-bottom: 120px; }

    .product-hero-header {
        background: 
            linear-gradient(rgba(8,14,28,0.82) 0%, rgba(10,18,36,0.85) 100%),
            url('/images/publications-banner.jpg') center center / cover no-repeat;
        padding: 140px 0 80px;
        position: relative;
        overflow: hidden;
        z-index: 0;
    }

    .product-hero-header::before {
        content: ''; position: absolute; inset: 0; z-index: 0;
        background-image:
            linear-gradient(rgba(201, 168, 76, 0.055) 1px, transparent 1px),
            linear-gradient(90deg, rgba(201, 168, 76, 0.055) 1px, transparent 1px);
        background-size: 55px 55px;
    }

    .product-hero-header::after {
        content:''; position: absolute; inset:0; z-index: 0;
        background:
            radial-gradient(ellipse at 10% 50%, rgba(201,168,76,0.12) 0%, transparent 50%),
            radial-gradient(ellipse at 90% 50%, rgba(201,168,76,0.08) 0%, transparent 50%),
            radial-gradient(ellipse at 50% 110%, rgba(201,168,76,0.18) 0%, transparent 45%),
            linear-gradient(120deg, rgba(20,35,70,0.85) 0%, rgba(8,14,28,0.6) 50%, rgba(15,28,55,0.9) 100%);
    }

    .product-hero-deco {
        position: absolute; right: 7%; top: 50%; transform: translateY(-50%);
        font-size: 14rem; color: rgba(201,168,76,0.06);
        pointer-events: none; z-index: 0; line-height: 1;
    }

    .product-hero-header .container { position: relative; z-index: 1; }
    .product-hero-header .label-gold { display: block; }
    .product-hero-header .heading-premium { font-size: clamp(1.8rem, 4vw, 3rem); font-weight: 700; }
    .accent-line-gold { width: 80px; height: 3px; background: var(--gold, #c9a84c); margin-top: 25px; border-radius: 2px; position: relative; z-index: 2; }

    .product-breadcrumb { display: flex; align-items: center; gap: 10px; font-size: 0.8rem; letter-spacing: 2px; text-transform: uppercase; font-weight: 700; position: relative; z-index: 2; }
    .product-breadcrumb a { color: var(--gold, #c9a84c); text-decoration:none; transition: 0.3s; }
    .product-breadcrumb a:hover { color: white; }
    .product-breadcrumb .sep { color: rgba(255,255,255,0.3); }
    .product-breadcrumb .curr { color: white; opacity: 0.7; }

    .product-content-container { padding-top: 60px; padding-bottom: 60px; }
    .product-main-card { background: white; border-radius: 40px; overflow: hidden; box-shadow: 0 40px 100px rgba(0,0,0,0.08); border: 1px solid rgba(201,168,76,0.12); }

    .main-image-wrap { height: 500px; display: flex; align-items: center; justify-content: center; padding: 40px; }
    .main-image-wrap img { max-height: 100%; max-width: 100%; object-fit: contain; filter: drop-shadow(15px 15px 30px rgba(0,0,0,0.2)); transition: 0.5s; }
    .main-image-wrap img:hover { transform: scale(1.03); }

    .glide-nav { position:absolute; top:50%; transform:translateY(-50%); width:45px; height:45px; background:white; border:1px solid var(--gold); border-radius:50%; display:flex; align-items:center; justify-content:center; color:var(--navy); cursor:pointer; transition:0.3s; z-index:2; }
    .glide-nav:hover { background:var(--gold); color:white; }
    .glide-nav.prev { left: 15px; }
    .glide-nav.next { right: 15px; }

    .thumb-nav-flex { display: flex; gap: 15px; justify-content: center; }
    .thumb-nav-item { width: 80px; height: 80px; border-radius: 12px; border: 2px solid transparent; cursor: pointer; padding: 8px; background: var(--cream); transition: 0.3s; }
    .thumb-nav-item.active, .thumb-nav-item:hover { border-color: var(--gold); background: white; }
    .thumb-nav-item img { width: 100%; height: 100%; object-fit: contain; }

    .price-box-premium { display: flex; flex-direction: row; align-items: baseline; gap: 8px; flex-wrap: nowrap; }
    .price-box-premium .currency { display: inline-block; font-size: 2.2rem; color: var(--navy, #0c1428); font-weight: 800; line-height: 1; }
    .price-box-premium .amount { display: inline-block; font-size: 3rem; color: var(--navy, #0c1428); font-weight: 800; font-family: var(--font-display); line-height: 1; }
    .price-box-premium .tax-tag { display: inline-block; font-size: 0.85rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; white-space: nowrap; }

    .meta-pill { display: inline-flex; align-items: center; gap: 8px; background: var(--cream); color: var(--navy); padding: 6px 16px; border-radius: 50px; font-size: 0.85rem; font-weight: 600; border: 1px solid rgba(201,168,76,0.15); margin-right: 10px; }
    .meta-pill i { color: var(--gold); }

    .qty-selector-lux { display: flex; align-items: center; background: white; border: 1px solid rgba(201,168,76,0.3); border-radius: 12px; overflow: hidden; max-width: 140px; }
    .qty-btn-lux { border: none; background: transparent; padding: 10px 15px; color: var(--navy); cursor: pointer; transition: 0.3s; }
    .qty-btn-lux:hover { background: var(--gold); color: white; }
    .qty-selector-lux input { width: 40px; text-align: center; border: none; font-weight: 700; color: var(--navy); background: transparent; }

    .referral-flex { display: flex; gap: 10px; }
    .form-luxury-sm { border: 1px solid rgba(201,168,76,0.3); background: white; padding: 10px 15px; border-radius: 12px; width: 100%; font-size: 0.9rem; color: var(--navy); }
    .btn-apply-lux { background: var(--navy); color: white; border: none; padding: 0 20px; border-radius: 12px; font-weight: 700; font-size: 0.8rem; text-transform: uppercase; transition: 0.3s; white-space: nowrap; cursor: pointer; }
    .btn-apply-lux:hover { background: var(--gold); color: var(--navy); }

    .summary-box-lux .summary-line { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 0.95rem; color: var(--text-muted); font-weight: 600; }
    .summary-box-lux .summary-line.discount { color: #25d366; }

    .trust-flex-premium { display: flex; flex-wrap: wrap; gap: 25px; align-items: center; justify-content: center; border-top: 1px solid rgba(0,0,0,0.05); padding-top: 20px; margin-top: 20px; }
    .trust-item { font-size: 0.85rem; font-weight: 700; color: var(--text-muted); display: flex; align-items: center; gap: 8px; }
    .trust-item i { color: var(--gold); font-size: 1.1rem; }

    .out-of-stock-premium { background: rgba(201,168,76,0.05); padding: 40px; border-radius: 24px; border: 2px dashed var(--gold); text-align: center; color: var(--navy); }
    .btn-outline-gold { background: transparent; color: var(--gold); border: 2px solid var(--gold); border-radius: 12px; font-weight: 700; transition: 0.3s; }
    .btn-outline-gold:hover { background: var(--gold); color: white; }
    .btn-primary-gold { background: var(--gold); color: white; border-radius: 12px; font-weight: 700; transition: 0.3s; border: none; cursor: pointer; }
    .btn-primary-gold:hover { background: var(--gold-dark); transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    
    .product-no-image { height: 500px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: var(--cream); }

    @media (max-width: 991px) {
        .product-gallery-premium { border-right: none; border-bottom: 1px solid rgba(0,0,0,0.05); }
        .product-main-card { border-radius: 24px; }
        .main-image-wrap { height: 350px; }
    }
    @media (max-width: 576px) {
        .product-content-container { padding-top: 40px; }
        .product-details-premium { padding: 28px !important; }
        .product-gallery-premium { padding: 28px !important; }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.product-main-slider')) {
        const glide = new Glide('.product-main-slider', {
            type: 'carousel',
            perView: 1,
            gap: 0,
            autoplay: false
        }).mount();

        document.querySelectorAll('.thumb-nav-item').forEach((item, i) => {
            item.addEventListener('click', () => {
                glide.go('=' + i);
                document.querySelectorAll('.thumb-nav-item').forEach(el => el.classList.remove('active'));
                item.classList.add('active');
            });
        });

        glide.on('run', () => {
            document.querySelectorAll('.thumb-nav-item').forEach((el, i) => {
                el.classList.toggle('active', i === glide.index);
            });
        });
    }

    var qtyInput = document.getElementById('quantity');
    if (!qtyInput) return;

    var unitPrice = {{ (float)($product->price ?? 0) }};
    var maxStock = {{ (int)($product->stock ?? 9999) }};
    var referralApplied = false;
    var discountPerUnit = 25;

    function updateUIPrice() {
        var qty = parseInt(qtyInput.value) || 1;
        var subtotal = unitPrice * qty;
        var discount = referralApplied ? (discountPerUnit * qty) : 0;
        var total = subtotal - discount;

        var unitPriceDisplay = document.getElementById('unitPriceDisplay');
        if (unitPriceDisplay) {
            unitPriceDisplay.innerText = unitPrice.toLocaleString('en-IN', {minimumFractionDigits: 2});
        }

        var discountRow = document.getElementById('discountRow');
        var discountAmount = document.getElementById('discountAmount');
        if (referralApplied && discountRow && discountAmount) {
            discountRow.classList.remove('d-none');
            discountAmount.innerText = discount.toLocaleString('en-IN', {minimumFractionDigits: 2});
        } else if (discountRow) {
            discountRow.classList.add('d-none');
        }

        var totalAmountFinal = document.getElementById('totalAmountFinal');
        if (totalAmountFinal) {
            totalAmountFinal.innerText = total.toLocaleString('en-IN', {minimumFractionDigits: 2});
        }
    }

    window.changeQty = function(delta) {
        var val = parseInt(qtyInput.value) || 1;
        var next = val + delta;
        if (next >= 1 && next <= maxStock) {
            qtyInput.value = next;
            updateUIPrice();
        }
    };

    updateUIPrice();

    var applyReferralBtn = document.getElementById('applyReferral');
    if (applyReferralBtn) {
        applyReferralBtn.addEventListener('click', function() {
            var code = document.getElementById('referral_code').value.trim();
            var msg = document.getElementById('referralMessage');
            var btn = this;

            if (!code) {
                if (msg) {
                    msg.innerText = 'Please enter a referral code';
                    msg.className = 'referral-msg text-danger mt-1';
                }
                return;
            }

            btn.disabled = true;
            btn.innerText = 'Checking...';

            fetch("{{ route('checkReferral') }}", {
                method: "POST",
                headers: { 
                    "Content-Type": "application/json", 
                    "X-CSRF-TOKEN": "{{ csrf_token() }}" 
                },
                body: JSON.stringify({ referral_code: code })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    referralApplied = true;
                    if (msg) {
                        msg.innerText = data.message || 'Referral applied!';
                        msg.className = 'referral-msg text-success mt-1 d-block';
                    }
                    btn.innerText = 'Applied';
                    updateUIPrice();
                } else {
                    referralApplied = false;
                    if (msg) {
                        msg.innerText = data.message || 'Invalid code';
                        msg.className = 'referral-msg text-danger mt-1 d-block';
                    }
                    btn.innerText = 'Apply';
                    btn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (msg) {
                    msg.innerText = 'Error checking code';
                    msg.className = 'referral-msg text-danger mt-1 d-block';
                }
                btn.innerText = 'Apply';
                btn.disabled = false;
            });
        });
    }
});
</script>
@endpush
