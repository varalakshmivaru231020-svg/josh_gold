@extends('layouts.lawfirm')

@section('title', $blog->title . ' - Bengaluru Law House')

@section('content')
<!-- Blog Hero Section (Matches Products Hero) -->
<section class="page-hero">
    <div class="hero-pattern-left"></div>
    <div class="hero-pattern-right"></div>
    <div class="container">
        <div class="page-hero-content fade-up">
            <div class="page-hero-badges mb-4">
                <span class="hero-badge">News & Updates</span>
                <span class="hero-badge">{{ $blog->created_at->format('M d, Y') }}</span>
            </div>
            
            <h1 class="page-hero-title mb-4">{{ $blog->title }}</h1>
            
            @if($blog->excerpt)
            <p class="page-hero-description">
                {{ $blog->excerpt }}
            </p>
            @endif
        </div>
    </div>
</section>

<!-- Blog Content Section -->
<section class="blog-content-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Featured Image -->
                @if($blog->image)
                    <div class="blog-featured-image-wrapper fade-up">
                        <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="blog-featured-image">
                        <div class="image-shadow"></div>
                    </div>
                @endif

                <!-- Article Body -->
                <div class="article-body fade-up">
                    {!! $blog->description !!}
                </div>

                <!-- Footer / Share -->
                <div class="blog-footer fade-up">
                    <div class="gold-divider mb-4"></div>
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <a href="{{ route('blog.index') }}" class="btn-back">
                            <i class="fas fa-arrow-left me-2"></i> Back to News
                        </a>
                        <div class="blog-share">
                            <span class="share-label">Share Article:</span>
                            <div class="share-icons">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="share-icon"><i class="fab fa-facebook-f"></i></a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($blog->title) }}" target="_blank" class="share-icon"><i class="fab fa-twitter"></i></a>
                                <a href="https://wa.me/?text={{ urlencode($blog->title . ' - ' . request()->url()) }}" target="_blank" class="share-icon"><i class="fab fa-whatsapp"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- Related Articles -->
@if($relatedBlogs->count() > 0)
<section class="related-section">
    <div class="container">
        <div class="section-header text-center mb-5">
            <p class="section-label-gold">Continue Reading</p>
            <h3 class="section-title">Related Articles</h3>
        </div>
        
        <div class="row g-4 justify-content-center">
            @foreach($relatedBlogs as $related)
                <div class="col-md-4">
                    <a href="{{ route('blog.show', $related->slug) }}" class="related-card">
                        <div class="related-image-wrapper">
                            @if($related->image)
                                <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->title }}">
                            @else
                                <div class="placeholder-image">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                            @endif
                        </div>
                        <div class="related-content">
                            <span class="related-date">{{ $related->created_at->format('M d, Y') }}</span>
                            <h5 class="related-title">{{ $related->title }}</h5>
                            <span class="read-more">Read Article <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@push('styles')
