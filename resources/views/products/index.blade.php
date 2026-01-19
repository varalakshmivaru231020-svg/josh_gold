@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div class="container py-4">
    <!-- Hero Section -->
    <div class="hero-section bg-gradient-primary text-white rounded-3 p-5 mb-5">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold">Shop Premium Products</h1>
                <p class="lead mb-4">
                    Get ₹25 discount using distributor referral code!
                    Earn commissions by becoming a distributor.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('agent.register') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Become a Distributor
                    </a>
                    <a href="#products" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-shopping-bag me-2"></i>Shop Now
                    </a>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <i class="fas fa-gift fa-8x opacity-75"></i>
            </div>
        </div>
    </div>
    
    <!-- Products Section -->
    <div id="products">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Featured Products</h2>
            <div class="d-flex gap-2">
                <span class="badge bg-primary">{{ $products->total() }} products</span>
                @if(request()->has('ref'))
                    <span class="badge bg-success">₹25 discount available</span>
                @endif
            </div>
        </div>
        
        @if($products->count() > 0)
        <div class="row g-4">
            @foreach($products as $product)
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card product-card h-100">
                    <!-- Product Image -->
                    <div class="position-relative">
                        @if($product->images && count($product->images) > 0)
                        <div class="glide product-slider">
                            <div class="glide__track" data-glide-el="track">
                                <ul class="glide__slides">
                                    @foreach($product->images as $image)
                                    <li class="glide__slide">
                                        <img src="{{ asset('storage/' . $image) }}"
                                             class="card-img-top product-img"
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
                        @else
                        <div class="card-img-top product-img bg-light d-flex align-items-center justify-content-center">
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Product Body -->
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text text-muted small mb-3">
                            {{ Str::limit(strip_tags($product->description), 80) }}
                        </p>
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="text-primary mb-0">
                                    ₹{{ number_format($product->price, 2) }}
                                </h4>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('products.show', $product) }}"
                                   class="btn btn-primary">
                                    <i class="fas fa-eye me-2"></i>View Details
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Footer -->
                    <div class="card-footer bg-transparent border-top-0 pt-0">
                        <small class="text-muted">
                            <i class="fas fa-tag me-1"></i>
                            @if(request()->has('ref'))
                                <span class="text-success">
                                    ₹25 discount with distributor referral code
                                </span>
                            @else
                                Use distributor referral code for discount
                            @endif
                        </small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            {{ $products->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-box-open fa-4x text-muted mb-4"></i>
            <h4>No products available</h4>
            <p class="text-muted">Check back later for new products.</p>
        </div>
        @endif
    </div>
    
    <!-- Become Distributor CTA -->
    <div class="card bg-gradient-success text-white mt-5">
        <div class="card-body p-5 text-center">
            <h2 class="mb-3">Become a Distributor & Earn Commissions</h2>
            <p class="lead mb-4">
                  Share products with your contacts and earn a honorary commission on every sale.     
            </p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="{{ route('agent.register') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-user-plus me-2"></i>Register as Distributor
                </a>
                <a href="{{ route('agent.login') }}" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-sign-in-alt me-2"></i>Distributor Login
                </a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@glidejs/glide/dist/css/glide.core.min.css">
<style>
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.product-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #e0e0e0;
}
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
.product-img {
    height: 200px;
    object-fit: cover;
}
.product-slider {
    position: relative;
}
.glide__arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255,255,255,0.8);
    border: none;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #333;
}
.glide__arrow--left { left: 10px; }
.glide__arrow--right { right: 10px; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@glidejs/glide"></script>
<script>
$(document).ready(function () {
    $('.product-slider').each(function () {
        new Glide(this, {
            type: 'carousel',
            perView: 1,
            autoplay: 3000,
            hoverpause: true
        }).mount();
    });
});
</script>
@endpush
@endsection
