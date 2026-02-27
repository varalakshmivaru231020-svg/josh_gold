@extends('layouts.lawfirm')

@section('title', $blog->title . ' | Bengaluru Law House')

@section('content')
<section class="blog-detail-page">
    <div class="blog-hero-header">
        <div class="container text-center" data-aos="fade-up">
            @php
                $readTime = max(1, ceil(str_word_count(strip_tags($blog->description)) / 200));
            @endphp

            <nav class="blog-breadcrumb mb-3">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <a href="{{ route('blog.index') }}">Blog</a>
                <span>/</span>
                <span class="current">{{ $blog->title }}</span>
            </nav>

            <div class="blog-meta-badges mb-3">
                <span class="label-gold">News &amp; Intelligence</span>
                <span class="sep-dot"></span>
                <span class="label-white">{{ strtoupper($blog->created_at->format('M d, Y')) }}</span>
            </div>

            <h1 class="heading-premium text-white mb-2">{{ $blog->title }}</h1>
            <div class="accent-line-gold mx-auto mb-3"></div>

            <div class="blog-hero-submeta">
                <span>By Bengaluru Law House</span>
                <span class="sep-dot small"></span>
                <span>{{ $readTime }} min read</span>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-8" data-aos="fade-right">
                        <!-- Article Body -->
                        <div class="blog-content-premium bg-white p-5 rounded-4 shadow-sm border border-light">
                            @if($blog->image)
                                <div class="blog-featured-inside mb-4" data-aos="fade-up">
                                    <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="img-fluid shadow-sm w-100 blog-featured-image">
                                </div>
                            @endif

                            <div class="rich-text-legal">
                                {!! $blog->description !!}
                            </div>

                            <div class="blog-end-meta mt-5 pt-4 border-top">
                                <div class="blog-tags mb-3">
                                    <span class="tag-pill">Legal Insight</span>
                                    <span class="tag-pill">News &amp; Intelligence</span>
                                </div>

                                <div class="blog-prev-next d-flex flex-column flex-md-row gap-3">
                                    @if($previousBlog)
                                        <a href="{{ route('blog.show', $previousBlog->slug) }}" class="blog-nav-card prev flex-fill">
                                            <small class="nav-label">Previous</small>
                                            <h5>{{ \Illuminate\Support\Str::limit($previousBlog->title, 60) }}</h5>
                                        </a>
                                    @endif

                                    @if($nextBlog)
                                        <a href="{{ route('blog.show', $nextBlog->slug) }}" class="blog-nav-card next flex-fill text-md-end">
                                            <small class="nav-label">Next</small>
                                            <h5>{{ \Illuminate\Support\Str::limit($nextBlog->title, 60) }}</h5>
                                        </a>
                                    @endif
                                </div>
                            </div>

                            @if($relatedBlogs->count())
                                <div class="related-articles-section mt-5 pt-4 border-top">
                                    <h3 class="font-display text-navy mb-4">Related Articles</h3>
                                    <div class="row g-4">
                                        @foreach($relatedBlogs as $related)
                                            <div class="col-md-4">
                                                <a href="{{ route('blog.show', $related->slug) }}" class="related-article-card h-100 d-block">
                                                    <div class="related-thumb">
                                                        <img src="{{ $related->image ? asset('storage/' . $related->image) : asset('images/placeholder-intel.jpg') }}" alt="{{ $related->title }}">
                                                    </div>
                                                    <div class="related-body">
                                                        <span class="small text-gold fw-bold d-block mb-1">{{ $related->created_at->format('M d, Y') }}</span>
                                                        <h5>{{ \Illuminate\Support\Str::limit($related->title, 70) }}</h5>
                                                        <p>{{ \Illuminate\Support\Str::limit(strip_tags($related->excerpt), 80) }}</p>
                                                        <span class="related-read-more">Read More <i class="fas fa-arrow-right"></i></span>
                                                    </div>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                            {{-- Share Section (hidden as requested)
                            <div class="blog-share-wrap mt-5 pt-5 border-top">
                                <h5 class="font-display text-navy mb-4">Share this Commentary</h5>
                                <div class="share-flex">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="share-btn fb"><i class="fab fa-facebook-f"></i></a>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($blog->title) }}" target="_blank" class="share-btn tw"><i class="fab fa-twitter"></i></a>
                                    <a href="https://wa.me/?text={{ urlencode($blog->title . ' - ' . request()->url()) }}" target="_blank" class="share-btn wa"><i class="fab fa-whatsapp"></i></a>
                                    <a href="{{ route('blog.index') }}" class="btn-outline-navy ms-auto">
                                        <i class="fas fa-arrow-left me-2"></i> Back to Archives
                                    </a>
                                </div>
                            </div>
                            --}}
                        </div>
                    </div>

                    <div class="col-lg-4 mt-5 mt-lg-0" data-aos="fade-left">
                        <div class="blog-sidebar">
                            <h4 class="font-display text-navy mb-4 border-bottom pb-3">Recent Posts</h4>
                            @foreach($recentBlogs as $recent)
                                <a href="{{ route('blog.show', $recent->slug) }}" class="related-intel-card mb-4">
                                    <div class="intel-thumb">
                                        <img src="{{ $recent->image ? asset('storage/' . $recent->image) : asset('images/placeholder-intel.jpg') }}" alt="{{ $recent->title }}">
                                    </div>
                                    <div class="intel-info">
                                        <span class="small text-gold fw-bold">{{ $recent->created_at->format('M d, Y') }}</span>
                                        <h5>{{ \Illuminate\Support\Str::limit($recent->title, 50) }}</h5>
                                    </div>
                                </a>
                            @endforeach

                            {{-- <div class="newsletter-sidebar-card bg-navy p-4 rounded-4 text-white mt-5">
                                <h4 class="font-display text-gold mb-3">Newsletter</h4>
                                <p class="small opacity-75 mb-4">Receive weekly judicial summaries and publication releases.</p>
                                <form action="#" class="sidebar-form">
                                    <input type="email" placeholder="Your Email" class="form-control bg-transparent border-secondary text-white mb-3">
                                    <button class="btn-primary-gold w-100">Subscribe</button>
                                </form>
                            </div> --}}
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
    .blog-detail-page { background: var(--cream); min-height: 80vh; padding-bottom: 120px; }
    .blog-detail-page .container { max-width: 1400px; }
    
    .blog-hero-header { 
        background: 
            linear-gradient(rgba(11,29,58,0.9) 0%, rgba(19,43,82,0.96) 100%),
            url('/images/publications-banner.jpg') center center / cover no-repeat;
        padding: 160px 0 130px; 
        position:relative; 
        overflow:hidden; 
    }
    .blog-hero-header::before {
        content: ''; position: absolute; inset: 0;
        background-image: 
            linear-gradient(rgba(201, 168, 76, 0.04) 1px, transparent 1px),
            linear-gradient(90deg, rgba(201, 168, 76, 0.04) 1px, transparent 1px);
        background-size: 50px 50px;
    }
    .blog-hero-header::after { 
        content:''; position:absolute; inset:0; 
        background: radial-gradient(circle at 50% 120%, rgba(201,168,76,0.15) 0%, transparent 60%); 
    }

    .blog-breadcrumb { font-size: 0.8rem; letter-spacing: 1.8px; text-transform: uppercase; color: rgba(201,168,76,0.8); position: relative; z-index: 2; }
    .blog-breadcrumb a { color: rgba(201,168,76,0.9); text-decoration: none; }
    .blog-breadcrumb a:hover { text-decoration: underline; }
    .blog-breadcrumb span { margin: 0 4px; }
    .blog-breadcrumb .current { color: #ffffff; opacity: 0.85; }

    .blog-meta-badges { display: flex; align-items: center; justify-content: center; gap: 15px; position: relative; z-index: 2; }
    .sep-dot { width: 5px; height: 5px; background: var(--gold); border-radius: 50%; }
    .label-white { color: white; opacity: 0.8; font-size: 0.8rem; letter-spacing: 2px; text-transform: uppercase; font-weight: 700; }

    .blog-hero-submeta { color: rgba(255,255,255,0.85); font-size: 0.9rem; letter-spacing: 1.2px; text-transform: uppercase; display: flex; justify-content: center; align-items: center; gap: 10px; position: relative; z-index: 2; }
    .blog-hero-submeta .sep-dot.small { width: 4px; height: 4px; }

    .blog-content-premium { position: relative; z-index: 5; box-shadow: 0 18px 45px rgba(15,23,42,0.12); }
    .blog-featured-image { max-height: 450px; width: 100%; object-fit: cover; border-radius: 18px; }

    .rich-text-legal { font-size: 1.05rem; line-height: 1.8; color: var(--text-muted); }
    .rich-text-legal p { margin-bottom: 30px; }
    .rich-text-legal h2, .rich-text-legal h3 { font-family: var(--font-display); color: var(--navy); font-weight: 700; margin: 40px 0 20px; }
    .rich-text-legal blockquote { border-left: 5px solid var(--gold); padding: 30px 40px; background: #f9f3e5; margin: 40px 0; font-family: var(--font-display); font-style: italic; font-size: 1.3rem; color: var(--navy); border-radius: 0 20px 20px 0; }

    .blog-end-meta { margin-top: 40px; }
    .blog-tags .tag-pill { display: inline-block; padding: 6px 14px; border-radius: 999px; background: rgba(201,168,76,0.08); color: var(--navy); font-size: 0.8rem; font-weight: 600; letter-spacing: 1.5px; text-transform: uppercase; margin-right: 8px; }

    .blog-prev-next { margin-top: 10px; }
    .blog-nav-card { background: #f8fafc; border-radius: 16px; padding: 16px 20px; text-decoration: none; color: var(--navy); border: 1px solid rgba(15,23,42,0.06); transition: 0.25s ease; display: block; }
    .blog-nav-card:hover { background: #ffffff; box-shadow: 0 12px 30px rgba(15,23,42,0.12); transform: translateY(-2px); }
    .blog-nav-card .nav-label { text-transform: uppercase; letter-spacing: 1.6px; font-size: 0.75rem; color: var(--text-muted); }
    .blog-nav-card h5 { font-family: var(--font-display); font-size: 1rem; margin: 6px 0 0; }

    .related-articles-section h3 { font-size: 1.4rem; }
    .related-article-card { border-radius: 18px; overflow: hidden; background: #ffffff; border: 1px solid rgba(15,23,42,0.06); text-decoration: none; color: inherit; transition: 0.3s; }
    .related-article-card:hover { box-shadow: 0 18px 40px rgba(15,23,42,0.15); transform: translateY(-6px); }
    .related-thumb { height: 160px; overflow: hidden; }
    .related-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .related-body { padding: 16px 18px 18px; }
    .related-body h5 { font-family: var(--font-display); font-size: 1rem; margin-bottom: 6px; }
    .related-body p { font-size: 0.9rem; color: var(--text-muted); margin-bottom: 10px; }
    .related-read-more { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.8px; color: var(--gold-dark); font-weight: 700; }

    .share-flex { display: flex; align-items: center; gap: 15px; flex-wrap: wrap; }
    .share-btn { width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; color: white; transition: 0.3s; text-decoration: none; }
    .share-btn:hover { transform: translateY(-5px); opacity: 0.9; }
    .share-btn.fb { background: #1877f2; }
    .share-btn.tw { background: #000000; }
    .share-btn.wa { background: #25d366; }

    .related-intel-card { display: flex; gap: 15px; text-decoration: none; align-items: center; transition: 0.3s; }
    .related-intel-card:hover { transform: translateX(5px); }
    .intel-thumb { width: 80px; height: 80px; border-radius: 12px; overflow: hidden; flex-shrink: 0; }
    .intel-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .intel-info h5 { font-family: var(--font-display); color: var(--navy); font-size: 1rem; margin: 5px 0 0; font-weight: 700; line-height: 1.3; }

    /* Removed .mt-n5 negative margin to keep cards below the banner */
    .accent-line-gold { width: 80px; height: 3px; background: var(--gold); margin-top: 25px; position: relative; z-index: 2; }

    @media (max-width: 767px) {
        .blog-content-premium { padding: 30px; }
        .heading-premium { font-size: 2.2rem; }
    }
</style>
@endpush