<style>
    /* Reuse Hero Styles from Index */
    .page-hero {
        background: var(--navy-deep);
        padding: 140px 0 100px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .hero-pattern-left, .hero-pattern-right {
        position: absolute;
        width: 400px;
        height: 400px;
        border: 2px solid rgba(201, 162, 39, 0.1);
        border-radius: 50%;
        pointer-events: none;
    }
    .hero-pattern-left { top: 5%; left: -200px; animation: float 8s ease-in-out infinite; }
    .hero-pattern-right { bottom: 5%; right: -200px; animation: float 8s ease-in-out infinite reverse; }
    
    .page-hero-title {
        font-family: var(--font-heading);
        font-size: 3rem;
        font-weight: 700;
        color: var(--cream-primary);
        line-height: 1.2;
    }

    .page-hero-description {
        font-size: 1.15rem;
        color: rgba(250, 248, 243, 0.75);
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.8;
    }

    .hero-badge {
        display: inline-block;
        background: rgba(201, 162, 39, 0.15);
        color: var(--gold-primary);
        padding: 0.5rem 1.2rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        border: 1px solid rgba(201, 162, 39, 0.3);
        margin: 0 0.25rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-30px); }
    }

    /* Content Section */
    .blog-content-section {
        background: var(--cream-primary);
        padding: 0 0 80px;
        position: relative;
    }

    .blog-featured-image-wrapper {
        margin-top: -60px;
        position: relative;
        margin-bottom: 3rem;
        text-align: center;
    }

    .blog-featured-image {
        width: 100%;
        border-radius: 12px;
        box-shadow: 0 20px 40px rgba(10, 22, 40, 0.15);
        border: 4px solid var(--white);
    }

    .article-body {
        font-size: 1.1rem;
        line-height: 1.9;
        color: var(--text-primary);
        font-family: 'Inter', sans-serif;
    }

    .article-body p { margin-bottom: 1.5rem; }
    
    .article-body h2, .article-body h3 {
        font-family: var(--font-heading);
        color: var(--navy-deep);
        margin-top: 2.5rem;
        margin-bottom: 1rem;
        font-weight: 700;
    }

    .article-body blockquote {
        border-left: 4px solid var(--gold-primary);
        padding: 1.5rem 2rem;
        margin: 2rem 0;
        font-style: italic;
        background: rgba(201, 162, 39, 0.1);
        border-radius: 0 8px 8px 0;
        color: var(--navy-deep);
        font-family: var(--font-heading);
        font-size: 1.2rem;
    }

    .article-body img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 2rem 0;
        box-shadow: var(--shadow-soft);
    }

    /* Footer & Share */
    .blog-footer {
        margin-top: 4rem;
        padding-top: 2rem;
    }

    .gold-divider {
        height: 1px;
        background: rgba(201, 162, 39, 0.3);
        width: 100%;
    }

    .btn-back {
        color: var(--navy-primary);
        text-decoration: none;
        font-weight: 600;
        transition: 0.3s;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
    }
    .btn-back:hover { color: var(--gold-primary); }

    .share-label {
        font-family: var(--font-heading);
        color: var(--navy-deep);
        font-weight: 600;
        margin-right: 0.5rem;
    }

    .share-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--navy-deep);
        color: var(--gold-primary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-left: 0.5rem;
        transition: 0.3s;
        border: 1px solid var(--gold-primary);
    }

    .share-icon:hover {
        background: var(--gold-primary);
        color: var(--navy-deep);
        transform: translateY(-3px);
    }

    /* Related Section */
    .related-section {
        padding: 80px 0;
        background: var(--white);
        border-top: 1px solid rgba(201, 162, 39, 0.1);
    }

    .section-label-gold {
        font-family: var(--font-heading);
        font-size: 0.9rem;
        color: var(--gold-primary);
        text-transform: uppercase;
        letter-spacing: 0.2em;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .section-title {
        font-family: var(--font-heading);
        font-size: 2.5rem;
        color: var(--navy-deep);
        margin-bottom: 1rem;
    }

    .related-card {
        display: block;
        text-decoration: none;
        background: var(--cream-primary);
        border-radius: 12px;
        overflow: hidden;
        transition: 0.4s;
        border: 1px solid transparent;
        height: 100%;
    }

    .related-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(10, 22, 40, 0.1);
        border-color: rgba(201, 162, 39, 0.3);
    }

    .related-image-wrapper {
        height: 200px;
        overflow: hidden;
        position: relative;
    }

    .related-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: 0.5s;
    }

    .related-card:hover .related-image-wrapper img {
        transform: scale(1.1);
    }

    .related-content {
        padding: 1.5rem;
    }

    .related-date {
        font-size: 0.8rem;
        color: var(--gold-dark);
        text-transform: uppercase;
        letter-spacing: 1px;
        display: block;
        margin-bottom: 0.5rem;
    }

    .related-title {
        font-family: var(--font-heading);
        font-size: 1.25rem;
        color: var(--navy-deep);
        margin-bottom: 1rem;
        line-height: 1.4;
    }

    .read-more {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--navy-primary);
        transition: 0.3s;
    }

    .related-card:hover .read-more {
        color: var(--gold-primary);
        padding-left: 5px;
    }
    
    @media (max-width: 768px) {
        .page-hero-title { font-size: 2rem; }
        .page-hero { padding: 120px 0 80px; }
        .blog-featured-image-wrapper { margin-top: -40px; }
    }
</style>
@endpush
