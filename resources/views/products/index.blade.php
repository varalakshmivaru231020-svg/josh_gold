@extends('layouts.lawfirm')

@section('title', 'Legal Publications - Bengaluru Law House')

@section('content')
    <!-- Page Hero -->
    <section class="page-hero">
        <div class="hero-pattern-left"></div>
        <div class="hero-pattern-right"></div>
        <div class="container">
            <div class="page-hero-content fade-up">
                <p class="section-label-gold">Our Collection</p>
                <h1 class="page-hero-title">Legal Publications</h1>
                <p class="page-hero-description">
                    Browse our comprehensive collection of authoritative legal books and academic resources
                </p>
                <div class="page-hero-badges">
                    <span class="hero-badge"><i class="fas fa-book me-2"></i>{{ $products->total() }} Publications</span>
                    @if(request()->has('ref'))
                        <span class="hero-badge success"><i class="fas fa-tag me-2"></i>Discount Available</span>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="products-section">
        <div class="container-fluid px-4 px-lg-5">
            <!-- Breadcrumb -->
            <div class="products-breadcrumb fade-up">
                <a href="{{ route('home') }}"><i class="fas fa-home me-1"></i> Home</a>
                <span class="divider">/</span>
                <span class="current">Publications</span>
            </div>

            @if($products->count() > 0)
                <div class="products-list">
                    @foreach($products as $product)
                        <div class="product-row fade-up">
                            <div class="product-row-inner">
                                <!-- Book Image -->
                                <div class="product-image-section">
                                    <div class="product-image-wrapper">
                                        @if($product->images && count($product->images) > 0)
                                            <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" class="product-cover">
                                        @else
                                            <div class="product-placeholder">
                                                <i class="fas fa-book"></i>
                                            </div>
                                        @endif
                                        <div class="product-shadow"></div>
                                    </div>
                                </div>

                                <!-- Book Details -->
                                <div class="product-details">
                                    <div class="product-details-top">
                                        <span class="product-category">Legal Publication</span>
                                        <h2 class="product-title">{{ $product->name }}</h2>
                                        <div class="gold-divider"></div>
                                        <p class="product-description">{{ Str::limit(strip_tags($product->description), 250) }}</p>
                                    </div>

                                    <div class="product-meta">
                                        @if($product->author)
                                        <div class="meta-item">
                                            <i class="fas fa-user-edit"></i>
                                            <span>{{ $product->author }}</span>
                                        </div>
                                        @endif
                                        @if($product->publisher)
                                        <div class="meta-item">
                                            <i class="fas fa-building"></i>
                                            <span>{{ $product->publisher }}</span>
                                        </div>
                                        @endif
                                        @if($product->edition)
                                        <div class="meta-item">
                                            <i class="fas fa-bookmark"></i>
                                            <span>{{ $product->edition }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Price & Action -->
                                <div class="product-action-section">
                                    <div class="product-price-box">
                                        <span class="price-label">Price</span>
                                        <div class="price-main">
                                            <span class="price-value">₹{{ number_format($product->price, 0) }}</span>
                                            @if(isset($product->mrp) && $product->mrp > $product->price)
                                                <span class="price-original">₹{{ number_format($product->mrp, 0) }}</span>
                                                <span class="price-discount">{{ round((($product->mrp - $product->price) / $product->mrp) * 100) }}% OFF</span>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ route('products.show', $product) }}" class="btn-view-product">
                                        <span>View Details</span>
                                        <i class="fas fa-arrow-right"></i>
                                    </a>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                <div class="pagination-wrapper fade-up">
                    {{ $products->links() }}
                </div>
                @endif
            @else
                <div class="empty-state fade-up">
                    <div class="empty-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h3>No Publications Available</h3>
                    <p>We're currently updating our collection. Please check back soon for new publications.</p>
                    <a href="{{ route('home') }}" class="btn-primary-law">
                        <i class="fas fa-home me-2"></i>Back to Home
                    </a>
                </div>
            @endif
        </div>
    </section>

@endsection

