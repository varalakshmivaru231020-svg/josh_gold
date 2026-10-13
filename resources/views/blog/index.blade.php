@extends('layouts.lawfirm')

@section('title', 'News & Updates | Josha Gold and Diamond Jewellery')

@section('content')
@include('layouts.partials.page-head', [
    'eyebrow' => 'Latest news',
    'title'   => 'News &',
    'accent'  => 'Updates',
    'sub'     => 'Stay informed with our latest announcements, market updates and company news',
    'crumbs'  => [['News & Updates']],
])

<section class="blog-luxury-page">
    <div class="jb-wrap blog-list-wrap">
        <div class="row g-4">
            @forelse($blogs as $blog)
            <div class="col-lg-4 col-md-6" data-aos="fade-up">
                <a href="{{ route('blog.show', $blog->slug) }}" class="news-luxury-card h-100">
                    <div class="news-img-wrap">
                        <img src="{{ $blog->image ? asset('storage/' . $blog->image) : asset('blr.jpg') }}" alt="{{ $blog->title }}">
                        <div class="news-metadata">{{ $blog->created_at->format('M d, Y') }}</div>
                    </div>
                    <div class="news-body">
                        <h3>{{ $blog->title }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit($blog->excerpt, 120) }}</p>
                        <span class="read-analysis">Read More <i class="fas fa-arrow-right"></i></span>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12" data-aos="fade-up">
                <div class="empty-intelligence text-center">
                    <i class="fas fa-scroll fa-4x text-gold mb-4"></i>
                    <h3 class="text-navy">No News Yet</h3>
                    <p class="text-muted">New updates are on the way. Please check back shortly.</p>
                    <a href="{{ route('home') }}" class="jb-btn jb-btn--primary mt-4">Return Home</a>
                </div>
            </div>
            @endforelse
        </div>

        @if($blogs->hasPages())
        <div class="pagination-premium mt-5" data-aos="fade-up">
            {{ $blogs->links() }}
        </div>
        @endif

        {{-- Newsletter Subscription Section --}}
        {{-- <div class="newsletter-section mt-5 py-5 bg-white rounded-4 shadow-sm border" data-aos="fade-up">
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
        </div> --}}
    </div>
</section>
@endsection

@push('styles')
<style>
    .blog-luxury-page { padding-bottom: 56px; }
    .blog-list-wrap { padding-top: 32px; padding-bottom: 16px; }
    @media (min-width: 768px) { .blog-list-wrap { padding-top: 48px; } }

    /* News cards (home-page news style) */
    .news-luxury-card {
        display: flex; flex-direction: column; overflow: hidden; text-decoration: none;
        border: 1px solid var(--jb-line); border-radius: var(--jb-r-card); background: var(--jb-panel);
        transition: border-color .2s, transform .2s, box-shadow .2s;
    }
    @media (hover: hover) {
        .news-luxury-card:hover { transform: translateY(-3px); border-color: var(--jb-line-strong); box-shadow: 0 16px 36px -12px rgba(0, 0, 0, .9); }
        .news-luxury-card:hover .news-img-wrap img { transform: scale(1.04); }
    }

    .news-img-wrap { position: relative; aspect-ratio: 16 / 9; overflow: hidden; background: var(--jb-panel-3); }
    .news-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform .45s; }

    .news-metadata {
        position: absolute; right: 12px; bottom: 12px; padding: 5px 12px;
        border: 1px solid rgba(255, 255, 255, .14); border-radius: 999px;
        background: rgba(0, 0, 0, .6); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
        color: var(--jb-gold-light); font-size: 12px; font-weight: 700;
    }

    .news-body { flex: 1; display: flex; flex-direction: column; padding: 20px; }
    .news-body h3 { margin: 0 0 10px; font-family: var(--jb-serif); font-size: 19px; font-weight: 600; line-height: 1.35; color: var(--jb-white); }
    .news-body p {
        margin: 0 0 18px; color: var(--jb-muted); font-size: 14px; line-height: 1.65;
        display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
    }
    .read-analysis { margin-top: auto; display: inline-flex; align-items: center; gap: 8px; color: var(--jb-gold-light); font-size: 13.5px; font-weight: 700; transition: color .15s, gap .15s; }
    .news-luxury-card:hover .read-analysis { color: var(--jb-white); gap: 12px; }

    .empty-intelligence { padding: 56px 24px; border: 1px dashed var(--jb-line); border-radius: var(--jb-r-card); background: var(--jb-panel); }
    @media (min-width: 768px) { .empty-intelligence { padding: 80px 40px; } }

    .pagination-premium .pagination { justify-content: center; flex-wrap: wrap; gap: 8px; }
    .pagination-premium .page-link { border-radius: 10px !important; padding: 10px 16px; font-weight: 600; }

    /* Newsletter Section (markup is commented out for now) */
    .newsletter-section { max-width: 800px; margin: 40px auto 0; }
    .newsletter-section .form-control { padding: 14px 18px; font-size: 1rem; }
</style>
@endpush

@push('scripts')
<script>
// The newsletter form is currently commented out of the page, so only wire it up when it exists
const newsletterForm = document.getElementById('newsletter-form');
if (newsletterForm) newsletterForm.addEventListener('submit', function(e) {
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
