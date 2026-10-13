@extends('layouts.lawfirm')

@section('title', 'Videos & Media | JOSHA GOLD AND DIAMOND JEWELLERY')

@section('content')
<section class="videos-luxury-page">
    @include('layouts.partials.page-head', [
        'eyebrow' => 'Visual archives',
        'title'   => 'Media &',
        'accent'  => 'Testimonials',
        'sub'     => '"A visual journey of legal impact and cultural storytelling."',
        'crumbs'  => [['Videos']],
    ])

    <div class="jb-wrap video-content-container">
        @if($testimonialVideos && $testimonialVideos->count() > 0)
            <div class="row g-3 g-md-4">
                @foreach($testimonialVideos as $video)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up">
                        <div class="video-card-premium">
                            <div class="video-thumb-wrap" data-youtube-id="{{ $video->youtube_id }}">
                                <img src="https://img.youtube.com/vi/{{ $video->youtube_id }}/maxresdefault.jpg" alt="{{ $video->title }}" onerror="this.src='https://img.youtube.com/vi/{{ $video->youtube_id }}/hqdefault.jpg'">
                                <div class="play-btn-premium">
                                    <i class="fas fa-play"></i>
                                </div>
                                <div class="video-platform-badge">
                                    <i class="fab fa-youtube"></i>
                                </div>
                            </div>
                            <div class="video-details-premium">
                                <h5 class="video-title-premium">{{ $video->title }}</h5>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($testimonialVideos->hasPages())
                <div class="pagination-premium mt-5" data-aos="fade-up">
                    {{ $testimonialVideos->links() }}
                </div>
            @endif
        @else
            <div class="empty-state-luxury text-center" data-aos="fade-up">
                <div class="empty-icon-wrap mb-4">
                    <i class="fas fa-video-slash fa-3x text-gold"></i>
                </div>
                <h3 class="text-navy">No Videos Yet</h3>
                <p class="text-muted mb-0">We're adding new videos regularly. Check back soon!</p>
                <a href="{{ route('home') }}" class="jb-btn jb-btn--primary mt-4">
                    <span class="btn-text">Back to Home</span>
                </a>
            </div>
        @endif
    </div>
</section>

