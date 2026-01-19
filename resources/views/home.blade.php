@extends('layouts.lawfirm')

@section('title', 'Bengaluru Law House - Premium Legal Publications')

@section('content')
    <!-- Dynamic Slider Banner -->
    @if($sliders && $sliders->count() > 0)
    <section class="hero-slider-section">
        <div class="swiper hero-swiper">
            <div class="swiper-wrapper">
                @foreach($sliders as $slider)
                <div class="swiper-slide">
                    <div class="hero-slide" style="background-image: url('{{ asset('storage/' . $slider->image) }}');">
                        <div class="hero-slide-overlay"></div>
                        <div class="container">
                            <div class="hero-slide-content">
                                @if($slider->title)
                                <h1 class="hero-slide-title fade-up">{{ $slider->title }}</h1>
                                @endif
                                @if($slider->subtitle)
                                <p class="hero-slide-subtitle fade-up delay-1">{{ $slider->subtitle }}</p>
                                @endif
                                @if($slider->description)
                                <p class="hero-slide-description fade-up delay-2">{{ $slider->description }}</p>
                                @endif
                                @if($slider->button_text && $slider->button_link)
                                <a href="{{ $slider->button_link }}" class="btn-primary-law fade-up delay-3">
                                    {{ $slider->button_text }}
                                    <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @if($sliders->count() > 1)
            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
            @endif
        </div>
    </section>
    @else
    <!-- Fallback Hero Section -->
    <section class="hero-section">
        <div class="hero-pattern">
            <i class="fas fa-balance-scale"></i>
        </div>
        <div class="container">
            <div class="row align-items-center min-vh-75">
                <div class="col-lg-8 mx-auto text-center">
                    <div class="hero-content">
                        <p class="hero-subtitle fade-up">Trusted Legal Publications</p>
                        <h1 class="hero-title fade-up delay-1">
                            Your Partner in<br>
                            <span class="highlight">Legal Excellence</span>
                        </h1>
                        <p class="hero-description fade-up delay-2">
                            Bengaluru Law House Publications is one of India's leading providers of authoritative legal books and academic resources.
                        </p>
                        <div class="hero-buttons fade-up delay-3">
                            <a href="{{ route('products.index') }}" class="btn-primary-law">
                                <i class="fas fa-book"></i>
                                Browse Publications
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Dynamic About Section -->
    @if($about && $about->is_active)
    <section class="section about-section">
        <div class="container">
            <div class="row align-items-center">
                @if($about->image)
                <div class="col-lg-5">
                    <div class="about-image-col slide-left">
                        <img src="{{ asset('storage/' . $about->image) }}" alt="{{ $about->title }}" class="about-image">
                        @if($about->years_experience)
                        <div class="experience-badge">
                            <span class="number">{{ $about->years_experience }}</span>
                            <span class="text">Years of Trust</span>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-7">
                @else
                <div class="col-lg-10 mx-auto">
                @endif
                    <div class="about-text-col slide-right">
                        @if($about->subtitle)
                        <p class="section-label">{{ $about->subtitle }}</p>
                        @endif
                        <h2 class="section-title text-start">{{ $about->title }}</h2>
                        <div class="gold-divider mb-4"></div>
                        <div class="about-content">
                            {!! $about->content !!}
                        </div>
                        <a href="{{ url('/about-us') }}" class="btn-primary-law mt-4">
                            Learn More <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Featured Book Section -->
    @if($featuredBook && $featuredBook->is_active)
    <section class="featured-book-section">
        <div class="featured-book-bg">
            <div class="book-pattern-left"></div>
            <div class="book-pattern-right"></div>
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="featured-book-image slide-left">
                        @if($featuredBook->image)
                        <div class="book-image-wrapper">
                            <img src="{{ asset('storage/' . $featuredBook->image) }}" alt="{{ $featuredBook->book_name }}" class="book-image">
                            <div class="book-shadow"></div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="featured-book-content slide-right">
                        <h2 class="book-title">{{ $featuredBook->book_name }}</h2>
                        <div class="gold-divider mb-4"></div>
                        <div class="book-description">
                            {!! $featuredBook->description !!}
                        </div>
                        @if($featuredBook->price)
                        <div class="book-price">
                            <span class="price-label">Price:</span>
                            <span class="price-value">₹{{ number_format($featuredBook->price, 2) }}</span>
                        </div>
                        @endif
                        @if($featuredBook->button_text && $featuredBook->button_link)
                        @php
                            $link = $featuredBook->button_link;
                            if (!\Illuminate\Support\Str::startsWith($link, ['http://', 'https://'])) {
                                $link = url('/' . ltrim($link, '/'));
                            }
                        @endphp
                        <a href="{{ $link }}" class="btn-primary-law btn-lg">
                            <i class="fas fa-shopping-cart me-2"></i>
                            {{ $featuredBook->button_text }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Videos Section -->
    @if($testimonialVideos && $testimonialVideos->count() > 0)
    <section class="section videos-section">
        <div class="container">
            <div class="section-header">
                <p class="section-label fade-up">Our Videos</p>
                <h2 class="section-title fade-up delay-1">Watch Our Latest Videos</h2>
                <div class="section-divider fade-up delay-2"></div>
            </div>

            <!-- First 3 videos in grid -->
            <div class="row videos-grid fade-up">
                @foreach($testimonialVideos->take(3) as $video)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="video-card">
                        <div class="video-thumbnail" data-youtube-id="{{ $video->youtube_id }}">
                            <img src="https://img.youtube.com/vi/{{ $video->youtube_id }}/maxresdefault.jpg"
                                 alt="{{ $video->title }}"
                                 onerror="this.src='https://img.youtube.com/vi/{{ $video->youtube_id }}/hqdefault.jpg'">
                            <div class="play-button">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>
                        <div class="video-info">
                            <h5 class="video-title">{{ $video->title }}</h5>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Remaining videos in horizontal scroll -->
            @if($testimonialVideos->count() > 3)
            <div class="videos-scroll-wrapper fade-up mt-4">
                <h6 class="scroll-section-title mb-3">More Videos</h6>
                <div class="swiper videos-swiper">
                    <div class="swiper-wrapper">
                        @foreach($testimonialVideos->skip(3) as $video)
                        <div class="swiper-slide">
                            <div class="video-card video-card-sm">
                                <div class="video-thumbnail" data-youtube-id="{{ $video->youtube_id }}">
                                    <img src="https://img.youtube.com/vi/{{ $video->youtube_id }}/mqdefault.jpg"
                                         alt="{{ $video->title }}"
                                         onerror="this.src='https://img.youtube.com/vi/{{ $video->youtube_id }}/default.jpg'">
                                    <div class="play-button">
                                        <i class="fas fa-play"></i>
                                    </div>
                                </div>
                                <div class="video-info">
                                    <h6 class="video-title">{{ $video->title }}</h6>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination videos-pagination"></div>
                </div>
            </div>
            @endif

            <div class="text-center mt-5 fade-up">
                <a href="{{ route('testimonials') }}" class="btn-secondary-law">
                    <i class="fas fa-video me-2"></i>
                    View All Videos
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Order Flow Section -->
    @if($orderFlowSteps && $orderFlowSteps->count() > 0)
    <section class="section order-flow-section">
        <div class="container">
            <div class="section-header">
                <p class="section-label fade-up">Easy Process</p>
                <h2 class="section-title fade-up delay-1">How to Order</h2>
                <div class="section-divider fade-up delay-2"></div>
            </div>

            <div class="order-flow-wrapper">
                <div class="order-flow-line"></div>
                <div class="order-flow-steps">
                    @foreach($orderFlowSteps as $index => $step)
                    <div class="order-flow-step fade-up delay-{{ $index }}">
                        <div class="step-icon">
                            <i class="{{ $step->icon }}"></i>
                        </div>
                        <div class="step-number">{{ $step->step_number }}</div>
                        <h4 class="step-title">{{ $step->title }}</h4>
                        @if($step->description)
                        <p class="step-description">{{ $step->description }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- WhatsApp Order Flow Section -->
    @if($whatsappFlowSteps && $whatsappFlowSteps->count() > 0)
    <section class="section whatsapp-flow-section">
        <div class="container">
            <div class="section-header">
                <p class="section-label fade-up"><i class="fab fa-whatsapp me-2"></i>Quick Order</p>
                <h2 class="section-title fade-up delay-1">Order via WhatsApp</h2>
                <div class="section-divider fade-up delay-2"></div>
                <p class="section-description fade-up delay-3">
                    Prefer ordering via WhatsApp? Follow these simple steps
                </p>
            </div>

            <div class="whatsapp-flow-wrapper">
                <div class="row justify-content-center">
                    @foreach($whatsappFlowSteps as $index => $step)
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="whatsapp-step fade-up delay-{{ $index }}">
                            <div class="whatsapp-step-icon">
                                <i class="{{ $step->icon }}"></i>
                            </div>
                            <div class="whatsapp-step-number">{{ $step->step_number }}</div>
                            <h5 class="whatsapp-step-title">{{ $step->title }}</h5>
                            @if($step->description)
                            <p class="whatsapp-step-desc">{{ $step->description }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="text-center mt-5 fade-up">
                <a href="https://wa.me/919876543210?text=Hi,%20I%20want%20to%20order%20a%20book" target="_blank" class="btn-whatsapp-order">
                    <i class="fab fa-whatsapp me-2"></i>
                    Order Now on WhatsApp
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Blog/News Section -->
    @if($blogs && $blogs->count() > 0)
    <section class="section blog-section">
        <div class="container">
            <div class="section-header">
                <p class="section-label fade-up">Latest News</p>
                <h2 class="section-title fade-up delay-1">From Our Blog</h2>
                <div class="section-divider fade-up delay-2"></div>
            </div>

            <div class="row">
                @foreach($blogs as $blog)
                <div class="col-lg-4 col-md-6 mb-4 fade-up">
                    <div class="blog-card">
                        <div class="blog-image">
                            @if($blog->image)
                            <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}">
                            @else
                            <div class="blog-image-placeholder">
                                <i class="fas fa-newspaper"></i>
                            </div>
                            @endif
                        </div>
                        <div class="blog-content">
                            <div class="blog-date">
                                <i class="far fa-calendar-alt me-2"></i>
                                {{ $blog->created_at->format('d M Y') }}
                            </div>
                            <h4 class="blog-title">{{ $blog->title }}</h4>
                            <p class="blog-excerpt">{{ $blog->excerpt }}</p>
                            <a href="{{ route('blog.show', $blog->slug) }}" class="blog-link">
                                View More <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-4 fade-up">
                <a href="{{ route('blog.index') }}" class="btn-secondary-law">
                    <i class="fas fa-newspaper me-2"></i>
                    View All Posts
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Promotional Banner -->
    @if($promoBanner && $promoBanner->is_active)
    <section class="promo-banner-section">
        <div class="promo-banner" style="background-image: url('{{ asset('storage/' . $promoBanner->image) }}');">
            <div class="promo-overlay"></div>
            <div class="container">
                <div class="promo-content fade-up">
                    @if($promoBanner->title)
                    <h2 class="promo-title">{{ $promoBanner->title }}</h2>
                    @endif
                    @if($promoBanner->description)
                    <p class="promo-description">{{ $promoBanner->description }}</p>
                    @endif
                    @if($promoBanner->button_text && $promoBanner->button_link)
                    <a href="{{ $promoBanner->button_link }}" class="btn-promo">
                        {{ $promoBanner->button_text }}
                        <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    /* Hero Slider Section */
    .hero-slider-section {
        margin-top: 80px;
    }

    .hero-swiper {
        width: 100%;
        height: 85vh;
        min-height: 600px;
        max-height: 800px;
    }

    .hero-slide {
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        position: relative;
        display: flex;
        align-items: center;
    }

    .hero-slide-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(26, 35, 50, 0.9) 0%, rgba(26, 35, 50, 0.7) 100%);
    }

    .hero-slide-content {
        position: relative;
        z-index: 2;
        max-width: 700px;
    }

    .hero-slide-title {
        font-family: var(--font-heading);
        font-size: 3.5rem;
        font-weight: 700;
        color: var(--cream-primary);
        margin-bottom: 1rem;
        line-height: 1.2;
    }

    .hero-slide-subtitle {
        font-size: 1.25rem;
        color: var(--gold-primary);
        text-transform: uppercase;
        letter-spacing: 0.2em;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .hero-slide-description {
        font-size: 1.15rem;
        color: rgba(250, 248, 243, 0.85);
        margin-bottom: 2rem;
        line-height: 1.8;
    }

    .hero-swiper .swiper-pagination-bullet {
        background: rgba(250, 248, 243, 0.5);
        width: 12px;
        height: 12px;
        opacity: 1;
    }

    .hero-swiper .swiper-pagination-bullet-active {
        background: var(--gold-primary);
        width: 30px;
        border-radius: 6px;
    }

    .hero-swiper .swiper-button-prev,
    .hero-swiper .swiper-button-next {
        color: var(--gold-primary);
        background: rgba(26, 35, 50, 0.8);
        width: 50px;
        height: 50px;
        border-radius: 50%;
        transition: var(--transition-medium);
    }

    .hero-swiper .swiper-button-prev:hover,
    .hero-swiper .swiper-button-next:hover {
        background: var(--gold-primary);
        color: var(--navy-deep);
    }

    .hero-swiper .swiper-button-prev::after,
    .hero-swiper .swiper-button-next::after {
        font-size: 1.25rem;
        font-weight: 700;
    }

    /* Fallback Hero */
    .hero-section {
        background: var(--navy-deep);
        min-height: calc(85vh - 80px);
        display: flex;
        align-items: center;
        position: relative;
        margin-top: 80px;
    }

    .min-vh-75 {
        min-height: 75vh;
    }

    .hero-subtitle {
        color: var(--gold-primary);
        text-transform: uppercase;
        letter-spacing: 0.2em;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .hero-title {
        font-size: 3.5rem;
        color: var(--cream-primary);
        margin-bottom: 1.5rem;
        line-height: 1.2;
    }

    .hero-title .highlight {
        color: var(--gold-primary);
    }

    .hero-description {
        font-size: 1.15rem;
        color: rgba(250, 248, 243, 0.85);
        margin-bottom: 2rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .hero-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .hero-pattern {
        position: absolute;
        top: 50%;
        right: 10%;
        transform: translateY(-50%);
        opacity: 0.03;
    }

    .hero-pattern i {
        font-size: 400px;
        color: var(--gold-primary);
    }

    /* About Section */
    .about-section {
        background: var(--cream-primary);
        padding: 100px 0;
    }

    .about-image-col {
        position: relative;
    }

    .about-image {
        width: 100%;
        border-radius: 8px;
        box-shadow: var(--shadow-strong);
    }

    .experience-badge {
        position: absolute;
        bottom: -20px;
        right: -20px;
        background: var(--gold-primary);
        color: var(--navy-deep);
        padding: 1.5rem;
        border-radius: 8px;
        text-align: center;
        box-shadow: var(--shadow-medium);
    }

    .experience-badge .number {
        display: block;
        font-family: var(--font-heading);
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1;
    }

    .experience-badge .text {
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .about-text-col {
        padding-left: 3rem;
    }

    .about-content {
        font-size: 1rem;
        line-height: 1.8;
        color: var(--text-primary);
    }

    .about-content p {
        margin-bottom: 1rem;
    }

    /* Featured Book Section */
    .featured-book-section {
        background: var(--navy-deep);
        padding: 100px 0;
        position: relative;
        overflow: hidden;
    }

    .featured-book-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }

    .book-pattern-left,
    .book-pattern-right {
        position: absolute;
        width: 300px;
        height: 300px;
        border: 2px solid rgba(201, 162, 39, 0.1);
        border-radius: 50%;
    }

    .book-pattern-left {
        top: 10%;
        left: -150px;
        animation: float 8s ease-in-out infinite;
    }

    .book-pattern-right {
        bottom: 10%;
        right: -150px;
        animation: float 8s ease-in-out infinite reverse;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-30px); }
    }

    .featured-book-image {
        text-align: center;
    }

    .book-image-wrapper {
        position: relative;
        display: inline-block;
    }

    .book-image {
        max-width: 100%;
        max-height: 500px;
        border-radius: 8px;
        box-shadow: 0 30px 60px rgba(0,0,0,0.4);
        transform: perspective(1000px) rotateY(-5deg);
        transition: var(--transition-slow);
    }

    .book-image-wrapper:hover .book-image {
        transform: perspective(1000px) rotateY(0deg) scale(1.02);
    }

    .book-shadow {
        position: absolute;
        bottom: -30px;
        left: 50%;
        transform: translateX(-50%);
        width: 80%;
        height: 30px;
        background: radial-gradient(ellipse, rgba(0,0,0,0.3) 0%, transparent 70%);
    }

    .featured-book-content {
        padding-left: 3rem;
    }

    .section-label-light {
        font-family: var(--font-heading);
        font-size: 0.95rem;
        color: var(--gold-primary);
        text-transform: uppercase;
        letter-spacing: 0.3em;
        margin-bottom: 1rem;
        font-weight: 500;
    }

    .book-title {
        font-family: var(--font-heading);
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        line-height: 1.2;
        color: var(--cream-primary);
    }

    .book-description {
        font-size: 1.1rem;
        line-height: 1.8;
        color: rgba(250, 248, 243, 0.85);
        margin-bottom: 2rem;
    }

    .book-description p {
        margin-bottom: 1rem;
        color: rgba(250, 248, 243, 0.85);
    }

    .book-price {
        margin-bottom: 2rem;
    }

    .price-label {
        font-size: 1rem;
        color: rgba(250, 248, 243, 0.7);
        margin-right: 0.5rem;
    }

    .price-value {
        font-family: var(--font-heading);
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--gold-primary);
    }

    /* Videos Section */
    .videos-section {
        background: var(--cream-primary);
        padding: 100px 0;
    }

    .video-card {
        background: var(--white);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-soft);
        transition: var(--transition-medium);
    }

    .video-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-strong);
    }

    .video-thumbnail {
        position: relative;
        height: 220px;
        cursor: pointer;
        overflow: hidden;
    }

    .video-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition-medium);
    }

    .video-card:hover .video-thumbnail img {
        transform: scale(1.05);
    }

    .play-button {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 70px;
        height: 70px;
        background: var(--gold-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition-medium);
        box-shadow: 0 10px 30px rgba(201, 162, 39, 0.4);
    }

    .play-button i {
        color: var(--navy-deep);
        font-size: 1.5rem;
        margin-left: 5px;
    }

    .video-card:hover .play-button {
        transform: translate(-50%, -50%) scale(1.1);
    }

    .video-info {
        padding: 1.25rem;
    }

    .video-title {
        font-size: 1rem;
        color: var(--navy-deep);
        margin-bottom: 0;
        font-weight: 600;
        line-height: 1.4;
    }

    /* Smaller video cards for horizontal scroll */
    .video-card-sm {
        height: 100%;
    }

    .video-card-sm .video-thumbnail {
        height: 140px;
    }

    .video-card-sm .play-button {
        width: 50px;
        height: 50px;
    }

    .video-card-sm .play-button i {
        font-size: 1rem;
    }

    .video-card-sm .video-info {
        padding: 1rem;
    }

    .video-card-sm .video-title {
        font-size: 0.9rem;
    }

    .scroll-section-title {
        color: var(--navy-deep);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-size: 0.9rem;
    }

    .videos-swiper {
        padding-bottom: 50px;
    }

    .videos-swiper .swiper-slide {
        height: auto;
    }

    .videos-pagination .swiper-pagination-bullet {
        background: var(--navy-muted);
        width: 10px;
        height: 10px;
        opacity: 1;
    }

    .videos-pagination .swiper-pagination-bullet-active {
        background: var(--gold-primary);
        width: 25px;
        border-radius: 5px;
    }

    /* Order Flow Section */
    .order-flow-section {
        background: var(--white);
        padding: 100px 0;
    }

    .order-flow-wrapper {
        position: relative;
        padding-top: 3rem;
    }

    .order-flow-line {
        position: absolute;
        top: calc(3rem + 45px);
        left: 10%;
        width: 80%;
        height: 3px;
        background: linear-gradient(90deg, var(--gold-light), var(--gold-primary), var(--gold-light));
        z-index: 1;
    }

    .order-flow-steps {
        display: flex;
        justify-content: space-between;
        position: relative;
        z-index: 2;
    }

    .order-flow-step {
        text-align: center;
        flex: 1;
        max-width: 200px;
        padding: 0 1rem;
    }

    .step-icon {
        width: 90px;
        height: 90px;
        background: var(--navy-deep);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: var(--shadow-medium);
        position: relative;
        transition: var(--transition-medium);
    }

    .step-icon i {
        font-size: 2rem;
        color: var(--gold-primary);
    }

    .order-flow-step:hover .step-icon {
        transform: translateY(-5px);
        box-shadow: var(--shadow-strong);
    }

    .step-number {
        position: absolute;
        top: -10px;
        right: -10px;
        width: 30px;
        height: 30px;
        background: var(--gold-primary);
        color: var(--navy-deep);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
    }

    .step-title {
        font-size: 1.1rem;
        color: var(--navy-deep);
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .step-description {
        font-size: 0.9rem;
        color: var(--text-muted);
        margin: 0;
        line-height: 1.5;
    }

    /* WhatsApp Flow Section */
    .whatsapp-flow-section {
        background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
        padding: 100px 0;
        position: relative;
        overflow: hidden;
    }

    .whatsapp-flow-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.3;
    }

    .whatsapp-flow-section .section-label,
    .whatsapp-flow-section .section-title,
    .whatsapp-flow-section .section-description {
        color: var(--white);
    }

    .whatsapp-flow-section .section-divider {
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
    }

    .whatsapp-flow-wrapper {
        margin-top: 3rem;
    }

    .whatsapp-step {
        text-align: center;
        padding: 2rem 1rem;
        position: relative;
    }

    .whatsapp-step-icon {
        width: 80px;
        height: 80px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255,255,255,0.3);
        transition: var(--transition-medium);
    }

    .whatsapp-step-icon i {
        font-size: 2rem;
        color: var(--white);
    }

    .whatsapp-step:hover .whatsapp-step-icon {
        transform: scale(1.1);
        background: rgba(255,255,255,0.3);
    }

    .whatsapp-step-number {
        position: absolute;
        top: 2rem;
        right: calc(50% - 55px);
        width: 28px;
        height: 28px;
        background: var(--white);
        color: #128C7E;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
    }

    .whatsapp-step-title {
        font-size: 1.1rem;
        color: var(--white);
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .whatsapp-step-desc {
        font-size: 0.9rem;
        color: rgba(255,255,255,0.85);
        margin: 0;
        line-height: 1.5;
    }

    .btn-whatsapp-order {
        display: inline-flex;
        align-items: center;
        background: var(--white);
        color: #128C7E;
        padding: 1rem 2.5rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1.1rem;
        text-decoration: none;
        transition: var(--transition-medium);
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .btn-whatsapp-order:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        color: #128C7E;
    }

    .btn-whatsapp-order i {
        font-size: 1.5rem;
    }

    /* Blog Section */
    .blog-section {
        background: var(--cream-primary);
        padding: 100px 0;
    }

    .blog-card {
        background: var(--white);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-soft);
        transition: var(--transition-medium);
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .blog-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-strong);
    }

    .blog-image {
        height: 200px;
        overflow: hidden;
    }

    .blog-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition-medium);
    }

    .blog-card:hover .blog-image img {
        transform: scale(1.05);
    }

    .blog-image-placeholder {
        width: 100%;
        height: 100%;
        background: var(--cream-primary);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .blog-image-placeholder i {
        font-size: 3rem;
        color: var(--navy-muted);
    }

    .blog-content {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .blog-date {
        font-size: 0.85rem;
        color: var(--gold-dark);
        margin-bottom: 0.75rem;
    }

    .blog-title {
        font-family: var(--font-heading);
        font-size: 1.2rem;
        color: var(--navy-deep);
        margin-bottom: 0.75rem;
        line-height: 1.4;
    }

    .blog-excerpt {
        color: var(--text-muted);
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 1rem;
        flex: 1;
    }

    .blog-link {
        color: var(--gold-primary);
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: var(--transition-fast);
    }

    .blog-link:hover {
        color: var(--navy-deep);
    }

    .blog-link i {
        transition: var(--transition-fast);
    }

    .blog-link:hover i {
        transform: translateX(5px);
    }

    /* Promo Banner Section */
    .promo-banner-section {
        padding: 0;
    }

    .promo-banner {
        min-height: 400px;
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .promo-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(26, 35, 50, 0.9) 0%, rgba(26, 35, 50, 0.7) 100%);
    }

    .promo-content {
        position: relative;
        z-index: 2;
        text-align: center;
        max-width: 800px;
        padding: 60px 20px;
    }

    .promo-title {
        font-family: var(--font-heading);
        font-size: 2.5rem;
        color: var(--cream-primary);
        margin-bottom: 1rem;
    }

    .promo-description {
        font-size: 1.15rem;
        color: rgba(250, 248, 243, 0.85);
        margin-bottom: 2rem;
    }

    .btn-promo {
        display: inline-flex;
        align-items: center;
        background: var(--gold-primary);
        color: var(--navy-deep);
        padding: 1rem 2.5rem;
        border-radius: 4px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        text-decoration: none;
        transition: var(--transition-medium);
    }

    .btn-promo:hover {
        background: var(--gold-light);
        transform: translateY(-3px);
    }

    /* Video Modal */
    .video-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.95);
        z-index: 10000;
        align-items: center;
        justify-content: center;
    }

    .video-modal.active {
        display: flex;
    }

    .video-modal-content {
        width: 90%;
        max-width: 900px;
        position: relative;
    }

    .video-modal-close {
        position: absolute;
        top: -50px;
        right: 0;
        background: none;
        border: none;
        color: white;
        font-size: 2rem;
        cursor: pointer;
        transition: var(--transition-fast);
    }

    .video-modal-close:hover {
        color: var(--gold-primary);
    }

    .video-container {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
    }

    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 8px;
    }

    /* Responsive */
    @media (max-width: 1199px) {
        .hero-slide-title {
            font-size: 3rem;
        }
    }

    @media (max-width: 991px) {
        .hero-slider-section {
            margin-top: 70px;
        }

        .hero-swiper {
            height: 70vh;
            min-height: 500px;
        }

        .hero-slide-title {
            font-size: 2.5rem;
        }

        .about-text-col {
            padding-left: 0;
            margin-top: 3rem;
        }

        .featured-book-content {
            padding-left: 0;
            margin-top: 3rem;
        }

        .order-flow-line {
            display: none;
        }

        .order-flow-steps {
            flex-wrap: wrap;
            gap: 2rem;
        }

        .order-flow-step {
            flex: 0 0 50%;
            max-width: none;
        }

        .step-number {
            position: relative;
            top: auto;
            right: auto;
            margin: 0 auto 1rem;
        }
    }

    @media (max-width: 767px) {
        .hero-swiper {
            height: 60vh;
            min-height: 450px;
        }

        .hero-slide-title {
            font-size: 2rem;
        }

        .hero-slide-description {
            font-size: 1rem;
        }

        .hero-title {
            font-size: 2.5rem;
        }

        .book-title {
            font-size: 2rem;
        }

        .order-flow-step {
            flex: 0 0 100%;
        }

        .promo-title {
            font-size: 1.75rem;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hero Slider
    const heroSwiper = new Swiper('.hero-swiper', {
        loop: true,
        autoplay: {
            delay: 6000,
            disableOnInteraction: false,
        },
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        },
        speed: 1000,
        pagination: {
            el: '.hero-swiper .swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.hero-swiper .swiper-button-next',
            prevEl: '.hero-swiper .swiper-button-prev',
        },
    });

    // Videos Slider (for remaining videos after first 3)
    const videosSwiper = new Swiper('.videos-swiper', {
        slidesPerView: 1.5,
        spaceBetween: 20,
        loop: false,
        pagination: {
            el: '.videos-pagination',
            clickable: true,
        },
        breakpoints: {
            480: {
                slidesPerView: 2.5,
            },
            768: {
                slidesPerView: 3.5,
            },
            1024: {
                slidesPerView: 4.5,
            },
        },
    });

    // Video Modal
    const videoModal = document.createElement('div');
    videoModal.className = 'video-modal';
    videoModal.innerHTML = `
        <div class="video-modal-content">
            <button class="video-modal-close">&times;</button>
            <div class="video-container">
                <iframe id="youtube-iframe" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
    `;
    document.body.appendChild(videoModal);

    // Video thumbnail click handler
    document.querySelectorAll('.video-thumbnail').forEach(function(thumbnail) {
        thumbnail.addEventListener('click', function() {
            const youtubeId = this.dataset.youtubeId;
            const iframe = document.getElementById('youtube-iframe');
            iframe.src = `https://www.youtube.com/embed/${youtubeId}?autoplay=1`;
            videoModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });

    // Close modal
    videoModal.querySelector('.video-modal-close').addEventListener('click', function() {
        closeVideoModal();
    });

    videoModal.addEventListener('click', function(e) {
        if (e.target === videoModal) {
            closeVideoModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && videoModal.classList.contains('active')) {
            closeVideoModal();
        }
    });

    function closeVideoModal() {
        videoModal.classList.remove('active');
        document.getElementById('youtube-iframe').src = '';
        document.body.style.overflow = '';
    }
});
</script>
@endpush
