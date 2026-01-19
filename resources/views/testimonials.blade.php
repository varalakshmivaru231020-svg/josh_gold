@extends('layouts.lawfirm')

@section('title', 'Videos - Bengaluru Law House')

@section('content')
    <!-- Page Hero -->
    <section class="page-hero">
        <div class="container">
            <div class="page-hero-content fade-up">
                <p class="section-label">Watch & Learn</p>
                <h1 class="page-hero-title">Our Videos</h1>
                <p class="page-hero-description">
                    Watch our collection of informative videos about legal publications and resources
                </p>
            </div>
        </div>
    </section>

    <!-- Videos Grid -->
    <section class="section section-light">
        <div class="container">
            <!-- Breadcrumb -->
            <div class="breadcrumb-bar fade-up">
                <a href="{{ route('home') }}" class="breadcrumb-link">
                    <i class="fas fa-home me-1"></i> Home
                </a>
                <span class="breadcrumb-divider">/</span>
                <span class="breadcrumb-current">Videos</span>
            </div>

            @if($testimonialVideos && $testimonialVideos->count() > 0)
                <div class="row g-4">
                    @foreach($testimonialVideos as $video)
                        <div class="col-lg-4 col-md-6">
                            <div class="video-card fade-up">
                                <div class="video-thumbnail" data-youtube-id="{{ $video->youtube_id }}">
                                    <img src="https://img.youtube.com/vi/{{ $video->youtube_id }}/maxresdefault.jpg" alt="{{ $video->title }}" onerror="this.src='https://img.youtube.com/vi/{{ $video->youtube_id }}/hqdefault.jpg'">
                                    <div class="play-button">
                                        <i class="fas fa-play"></i>
                                    </div>
                                    <div class="video-badge">
                                        <i class="fab fa-youtube"></i>
                                    </div>
                                </div>
                                <div class="video-info">
                                    <h5 class="video-title">{{ $video->title }}</h5>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($testimonialVideos->hasPages())
                    <div class="pagination-wrapper fade-up">
                        {{ $testimonialVideos->links() }}
                    </div>
                @endif
            @else
                <div class="empty-state fade-up">
                    <div class="empty-icon">
                        <i class="fas fa-video-slash"></i>
                    </div>
                    <h3>No Videos Yet</h3>
                    <p>We're adding new videos regularly. Check back soon!</p>
                    <a href="{{ route('home') }}" class="btn-primary-law">
                        <i class="fas fa-home me-2"></i> Back to Home
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Video Modal -->
    <div class="video-modal" id="videoModal">
        <div class="video-modal-content">
            <button class="video-modal-close" id="modalClose">&times;</button>
            <div class="video-container">
                <iframe id="youtube-iframe" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    /* Page Hero */
    .page-hero {
        background: var(--navy-deep);
        padding: 160px 0 80px;
        text-align: center;
        position: relative;
    }

    .page-hero::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, transparent, var(--gold-primary), transparent);
    }

    .page-hero-content .section-label {
        color: var(--gold-primary);
    }

    .page-hero-title {
        font-size: 3rem;
        color: var(--cream-primary);
        margin-bottom: 1rem;
    }

    .page-hero-description {
        color: rgba(250, 248, 243, 0.7);
        font-size: 1.15rem;
        max-width: 600px;
        margin: 0 auto;
    }

    /* Breadcrumb */
    .breadcrumb-bar {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1.5rem 0;
        margin-bottom: 2rem;
        border-bottom: 1px solid rgba(201, 162, 39, 0.15);
        font-size: 0.9rem;
    }

    .breadcrumb-link {
        color: var(--gold-dark);
        text-decoration: none;
        transition: var(--transition-fast);
    }

    .breadcrumb-link:hover {
        color: var(--gold-primary);
    }

    .breadcrumb-divider {
        color: var(--text-muted);
    }

    .breadcrumb-current {
        color: var(--navy-primary);
        font-weight: 500;
    }

    /* Video Cards */
    .video-card {
        background: var(--white);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-soft);
        transition: var(--transition-medium);
        height: 100%;
        display: flex;
        flex-direction: column;
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

    .video-badge {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: rgba(0,0,0,0.8);
        color: #ff0000;
        padding: 0.3rem 0.6rem;
        border-radius: 4px;
        font-size: 0.9rem;
    }

    .video-info {
        padding: 1.25rem;
    }

    .video-title {
        font-size: 1.05rem;
        color: var(--navy-deep);
        margin: 0;
        font-weight: 600;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 1px solid rgba(201, 162, 39, 0.15);
    }

    .pagination-wrapper .pagination {
        gap: 0.5rem;
    }

    .pagination-wrapper .page-link {
        background: var(--white);
        border: 1px solid rgba(201, 162, 39, 0.2);
        color: var(--navy-primary);
        padding: 0.6rem 1rem;
        border-radius: 4px;
        transition: var(--transition-fast);
    }

    .pagination-wrapper .page-link:hover {
        background: var(--cream-dark);
        border-color: var(--gold-primary);
        color: var(--gold-dark);
    }

    .pagination-wrapper .page-item.active .page-link {
        background: var(--navy-primary);
        border-color: var(--navy-primary);
        color: var(--white);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
    }

    .empty-icon {
        width: 100px;
        height: 100px;
        background: var(--cream-dark);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
    }

    .empty-icon i {
        font-size: 2.5rem;
        color: var(--navy-muted);
    }

    .empty-state h3 {
        color: var(--navy-deep);
        margin-bottom: 1rem;
    }

    .empty-state p {
        color: var(--text-muted);
        margin-bottom: 2rem;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
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
    @media (max-width: 767px) {
        .page-hero {
            padding: 140px 0 60px;
        }

        .page-hero-title {
            font-size: 2rem;
        }

        .video-thumbnail {
            height: 180px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const videoModal = document.getElementById('videoModal');
    const modalClose = document.getElementById('modalClose');
    const youtubeIframe = document.getElementById('youtube-iframe');

    // Video thumbnail click handler
    document.querySelectorAll('.video-thumbnail').forEach(function(thumbnail) {
        thumbnail.addEventListener('click', function() {
            const youtubeId = this.dataset.youtubeId;
            youtubeIframe.src = `https://www.youtube.com/embed/${youtubeId}?autoplay=1`;
            videoModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });

    // Close modal
    modalClose.addEventListener('click', closeModal);

    videoModal.addEventListener('click', function(e) {
        if (e.target === videoModal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && videoModal.classList.contains('active')) {
            closeModal();
        }
    });

    function closeModal() {
        videoModal.classList.remove('active');
        youtubeIframe.src = '';
        document.body.style.overflow = '';
    }
});
</script>
@endpush