<!-- Video Modal -->
<div class="video-modal-premium" id="videoModal">
    <div class="video-modal-overlay"></div>
    <div class="video-modal-container">
        <button class="modal-close-lux" id="modalClose">&times;</button>
        <div class="video-frame-lux">
            <iframe id="youtube-iframe" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .videos-luxury-page { background: var(--jb-ground); }

    /* Content below the heading */
    .video-content-container { padding-top: 40px; padding-bottom: 64px; position: relative; z-index: 5; }
    @media (max-width: 767px) { .video-content-container { padding-top: 28px; padding-bottom: 44px; } }

    .video-card-premium {
        position: relative; height: 100%; display: flex; flex-direction: column; overflow: hidden;
        background: var(--jb-panel); border: 1px solid var(--jb-line); border-radius: var(--jb-r-card);
        transition: transform .2s, border-color .2s, box-shadow .2s;
    }
    .video-card-premium::after {
        content: ''; position: absolute; left: 18%; right: 18%; bottom: 0; height: 1px;
        background: linear-gradient(90deg, transparent, var(--jb-gold-deep), transparent); opacity: .8;
    }
    @media (hover: hover) {
        .video-card-premium:hover { transform: translateY(-3px); border-color: var(--jb-line-strong); box-shadow: 0 16px 36px -14px rgba(0, 0, 0, .9); }
        .video-card-premium:hover .video-thumb-wrap img { transform: scale(1.04); }
        .video-card-premium:hover .play-btn-premium { transform: translate(-50%, -50%) scale(1.08); background: #fff8ea; }
    }

    .video-thumb-wrap {
        position: relative; aspect-ratio: 16 / 9; cursor: pointer; overflow: hidden;
        border-bottom: 1px solid var(--jb-line);
        background: radial-gradient(circle at 50% 42%, #15151a 0%, #08080a 95%);
    }
    .video-thumb-wrap::after { content: ''; position: absolute; inset: 0; background: linear-gradient(transparent 55%, rgba(0, 0, 0, .6)); pointer-events: none; }
    .video-thumb-wrap img { display: block; width: 100%; height: 100%; object-fit: cover; transition: transform .45s; }

    .play-btn-premium {
        position: absolute; z-index: 1; top: 50%; left: 50%; transform: translate(-50%, -50%);
        width: 58px; height: 58px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background: var(--jb-champagne); color: #111; font-size: 1.05rem;
        box-shadow: 0 10px 28px -8px rgba(0, 0, 0, .8);
        transition: transform .2s, background-color .2s;
    }
    .play-btn-premium i { margin-left: 3px; }

    .video-platform-badge {
        position: absolute; z-index: 1; bottom: 12px; right: 12px;
        width: 34px; height: 34px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        background: rgba(0, 0, 0, .7); border: 1px solid var(--jb-line); color: #ff3b3b; font-size: 1rem;
        backdrop-filter: blur(6px);
    }

    .video-details-premium { flex: 1; padding: 16px 18px 20px; }
    .video-title-premium { margin: 0; font-family: var(--jb-serif); font-size: 16.5px; font-weight: 600; color: var(--jb-white); line-height: 1.4; }

    .empty-state-luxury { background: var(--jb-panel); padding: 64px 24px; border-radius: var(--jb-r-card); border: 1px dashed var(--jb-line-strong); }

    /* Modal Styling */
    .video-modal-premium { position: fixed; inset: 0; z-index: 10000; display: none; align-items: center; justify-content: center; padding: 40px; }
    .video-modal-premium.active { display: flex; }
    .video-modal-overlay { position: absolute; inset: 0; background: rgba(0, 0, 0, .94); backdrop-filter: blur(10px); }
    .video-modal-container { position: relative; width: 100%; max-width: 1100px; z-index: 2; }
    .modal-close-lux {
        position: absolute; top: -56px; right: 0;
        width: 44px; height: 44px; display: grid; place-items: center;
        background: var(--jb-panel); border: 1px solid var(--jb-line); border-radius: 12px;
        color: var(--jb-white); font-size: 1.8rem; line-height: 1; cursor: pointer; transition: color .15s, border-color .15s;
    }
    .modal-close-lux:hover { color: var(--jb-gold-light); border-color: rgba(255, 255, 255, .5); }
    .video-frame-lux { position: relative; padding-bottom: 56.25%; height: 0; border-radius: var(--jb-r-card); overflow: hidden; background: #000; box-shadow: 0 30px 70px -20px rgba(0, 0, 0, .9); border: 1px solid var(--jb-line); }
    .video-frame-lux iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }
    @media (max-width: 575px) { .video-modal-premium { padding: 16px; } }

    /* Pagination (Bootstrap or Laravel's default markup) */
    .pagination-premium { display: flex; justify-content: center; }
    .pagination-premium .pagination { justify-content: center; flex-wrap: wrap; gap: 8px; }
    .pagination-premium .page-link {
        background: var(--jb-panel); border: 1px solid var(--jb-line); color: var(--jb-text);
        border-radius: var(--jb-r-ctl) !important; padding: 10px 16px; font-weight: 600;
    }
    .pagination-premium .page-link:hover { background: var(--jb-panel-2); border-color: var(--jb-line-strong); color: var(--jb-white); }
    .pagination-premium .page-item.active .page-link { background: var(--jb-champagne); border-color: var(--jb-champagne); color: #111; }
    .pagination-premium .page-item.disabled .page-link { background: var(--jb-panel); color: #5b5b5b; }
    /* Laravel's default (Tailwind) pagination markup, styled here because Tailwind is not loaded */
    .pagination-premium nav[role="navigation"] { width: 100%; }
    .pagination-premium nav[role="navigation"] > div:first-child { display: flex; justify-content: space-between; gap: 8px; }
    .pagination-premium nav[role="navigation"] > div:last-child { display: none; }
    @media (min-width: 640px) {
        .pagination-premium nav[role="navigation"] > div:first-child { display: none; }
        .pagination-premium nav[role="navigation"] > div:last-child { display: flex; flex-direction: column-reverse; align-items: center; gap: 12px; }
    }
    .pagination-premium nav[role="navigation"] p { margin: 0; color: var(--jb-muted); font-size: 13px; }
    .pagination-premium nav[role="navigation"] p span { color: var(--jb-white); font-weight: 600; }
    .pagination-premium nav[role="navigation"] > div:last-child > div:last-child > span { display: inline-flex; flex-wrap: wrap; justify-content: center; gap: 6px; }
    .pagination-premium nav[role="navigation"] a,
    .pagination-premium nav[role="navigation"] > div:first-child > span,
    .pagination-premium nav[role="navigation"] span[aria-current] > span,
    .pagination-premium nav[role="navigation"] span[aria-disabled] > span {
        display: inline-flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0 12px;
        border: 1px solid var(--jb-line); border-radius: var(--jb-r-ctl); background: var(--jb-panel);
        color: var(--jb-text); font-size: 13.5px; font-weight: 600; text-decoration: none;
        transition: border-color .15s, color .15s, background-color .15s;
    }
    .pagination-premium nav[role="navigation"] a:hover { border-color: var(--jb-line-strong); color: var(--jb-white); background: var(--jb-panel-2); }
    .pagination-premium nav[role="navigation"] span[aria-current] > span { background: var(--jb-champagne); border-color: var(--jb-champagne); color: #111; }
    .pagination-premium nav[role="navigation"] > div:first-child > span,
    .pagination-premium nav[role="navigation"] span[aria-disabled] > span { color: #5b5b5b; }
    .pagination-premium svg { width: 16px; height: 16px; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const videoModal = document.getElementById('videoModal');
    const modalClose = document.getElementById('modalClose');
    const youtubeIframe = document.getElementById('youtube-iframe');

    document.querySelectorAll('.video-thumb-wrap').forEach(function(thumb) {
        thumb.addEventListener('click', function() {
            const youtubeId = this.dataset.youtubeId;
            youtubeIframe.src = `https://www.youtube.com/embed/${youtubeId}?autoplay=1`;
            videoModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });

    const closeModal = () => {
        videoModal.classList.remove('active');
        youtubeIframe.src = '';
        document.body.style.overflow = '';
    };

    modalClose.addEventListener('click', closeModal);
    videoModal.querySelector('.video-modal-overlay').addEventListener('click', closeModal);
    
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && videoModal.classList.contains('active')) closeModal();
    });
});
</script>
@endpush
