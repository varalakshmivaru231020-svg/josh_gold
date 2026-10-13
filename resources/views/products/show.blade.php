@extends('layouts.lawfirm')

@section('title', $product->name . ' | JOSHA GOLD AND DIAMOND JEWELLERY')

@section('content')
@include('layouts.partials.page-head', [
    'eyebrow' => 'Legal Publication',
    'title'   => $product->name,
    'crumbs'  => [['Publications', route('products.index')], [Str::limit($product->name, 40)]],
])

<section class="product-detail-page">

    <div class="jb-wrap product-content-container">
        <div class="product-main-card" data-aos="fade-up">
            <div class="row g-0">
                <!-- Product Gallery -->
                <div class="col-lg-6">
                    <div class="product-gallery-premium p-5">
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
                                <i class="fas fa-book-open fa-5x"></i>
                                <span class="d-block mt-3">Visual Archive Pending</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Product Details -->
                <div class="col-lg-6">
                    <div class="product-details-premium p-5">
                        <div class="label-gold mb-3">Legal Publication @if($product->edition)| {{ $product->edition }} @endif</div>
                        <h2 class="product-title mb-4">{{ $product->name }}</h2>
                        
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
                            <h5 class="description-title">Description</h5>
                            <div class="abstract-content">
                                {!! $product->description !!}
                            </div>
                        </div>

                        @if($product->stock > 0)
                            <div class="purchase-box-premium p-4">
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
                                        <div class="summary-line total">
                                            <span>Total Amount</span>
                                            <span class="total-value">₹<span id="totalAmountFinal">{{ number_format($product->price, 2) }}</span></span>
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

                                    <button type="submit" class="btn-primary-gold w-100 d-flex align-items-center justify-content-center gap-2">
                                        <i class="fas fa-shopping-cart"></i> Proceed to Checkout
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="out-of-stock-premium">
                                <i class="fas fa-archive fa-2x mb-3"></i>
                                <h4>Edition Currently Unavailable</h4>
                                <p class="mb-0">This prestigious publication is temporarily out of vault. Please contact our chambers for replenishment updates.</p>
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
            <div class="related-section-lux">
                <div class="section-header mb-4" data-aos="fade-up">
                    <span class="label-gold">Curated Collection</span>
                    <h2 class="title-premium">Related Treatises</h2>
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
                                    <div class="treatise-price">₹{{ number_format($relatedProduct->price, 0) }}</div>
                                    <a href="{{ route('products.show', $relatedProduct) }}" class="btn-royal-sm jb-btn jb-btn--primary w-100">View Detail</a>
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
    .product-detail-page { background: var(--jb-ground); padding: 32px 0 48px; }
    @media (min-width: 768px) { .product-detail-page { padding: 48px 0 64px; } }

    /* Main card */
    .product-main-card { overflow: hidden; border: 1px solid var(--jb-line); border-radius: var(--jb-r-card); background: var(--jb-panel); box-shadow: 0 16px 36px -14px rgba(0, 0, 0, .9); }

    /* Gallery */
    .product-gallery-premium { position: relative; height: 100%; border-right: 1px solid var(--jb-line); background: radial-gradient(circle at 50% 42%, #17171b 0%, #08080a 90%); }
    .product-main-slider { position: relative; }
    .main-image-wrap { height: 460px; display: flex; align-items: center; justify-content: center; padding: 24px; }
    .main-image-wrap img { max-height: 100%; max-width: 100%; object-fit: contain; border-radius: 12px; filter: drop-shadow(0 18px 30px rgba(0, 0, 0, .7)); transition: transform .5s; }
    @media (hover: hover) { .main-image-wrap img:hover { transform: scale(1.03); } }

    .glide-nav { position: absolute; top: 50%; transform: translateY(-50%); z-index: 2; width: 42px; height: 42px; display: flex; align-items: center; justify-content: center;
        border: 1px solid var(--jb-line); border-radius: 50%; background: rgba(0, 0, 0, .6); backdrop-filter: blur(8px); color: var(--jb-white); cursor: pointer; transition: background-color .15s, border-color .15s, color .15s; }
    .glide-nav:hover { background: var(--jb-champagne); border-color: var(--jb-champagne); color: #111; }
    .glide-nav.prev { left: 6px; }
    .glide-nav.next { right: 6px; }

    .thumb-nav-flex { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; }
    .thumb-nav-item { width: 72px; height: 72px; padding: 6px; border: 1px solid var(--jb-line); border-radius: var(--jb-r-ctl); background: var(--jb-panel-2); cursor: pointer; transition: border-color .15s, background-color .15s; }
    .thumb-nav-item.active, .thumb-nav-item:hover { border-color: var(--jb-gold-light); background: var(--jb-panel-3); }
    .thumb-nav-item img { width: 100%; height: 100%; object-fit: contain; border-radius: 6px; }

    .product-no-image { height: 460px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--jb-muted); }
    .product-no-image i { color: var(--jb-gold); opacity: .35; }

    /* Details */
    .product-details-premium .label-gold { color: var(--jb-gold); font-size: 10.5px; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; }
    .product-title { color: var(--jb-white); font: 600 clamp(26px, 3vw, 34px)/1.2 var(--jb-serif); }

    .price-box-premium { display: flex; flex-wrap: wrap; align-items: baseline; gap: 4px 8px; }
    .price-box-premium .currency { color: var(--jb-gold-light); font: 600 1.8rem/1 var(--jb-serif); }
    .price-box-premium .amount { color: var(--jb-gold-light); font: 600 clamp(2.1rem, 4vw, 2.6rem)/1 var(--jb-serif); font-variant-numeric: tabular-nums; }
    .price-box-premium .tax-tag { margin-left: 4px; color: var(--jb-muted); font-size: 11px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; white-space: nowrap; }

    .product-meta-pills { display: flex; flex-wrap: wrap; gap: 8px; }
    .meta-pill { display: inline-flex; align-items: center; gap: 8px; min-height: 34px; padding: 0 12px; border: 1px solid var(--jb-line); border-radius: var(--jb-r-ctl); background: var(--jb-panel-2); color: var(--jb-text); font-size: 12.5px; font-weight: 600; }
    .meta-pill i { color: var(--jb-gold-light); }

    .description-title { margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid var(--jb-line-soft); color: var(--jb-white); font: 600 18px/1.3 var(--jb-serif); }
    .abstract-content { color: var(--jb-muted); font-size: 14.5px; line-height: 1.75; }
    .abstract-content :where(p, ul, ol):last-child { margin-bottom: 0; }
    .abstract-content :where(strong, b, h1, h2, h3, h4, h5, h6) { color: var(--jb-white); }
    .abstract-content a { color: var(--jb-gold-light); }

    /* Purchase box */
    .purchase-box-premium { border: 1px solid var(--jb-line); border-radius: var(--jb-r-card); background: linear-gradient(180deg, rgba(201, 163, 92, .06), transparent 60%), var(--jb-panel-2); }
    .label-gold-sm { color: var(--jb-gold); font-size: 10.5px; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; }

    .qty-selector-lux { display: flex; align-items: center; max-width: 140px; overflow: hidden; border: 1px solid var(--jb-line); border-radius: var(--jb-r-ctl); background: var(--jb-panel); }
    .qty-btn-lux { padding: 10px 15px; border: 0; background: transparent; color: var(--jb-white); cursor: pointer; transition: background-color .15s, color .15s; }
    .qty-btn-lux:hover { background: rgba(255, 255, 255, .08); color: var(--jb-gold-light); }
    .qty-selector-lux input { width: 40px; border: 0; background: transparent; color: var(--jb-white); font-weight: 700; text-align: center; -moz-appearance: textfield; appearance: textfield; }
    .qty-selector-lux input::-webkit-outer-spin-button, .qty-selector-lux input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    .qty-selector-lux input:focus { outline: none; }

    .referral-flex { display: flex; gap: 8px; }
    .form-luxury-sm { width: 100%; min-width: 0; padding: 10px 14px; border: 1px solid var(--jb-line); border-radius: var(--jb-r-ctl); background: var(--jb-panel); color: var(--jb-white); font-size: 0.9rem; transition: border-color .15s, box-shadow .15s; }
    .form-luxury-sm::placeholder { color: #7d7d7d; }
    .form-luxury-sm:focus { outline: none; border-color: rgba(234, 215, 166, .55); box-shadow: 0 0 0 .2rem rgba(234, 215, 166, .15); background: var(--jb-panel-2); }
    .btn-apply-lux { padding: 0 16px; border: 1px solid var(--jb-line); border-radius: var(--jb-r-ctl); background: transparent; color: var(--jb-white); font-size: 12.5px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; white-space: nowrap; cursor: pointer; transition: background-color .15s, border-color .15s; }
    .btn-apply-lux:hover { background: rgba(255, 255, 255, .08); border-color: rgba(255, 255, 255, .7); }
    .btn-apply-lux:disabled { opacity: .7; cursor: default; }
    .referral-msg.text-success { color: var(--jb-up) !important; }
    .referral-msg.text-danger { color: var(--jb-down) !important; }

    .summary-box-lux .summary-line { display: flex; justify-content: space-between; align-items: baseline; gap: 12px; margin-bottom: 8px; color: var(--jb-muted); font-size: 0.95rem; font-weight: 600; }
    .summary-box-lux .summary-line.discount { color: var(--jb-up); }
    .summary-box-lux .summary-line.total { margin: 12px 0 0; padding-top: 12px; border-top: 1px solid var(--jb-line); color: var(--jb-text); }
    .summary-box-lux .total-value { color: var(--jb-white); font: 600 1.4rem/1.2 var(--jb-serif); font-variant-numeric: tabular-nums; }

    .btn-primary-gold { min-height: 50px; border: 0; border-radius: var(--jb-r-btn); background: var(--jb-champagne); color: #111; font-weight: 700; font-size: 15px; cursor: pointer; box-shadow: 0 10px 28px -12px rgba(234, 215, 166, .45); transition: background-color .15s, transform .15s; }
    .btn-primary-gold:hover { background: #fff8ea; }
    .btn-primary-gold:active { background: var(--jb-champagne-down); transform: translateY(1px); }

    .out-of-stock-premium { padding: 32px 24px; border: 1px dashed var(--jb-line-strong); border-radius: var(--jb-r-card); background: rgba(201, 163, 92, .05); text-align: center; color: var(--jb-muted); }
    .out-of-stock-premium i { color: var(--jb-gold); }
    .out-of-stock-premium h4 { color: var(--jb-white); font-size: 20px; }

    .trust-flex-premium { display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 12px 24px; padding-top: 20px; border-top: 1px solid var(--jb-line-soft); }
    .trust-item { display: flex; align-items: center; gap: 8px; color: var(--jb-muted); font-size: 13px; font-weight: 700; }
    .trust-item i { color: var(--jb-gold-light); font-size: 1.05rem; }

    /* Related */
    .related-section-lux { margin-top: 40px; padding-top: 36px; border-top: 1px solid var(--jb-line-soft); }
    .related-section-lux .label-gold { display: block; color: var(--jb-gold); font-size: 10.5px; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; }
    .related-section-lux .title-premium { margin: 6px 0 0; font-size: 20px; font-weight: 600; line-height: 1.25; }
    @media (min-width: 768px) { .related-section-lux .title-premium { font-size: 26px; } }

    .treatise-premium-card { position: relative; display: flex; flex-direction: column; overflow: hidden; border: 1px solid var(--jb-line); border-radius: var(--jb-r-card); background: var(--jb-black); box-shadow: 0 4px 10px rgba(0, 0, 0, .8); transition: transform .2s, border-color .2s, box-shadow .2s; }
    .treatise-premium-card::after { content: ""; position: absolute; left: 18%; right: 18%; bottom: 0; height: 1px; background: linear-gradient(90deg, transparent, var(--jb-gold-deep), transparent); opacity: .8; }
    @media (hover: hover) {
        .treatise-premium-card:hover { transform: translateY(-3px); border-color: var(--jb-line-strong); box-shadow: 0 16px 36px -12px rgba(0, 0, 0, .9); }
        .treatise-premium-card:hover .treatise-img-box img { transform: scale(1.04); }
    }
    .treatise-img-box { position: relative; aspect-ratio: 1; overflow: hidden; background: radial-gradient(circle at 50% 42%, #15151a 0%, #08080a 95%); }
    .treatise-img-box img { width: 100%; height: 100%; object-fit: cover; transition: transform .45s; }
    .treatise-placeholder { height: 100%; display: grid; place-items: center; color: var(--jb-gold); font-size: 2.6rem; opacity: .4; }
    .treatise-body { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px; padding: 12px 12px 14px; text-align: center; }
    .treatise-name { margin: 0; min-height: 2.6em; color: var(--jb-white); font: 500 15px/1.3 var(--jb-serif); }
    .treatise-price { color: var(--jb-gold-light); font: 600 16.5px/1.2 var(--jb-serif); font-variant-numeric: tabular-nums; }
    .btn-royal-sm { min-height: 38px; margin-top: 10px; font-size: 13px; }

    @media (max-width: 991px) {
        .product-gallery-premium { height: auto; border-right: 0; border-bottom: 1px solid var(--jb-line); }
        .main-image-wrap, .product-no-image { height: 340px; }
    }
    @media (max-width: 576px) {
        .product-details-premium { padding: 22px !important; }
        .product-gallery-premium { padding: 20px !important; }
        .purchase-box-premium { padding: 18px !important; }
        .main-image-wrap, .product-no-image { height: 280px; }
        .main-image-wrap { padding: 12px; }
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
