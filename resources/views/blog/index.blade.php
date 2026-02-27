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

    <div class="container py-5 md:-mt-12">
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

        <!-- Newsletter Subscription Section -->
        <div class="newsletter-section mt-5 py-5 bg-white rounded-4 shadow-sm border" data-aos="fade-up">
            <div class="text-center mb-4">
                <h3 class="font-display text-navy mb-3">Newsletter</h3>
                <p class="text-muted">Receive weekly judicial summaries and publication releases.</p>
            </div>
            <form id="newsletter-form" class="row justify-content-center">
                <div class="col-md-6 mb-3">
                    <input type="email" name="email" class="form-control form-control-lg" placeholder="Your Email" required>
                </div>
                <div class="col-md-3 mb-3">
                    <button type="submit" class="btn btn-primary-gold w-100 btn-lg">Subscribe</button>
                </div>
            </form>
            <div id="newsletter-message" class="mt-3"></div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .blog-luxury-page { background: var(--cream); min-height: 80vh; padding-bottom: 120px; position: relative; overflow: visible; }
    
    .blog-hero-header { 
        background: 
            linear-gradient(rgba(12,20,40,0.85) 0%, rgba(12,20,40,0.9) 100%),
            url('/images/publications-banner.jpg') center center / cover no-repeat;
        padding: 160px 0 96px; 
        position:relative; 
        overflow:hidden; 
    }
    @media (min-width: 768px) {
        .blog-hero-header { padding: 160px 0 128px; }
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
    
    .news-luxury-card { display:block; text-decoration:none; background:white; border-radius:24px; overflow:hidden; border: 1px solid rgba(0,0,0,0.05); transition: all 0.4s cubic-bezier(0.2, 0.8, 0.2, 1); border-bottom: 4px solid var(--gold); box-shadow: 0 10px 30px rgba(0,0,0,0.05); position: relative; z-index: 10; }
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
    
    @media (min-width: 768px) {
        .md\:-mt-12 { margin-top: -48px !important; }
    }
    
    /* Newsletter Section */
    .newsletter-section {
        max-width: 800px;
        margin: 40px auto 0;
    }
    
    .newsletter-section .form-control {
        border: 2px solid #e9ecef;
        padding: 15px 20px;
        font-size: 1.1rem;
    }
    
    .newsletter-section .form-control:focus {
        border-color: var(--gold);
        box-shadow: 0 0 0 0.25rem rgba(201, 168, 76, 0.25);
    }
</style>
@endpush

@push('scripts')
<script>
document.getElementById('newsletter-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const email = this.email.value;
    const messageDiv = document.getElementById('newsletter-message');
    
    // Simple client-side validation
    if (!email || !email.includes('@')) {
        messageDiv.innerHTML = '<div class="alert alert-danger">Please enter a valid email address.</div>';
        return;
    }
    
    // Simulate subscription (since we removed the backend functionality)
    messageDiv.innerHTML = '<div class="alert alert-success">Thank you for subscribing to our newsletter!</div>';
    this.reset();
    
    // Hide message after 5 seconds
    setTimeout(() => {
        messageDiv.innerHTML = '';
    }, 5000);
});
</script>
@endpush