@push('styles')
<style>
    /* Page Hero */
    .page-hero {
        background: var(--navy-deep);
        padding: 160px 0 100px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .hero-pattern-left,
    .hero-pattern-right {
        position: absolute;
        width: 400px;
        height: 400px;
        border: 2px solid rgba(201, 162, 39, 0.1);
        border-radius: 50%;
        pointer-events: none;
    }

    .hero-pattern-left {
        top: 5%;
        left: -200px;
        animation: float 8s ease-in-out infinite;
    }

    .hero-pattern-right {
        bottom: 5%;
        right: -200px;
        animation: float 8s ease-in-out infinite reverse;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-30px); }
    }

    .page-hero-content {
        position: relative;
        z-index: 1;
    }

    .section-label-gold {
        font-family: var(--font-heading);
        font-size: 0.95rem;
        color: var(--gold-primary);
        text-transform: uppercase;
        letter-spacing: 0.3em;
        margin-bottom: 1rem;
        font-weight: 500;
    }

    .page-hero-title {
        font-family: var(--font-heading);
        font-size: 3.5rem;
        font-weight: 700;
        color: var(--cream-primary);
        margin-bottom: 1.5rem;
        line-height: 1.2;
    }

    .page-hero-description {
        font-size: 1.15rem;
        color: rgba(250, 248, 243, 0.75);
        max-width: 600px;
        margin: 0 auto 2rem;
        line-height: 1.8;
    }

    .page-hero-badges {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .hero-badge {
        background: rgba(201, 162, 39, 0.15);
        color: var(--gold-primary);
        padding: 0.6rem 1.5rem;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
        border: 1px solid rgba(201, 162, 39, 0.3);
    }

    .hero-badge.success {
        background: rgba(40, 167, 69, 0.15);
        color: #28a745;
        border-color: rgba(40, 167, 69, 0.3);
    }

    /* Products Section */
    .products-section {
        background: var(--cream-primary);
        padding: 60px 0 100px;
    }

    .products-breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 2.5rem;
        font-size: 0.95rem;
    }

    .products-breadcrumb a {
        color: var(--gold-dark);
        text-decoration: none;
        transition: var(--transition-fast);
    }

    .products-breadcrumb a:hover {
        color: var(--navy-deep);
    }

    .products-breadcrumb .divider {
        color: var(--text-muted);
    }

    .products-breadcrumb .current {
        color: var(--navy-primary);
        font-weight: 600;
    }

    /* Products List - Full Width Row Layout */
    .products-list {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .product-row {
        background: var(--white);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(10, 22, 40, 0.08);
        transition: all 0.4s ease;
        border: 1px solid rgba(201, 162, 39, 0.1);
    }

    .product-row:hover {
        box-shadow: 0 12px 40px rgba(10, 22, 40, 0.15);
        transform: translateY(-4px);
        border-color: rgba(201, 162, 39, 0.3);
    }

    .product-row-inner {
        display: flex;
        align-items: stretch;
        min-height: 280px;
    }

    /* Product Image Section */
    .product-image-section {
        width: 280px;
        min-width: 280px;
        background: var(--navy-deep);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        position: relative;
    }

    .product-image-section::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 60px;
        background: linear-gradient(90deg, transparent, rgba(201, 162, 39, 0.1));
        pointer-events: none;
    }

    .product-image-wrapper {
        position: relative;
    }

    .product-cover {
        width: 160px;
        height: 220px;
        object-fit: cover;
        border-radius: 6px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.4);
        transform: perspective(800px) rotateY(-8deg);
        transition: all 0.5s ease;
    }

    .product-row:hover .product-cover {
        transform: perspective(800px) rotateY(0deg) scale(1.05);
    }

    .product-placeholder {
        width: 160px;
        height: 220px;
        background: linear-gradient(135deg, var(--navy-primary) 0%, var(--navy-deep) 100%);
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 15px 35px rgba(0,0,0,0.4);
        transform: perspective(800px) rotateY(-8deg);
        transition: all 0.5s ease;
    }

    .product-row:hover .product-placeholder {
        transform: perspective(800px) rotateY(0deg) scale(1.05);
    }

    .product-placeholder i {
        font-size: 3.5rem;
        color: rgba(201, 162, 39, 0.3);
    }

    .product-shadow {
        position: absolute;
        bottom: -12px;
        left: 50%;
        transform: translateX(-50%);
        width: 80%;
        height: 15px;
        background: radial-gradient(ellipse, rgba(0,0,0,0.25) 0%, transparent 70%);
    }

    /* Product Details */
    .product-details {
        flex: 1;
        padding: 2rem 2.5rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .product-details-top {
        margin-bottom: 1.25rem;
    }

    .product-category {
        font-size: 0.8rem;
        color: var(--gold-dark);
        text-transform: uppercase;
        letter-spacing: 0.2em;
        margin-bottom: 0.5rem;
        font-weight: 600;
        display: inline-block;
    }

    .product-title {
        font-family: var(--font-heading);
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--navy-deep);
        margin-bottom: 0.75rem;
        line-height: 1.3;
    }

    .gold-divider {
        width: 60px;
        height: 3px;
        background: var(--gold-primary);
        margin-bottom: 1rem;
    }

    .product-description {
        font-size: 1rem;
        color: var(--text-muted);
        line-height: 1.7;
        margin: 0;
    }

    .product-meta {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .product-meta .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        color: var(--navy-muted);
    }

    .product-meta .meta-item i {
        color: var(--gold-primary);
        font-size: 0.85rem;
    }

    /* Product Action Section */
    .product-action-section {
        width: 280px;
        min-width: 280px;
        background: linear-gradient(135deg, var(--cream-dark) 0%, var(--cream-primary) 100%);
        padding: 2rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        border-left: 1px solid rgba(201, 162, 39, 0.15);
    }

    .product-price-box {
        text-align: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid rgba(201, 162, 39, 0.2);
    }

    .price-label {
        display: block;
        font-size: 0.85rem;
        color: var(--navy-muted);
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 0.5rem;
    }

    .price-main {
        display: flex;
        align-items: baseline;
        justify-content: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .price-value {
        font-family: var(--font-heading);
        font-size: 2.25rem;
        font-weight: 700;
        color: var(--navy-deep);
    }

    .price-original {
        font-size: 1.1rem;
        color: var(--text-muted);
        text-decoration: line-through;
    }

    .price-discount {
        background: #28a745;
        color: white;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.25rem 0.6rem;
        border-radius: 4px;
    }

    .btn-view-product {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        width: 100%;
        background: var(--navy-deep);
        color: var(--cream-primary);
        padding: 1rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.3s ease;
        margin-bottom: 0.75rem;
    }

    .btn-view-product:hover {
        background: var(--navy-primary);
        color: var(--gold-primary);
        transform: translateX(5px);
    }

    .btn-view-product i {
        transition: transform 0.3s ease;
    }

    .btn-view-product:hover i {
        transform: translateX(5px);
    }

    .btn-buy-now {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        background: var(--gold-primary);
        color: var(--navy-deep);
        padding: 1rem 1.5rem;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-buy-now:hover {
        background: var(--gold-dark);
        color: var(--navy-deep);
        transform: scale(1.02);
        box-shadow: 0 8px 25px rgba(201, 162, 39, 0.35);
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 4rem;
        padding-top: 3rem;
        border-top: 2px solid rgba(201, 162, 39, 0.15);
    }

    .pagination-wrapper .pagination {
        gap: 0.5rem;
    }

    .pagination-wrapper .page-link {
        background: var(--white);
        border: 2px solid rgba(201, 162, 39, 0.2);
        color: var(--navy-primary);
        padding: 0.75rem 1.25rem;
        border-radius: 6px;
        transition: var(--transition-fast);
        font-weight: 600;
    }

    .pagination-wrapper .page-link:hover {
        background: var(--navy-deep);
        border-color: var(--navy-deep);
        color: var(--cream-primary);
    }

    .pagination-wrapper .page-item.active .page-link {
        background: var(--navy-deep);
        border-color: var(--navy-deep);
        color: var(--gold-primary);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        background: var(--white);
        border-radius: 16px;
        box-shadow: var(--shadow-soft);
    }

    .empty-icon {
        width: 120px;
        height: 120px;
        background: var(--navy-deep);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
    }

    .empty-icon i {
        font-size: 3rem;
        color: var(--gold-primary);
    }

    .empty-state h3 {
        font-family: var(--font-heading);
        color: var(--navy-deep);
        margin-bottom: 1rem;
        font-size: 1.75rem;
    }

    .empty-state p {
        color: var(--text-muted);
        margin-bottom: 2rem;
        font-size: 1.1rem;
    }

    /* Responsive */
    @media (max-width: 1199px) {
        .product-image-section {
            width: 240px;
            min-width: 240px;
        }

        .product-action-section {
            width: 260px;
            min-width: 260px;
        }

        .product-cover,
        .product-placeholder {
            width: 140px;
            height: 195px;
        }
    }

    @media (max-width: 991px) {
        .page-hero-title {
            font-size: 2.5rem;
        }

        .product-row-inner {
            flex-direction: column;
        }

        .product-image-section {
            width: 100%;
            min-width: 100%;
            padding: 2rem;
        }

        .product-image-section::after {
            display: none;
        }

        .product-cover,
        .product-placeholder {
            width: 180px;
            height: 250px;
            transform: perspective(800px) rotateY(0deg);
        }

        .product-details {
            padding: 2rem;
        }

        .product-action-section {
            width: 100%;
            min-width: 100%;
            border-left: none;
            border-top: 1px solid rgba(201, 162, 39, 0.15);
            flex-direction: row;
            flex-wrap: wrap;
            gap: 1rem;
            padding: 1.5rem 2rem;
        }

        .product-price-box {
            flex: 1;
            min-width: 200px;
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
            text-align: left;
        }

        .price-main {
            justify-content: flex-start;
        }

        .btn-view-product,
        .btn-buy-now {
            flex: 1;
            min-width: 150px;
            margin-bottom: 0;
        }
    }

    @media (max-width: 767px) {
        .page-hero {
            padding: 140px 0 80px;
        }

        .page-hero-title {
            font-size: 2rem;
        }

        .products-section {
            padding: 40px 0 60px;
        }

        .product-image-section {
            padding: 1.5rem;
        }

        .product-cover,
        .product-placeholder {
            width: 150px;
            height: 210px;
        }

        .product-details {
            padding: 1.5rem;
        }

        .product-title {
            font-size: 1.4rem;
        }

        .product-meta {
            gap: 1rem;
        }

        .product-action-section {
            flex-direction: column;
            padding: 1.5rem;
        }

        .product-price-box {
            text-align: center;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
            border-bottom: 1px solid rgba(201, 162, 39, 0.2);
        }

        .price-main {
            justify-content: center;
        }

        .price-value {
            font-size: 2rem;
        }

        .btn-view-product,
        .btn-buy-now {
            width: 100%;
        }
    }
</style>
@endpush
