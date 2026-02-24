@extends('layouts.lawfirm')

@section('title', 'Legal Intelligence & News | Bengaluru Law House')

@section('content')
<section class="blog-luxury-page">
    <div class="blog-hero-header">
        <div class="container text-center" data-aos="fade-up">
            <div class="label-gold">KNOWLEDGE HUB</div>
            <h1 class="heading-premium text-white">Legal Intelligence Archives</h1>
            <div class="accent-line-gold mx-auto"></div>
            <p class="hero-subheader text-white opacity-75">Categorical insights into the evolving landscape of Indian Law & Jurisprudence</p>
        </div>
    </div>

    <div class="container py-5 mt-n5">
        <div class="row g-4">
            @forelse($blogs as $blog)
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up">
                <a href="{{ route('blog.show', $blog->slug) }}" class="news-luxury-card h-100">
                    <div class="news-img-wrap">
                        <img src="{{ $blog->image ? asset('storage/' . $blog->image) : asset('blr.jpg') }}" alt="{{ $blog->title }}">
                        <div class="news-metadata">{{ $blog->created_at->format('M d, Y') }}</div>
                    </div>
                    <div class="news-body">
                        <h3>{{ $blog->title }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit($blog->excerpt, 120) }}</p>
                        <span class="read-analysis">Full Commentary <i class="fas fa-arrow-right"></i></span>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12" data-aos="fade-up">
                <div class="empty-intelligence text-center">
                    <i class="fas fa-scroll fa-4x text-gold mb-4"></i>
                    <h3 class="text-navy">No Commentaries Found</h3>
                    <p class="text-muted">Archives are currently being organized. Please return shortly.</p>
                    <a href="{{ route('home') }}" class="btn-gold-action mt-4">Return Home</a>
                </div>
            </div>
            @endforelse
        </div>

        @if($blogs->hasPages())
        <div class="pagination-premium mt-5" data-aos="fade-up">
            {{ $blogs->links() }}
        </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
    .blog-luxury-page { background: var(--cream); min-height: 80vh; padding-bottom: 120px; }
    
    .blog-hero-header { 
        background: var(--navy); 
        padding: 160px 0 120px; 
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
    
    .hero-subheader { max-width: 700px; margin: 0 auto; font-family: var(--font-display); font-size: 1.4rem; font-style: italic; position: relative; z-index: 2; }
    
    .news-luxury-card { display:block; text-decoration:none; background:white; border-radius:24px; overflow:hidden; border: 1px solid rgba(0,0,0,0.05); transition: all 0.4s cubic-bezier(0.2, 0.8, 0.2, 1); border-bottom: 4px solid var(--gold); box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
    .news-luxury-card:hover { transform: translateY(-12px); box-shadow: 0 30px 60px rgba(0,0,0,0.12); }
    
    .news-img-wrap { position:relative; height:260px; overflow:hidden; }
    .news-img-wrap img { width:100%; height:100%; object-fit:cover; transition: all 0.5s ease; }
    .news-luxury-card:hover .news-img-wrap img { transform: scale(1.1); }
    
    .news-metadata { position:absolute; bottom:15px; right:15px; background:white; color:var(--navy); padding:6px 20px; border-radius:50px; font-size:0.8rem; font-weight:700; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    
    .news-body { padding: 35px; }
    .news-body h3 { font-family: var(--font-display); font-size: 1.7rem; color: var(--navy); margin-bottom: 15px; font-weight: 700; line-height: 1.3; }
    .news-body p { color: var(--text-muted); font-size: 1rem; line-height: 1.7; margin-bottom: 25px; height: 5.1em; overflow: hidden; }
    
    .read-analysis { color: var(--gold-dark); font-weight: 700; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 2px; display: inline-flex; align-items: center; gap: 10px; transition: all 0.3s; }
    .news-luxury-card:hover .read-analysis { color: var(--navy); transform: translateX(5px); }

    .empty-intelligence { background: white; padding: 100px; border-radius: 40px; border: 2px dashed rgba(201,168,76,0.3); }
    
    .pagination-premium .pagination { justify-content: center; gap: 12px; }
    .pagination-premium .page-link { background: var(--white); border: 1px solid rgba(201,168,76,0.2); color: var(--navy); border-radius: 10px; padding: 12px 22px; font-weight: 600; }
    .pagination-premium .page-item.active .page-link { background: var(--gold); border-color: var(--gold); color: var(--navy); box-shadow: 0 5px 15px rgba(201,168,76,0.3); }
    
    .mt-n5 { margin-top: -80px !important; }
</style>
@endpush
