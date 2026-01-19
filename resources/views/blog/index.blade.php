@extends('layouts.lawfirm')

@section('title', 'Blog - Bengaluru Law House')

@section('content')
<section class="blog-page-section">
    <div class="container py-5">
        <!-- Page Header -->
        <div class="page-header text-center mb-5">
            <p class="section-label fade-up">Latest News</p>
            <h1 class="page-title fade-up delay-1">Our Blog</h1>
            <div class="section-divider mx-auto fade-up delay-2"></div>
            <p class="page-description fade-up delay-3">
                Stay updated with the latest news, legal insights, and publications from Bengaluru Law House
            </p>
        </div>

        <!-- Blog Grid -->
        <div class="row">
            @forelse($blogs as $blog)
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
            @empty
            <div class="col-12">
                <div class="empty-state text-center py-5">
                    <i class="fas fa-newspaper fa-4x text-muted mb-4"></i>
                    <h4>No Blog Posts Yet</h4>
                    <p class="text-muted">Check back soon for updates and news from Bengaluru Law House.</p>
                    <a href="{{ route('home') }}" class="btn-primary-law mt-3">
                        <i class="fas fa-home me-2"></i>Back to Home
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($blogs->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $blogs->links() }}
        </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
.blog-page-section {
    margin-top: 80px;
    min-height: calc(100vh - 80px);
    background: var(--cream-primary);
    padding: 60px 0;
}

.page-header {
    margin-bottom: 3rem;
}

.page-title {
    font-family: var(--font-heading);
    font-size: 2.5rem;
    color: var(--navy-deep);
    margin-bottom: 1rem;
}

.page-description {
    max-width: 600px;
    margin: 1rem auto 0;
    color: var(--text-muted);
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
    height: 220px;
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

.empty-state {
    background: var(--white);
    border-radius: 12px;
    padding: 3rem;
}

/* Pagination Styling */
.pagination {
    gap: 0.5rem;
}

.page-link {
    border: none;
    border-radius: 8px;
    padding: 0.5rem 1rem;
    color: var(--navy-deep);
    background: var(--white);
    box-shadow: var(--shadow-soft);
}

.page-link:hover {
    background: var(--gold-primary);
    color: var(--navy-deep);
}

.page-item.active .page-link {
    background: var(--navy-deep);
    color: var(--white);
}

@media (max-width: 767px) {
    .blog-page-section {
        margin-top: 70px;
        padding: 40px 0;
    }

    .page-title {
        font-size: 2rem;
    }
}
</style>
@endpush
