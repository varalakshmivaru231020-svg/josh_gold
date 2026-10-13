@extends('layouts.lawfirm')

@section('title', $blog->title . ' | JOSHA GOLD AND DIAMOND JEWELLERY')

@section('content')
@php
    $readTime = max(1, ceil(str_word_count(strip_tags($blog->description)) / 200));
@endphp
@include('layouts.partials.page-head', [
    'eyebrow' => 'News & Intelligence · ' . strtoupper($blog->created_at->format('M d, Y')),
    'title'   => $blog->title,
    'sub'     => 'By Josha Gold and Diamond Jewellery · ' . $readTime . ' min read',
    'crumbs'  => [['News', route('news')], [\Illuminate\Support\Str::limit($blog->title, 60)]],
])

<section class="blog-detail-page">
    <div class="jb-wrap blog-detail-wrap">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-8" data-aos="fade-up">
                        <!-- Article Body -->
                        <div class="blog-content-premium">
                            @if($blog->image)
                                <div class="blog-featured-inside mb-4" data-aos="fade-up">
                                    <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="img-fluid w-100 blog-featured-image">
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

                    <div class="col-lg-4 mt-5 mt-lg-0" data-aos="fade-up" data-aos-delay="80">
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
    .blog-detail-page { padding-bottom: 56px; }
    .blog-detail-wrap { padding-top: 32px; padding-bottom: 16px; }
    @media (min-width: 768px) { .blog-detail-wrap { padding-top: 48px; } }

    /* Article */
    .blog-content-premium {
        position: relative; padding: 40px;
        border: 1px solid var(--jb-line); border-radius: var(--jb-r-card); background: var(--jb-panel);
    }
    .blog-featured-image { max-height: 450px; width: 100%; object-fit: cover; border-radius: 14px; border: 1px solid var(--jb-line); }

    .rich-text-legal { font-size: 16.5px; line-height: 1.8; color: var(--jb-text); overflow-wrap: break-word; }
    .rich-text-legal p { margin-bottom: 24px; }
    .rich-text-legal a { color: var(--jb-gold-light); }
    .rich-text-legal a:hover { color: var(--jb-white); }
    .rich-text-legal strong, .rich-text-legal b { color: var(--jb-white); }
    .rich-text-legal img { max-width: 100%; height: auto; border-radius: 12px; }
    .rich-text-legal h2, .rich-text-legal h3 { font-family: var(--jb-serif); color: var(--jb-white); font-weight: 600; margin: 36px 0 16px; }
    .rich-text-legal blockquote {
        margin: 32px 0; padding: 22px 28px;
        border-left: 3px solid var(--jb-gold); border-radius: 0 14px 14px 0;
        background: rgba(201, 163, 92, .07);
        font-family: var(--jb-serif); font-style: italic; font-size: 1.2rem; color: var(--jb-white);
    }

    .blog-end-meta { margin-top: 40px; }
    .blog-tags .tag-pill {
        display: inline-block; margin: 0 8px 8px 0; padding: 5px 12px; border-radius: 999px;
        border: 1px solid rgba(201, 163, 92, .4); background: rgba(201, 163, 92, .12);
        color: var(--jb-gold-light); font-size: 11.5px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
    }

    .blog-prev-next { margin-top: 10px; }
    .blog-nav-card {
        display: block; padding: 16px 20px; text-decoration: none;
        border: 1px solid var(--jb-line); border-radius: 14px; background: var(--jb-panel-2); color: var(--jb-white);
        transition: border-color .2s, transform .2s;
    }
    .blog-nav-card:hover { border-color: var(--jb-line-strong); color: var(--jb-white); transform: translateY(-2px); }
    .blog-nav-card .nav-label { text-transform: uppercase; letter-spacing: .14em; font-size: 11px; font-weight: 700; color: var(--jb-gold); }
    .blog-nav-card h5 { font-family: var(--jb-serif); font-size: 16px; font-weight: 600; margin: 6px 0 0; color: var(--jb-white); }

    .related-articles-section h3 { font-size: 22px; font-weight: 600; }
    .related-article-card {
        overflow: hidden; text-decoration: none; color: inherit;
        border: 1px solid var(--jb-line); border-radius: 14px; background: var(--jb-panel-2);
        transition: border-color .2s, transform .2s;
    }
    .related-article-card:hover { border-color: var(--jb-line-strong); transform: translateY(-3px); }
    .related-thumb { aspect-ratio: 16 / 10; overflow: hidden; background: var(--jb-panel-3); }
    .related-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .related-body { padding: 14px 16px 16px; }
    .related-body h5 { font-family: var(--jb-serif); font-size: 16px; font-weight: 600; margin-bottom: 6px; color: var(--jb-white); }
    .related-body p { font-size: 13.5px; color: var(--jb-muted); margin-bottom: 10px; }
    .related-read-more { color: var(--jb-gold-light); font-size: 13px; font-weight: 700; }

    .share-flex { display: flex; align-items: center; gap: 15px; flex-wrap: wrap; }
    .share-btn { width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; color: white; transition: 0.3s; text-decoration: none; }
    .share-btn:hover { transform: translateY(-5px); opacity: 0.9; }
    .share-btn.fb { background: #1877f2; }
    .share-btn.tw { background: #000000; border: 1px solid var(--jb-line); }
    .share-btn.wa { background: #25d366; }

    /* Sidebar */
    .blog-sidebar { padding: 24px; border: 1px solid var(--jb-line); border-radius: var(--jb-r-card); background: var(--jb-panel); }
    .blog-sidebar > h4 { font-size: 20px; font-weight: 600; border-color: var(--jb-line-soft) !important; }
    .related-intel-card { display: flex; gap: 14px; align-items: center; text-decoration: none; transition: transform .2s; }
    .related-intel-card:last-child { margin-bottom: 0 !important; }
    .related-intel-card:hover { transform: translateX(4px); }
    .intel-thumb { width: 72px; height: 72px; flex-shrink: 0; overflow: hidden; border-radius: 12px; border: 1px solid var(--jb-line); background: var(--jb-panel-3); }
    .intel-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .intel-info h5 { margin: 4px 0 0; font-family: var(--jb-serif); font-size: 15px; font-weight: 600; line-height: 1.35; color: var(--jb-white); }
    .related-intel-card:hover .intel-info h5 { color: var(--jb-gold-light); }

    @media (max-width: 767px) {
        .blog-content-premium { padding: 22px; }
        .rich-text-legal { font-size: 15.5px; }
    }
</style>
@endpush
