@extends('layouts.lawfirm')

@section('title', $blog->title . ' | Bengaluru Law House')

@section('content')
<section class="blog-detail-page">
    <div class="blog-hero-header">
        <div class="container text-center" data-aos="fade-up">
            <div class="blog-meta-badges mb-4">
                <span class="label-gold">News & Intelligence</span>
                <span class="sep-dot"></span>
                <span class="label-white">{{ $blog->created_at->format('M d, Y') }}</span>
            </div>
            <h1 class="heading-premium text-white">{{ $blog->title }}</h1>
            <div class="accent-line-gold mx-auto"></div>
        </div>
    </div>

    <div class="container py-5 mt-n5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Featured Image -->
                @if($blog->image)
                    <div class="blog-featured-wrap mb-5" data-aos="fade-up">
                        <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="img-fluid rounded-4 shadow-lg border border-white border-4">
                    </div>
                @endif

                <div class="row">
                    <div class="col-lg-8" data-aos="fade-right">
                        <!-- Article Body -->
                        <div class="blog-content-premium bg-white p-5 rounded-4 shadow-sm border border-light">
                            <div class="rich-text-legal">
                                {!! $blog->description !!}
                            </div>
                            
                            <!-- Share Section -->
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
                        </div>
                    </div>

                    <div class="col-lg-4 mt-5 mt-lg-0" data-aos="fade-left">
                        <div class="blog-sidebar">
                            <h4 class="font-display text-navy mb-4 border-bottom pb-3">Related Intel</h4>
                            @foreach($relatedBlogs as $related)
                                <a href="{{ route('blog.show', $related->slug) }}" class="related-intel-card mb-4">
                                    <div class="intel-thumb">
                                        <img src="{{ $related->image ? asset('storage/' . $related->image) : asset('images/placeholder-intel.jpg') }}" alt="{{ $related->title }}">
                                    </div>
                                    <div class="intel-info">
                                        <span class="small text-gold fw-bold">{{ $related->created_at->format('M d') }}</span>
                                        <h5>{{ \Illuminate\Support\Str::limit($related->title, 50) }}</h5>
                                    </div>
                                </a>
                            @endforeach

                            <div class="newsletter-sidebar-card bg-navy p-4 rounded-4 text-white mt-5">
                                <h4 class="font-display text-gold mb-3">Newsletter</h4>
                                <p class="small opacity-75 mb-4">Receive weekly judicial summaries and publication releases.</p>
                                <form action="#" class="sidebar-form">
                                    <input type="email" placeholder="Your Email" class="form-control bg-transparent border-secondary text-white mb-3">
                                    <button class="btn-primary-gold w-100">Subscribe</button>
                                </form>
                            </div>
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
    
    .blog-hero-header { 
        background: var(--navy); 
        padding: 180px 0 140px; 
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

    .blog-meta-badges { display: flex; align-items: center; justify-content: center; gap: 15px; position: relative; z-index: 2; }
    .sep-dot { width: 5px; height: 5px; background: var(--gold); border-radius: 50%; }
    .label-white { color: white; opacity: 0.8; font-size: 0.8rem; letter-spacing: 2px; text-transform: uppercase; font-weight: 700; }

    .blog-featured-wrap { position: relative; z-index: 5; text-align: center; }
    .blog-featured-wrap img { max-height: 500px; width: 100%; object-fit: cover; }

    .rich-text-legal { font-size: 1.15rem; line-height: 1.9; color: var(--text-muted); }
    .rich-text-legal p { margin-bottom: 30px; }
    .rich-text-legal h2, .rich-text-legal h3 { font-family: var(--font-display); color: var(--navy); font-weight: 700; margin: 40px 0 20px; }
    .rich-text-legal blockquote { border-left: 5px solid var(--gold); padding: 30px 40px; background: var(--cream); margin: 40px 0; font-family: var(--font-display); font-style: italic; font-size: 1.4rem; color: var(--navy); border-radius: 0 20px 20px 0; }

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

    .mt-n5 { margin-top: -100px !important; }
    .accent-line-gold { width: 80px; height: 3px; background: var(--gold); margin-top: 25px; position: relative; z-index: 2; }

    @media (max-width: 767px) {
        .blog-content-premium { padding: 30px; }
        .heading-premium { font-size: 2.2rem; }
    }
</style>
@endpush
