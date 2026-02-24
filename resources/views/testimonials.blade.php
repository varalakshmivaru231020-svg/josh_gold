@extends('layouts.lawfirm')

@section('title', 'Videos & Media | Bengaluru Law House')

@section('content')
<section class="videos-luxury-page">
    {{-- Hero Banner --}}
    <div class="video-hero-header">
        <div class="video-hero-deco" aria-hidden="true"><i class="fas fa-video"></i></div>
        <div class="container text-center" data-aos="fade-up">
            <div class="label-gold">VISUAL ARCHIVES</div>
            <h1 class="heading-premium text-white">Media & Testimonials</h1>
            <div class="accent-line-gold mx-auto"></div>
            <p class="hero-subheader text-white opacity-90 italic mt-3">"A visual journey of legal impact and cultural storytelling."</p>
        </div>
    </div>

    <div class="container video-content-container">
        <!-- Breadcrumb -->
        <div class="breadcrumb-luxury mb-5" data-aos="fade-up">
            <a href="{{ route('home') }}">Home</a>
            <span class="sep">/</span>
            <span class="curr">Videos</span>
        </div>

        @if($testimonialVideos && $testimonialVideos->count() > 0)
            <div class="row g-4">
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
                <p class="text-muted">We're adding new videos regularly. Check back soon!</p>
                <a href="{{ route('home') }}" class="btn-primary-gold mt-4">
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
    .videos-luxury-page { background: var(--cream); min-height: 80vh; padding-bottom: 120px; }
    
    /* Hero Banner */
    .video-hero-header { 
        background: 
            linear-gradient(rgba(8,14,28,0.82) 0%, rgba(10,18,36,0.85) 100%),
            url('/images/videos-banner.jpg') center center / cover no-repeat;
        padding: 160px 0 100px; 
        position:relative; 
        overflow:hidden; 
        z-index: 0;
    }
    .video-hero-header::before {
        content: ''; position: absolute; inset: 0; z-index: 0;
        background-image: 
            linear-gradient(rgba(201, 168, 76, 0.055) 1px, transparent 1px),
            linear-gradient(90deg, rgba(201, 168, 76, 0.055) 1px, transparent 1px);
        background-size: 55px 55px;
    }
    .video-hero-header::after { 
        content:''; position:absolute; inset:0; z-index: 0;
        background: radial-gradient(ellipse at 50% 110%, rgba(201,168,76,0.22) 0%, transparent 50%); 
    }
    
    /* Decorative background icon */
    .video-hero-deco {
        position: absolute; right: 8%; top: 50%; transform: translateY(-50%);
        font-size: 14rem; color: rgba(201,168,76,0.065);
        pointer-events: none; z-index: 0; line-height: 1;
    }

    .video-hero-header .container { position: relative; z-index: 1; }
    .hero-subheader { max-width: 700px; margin: 0 auto; font-family: var(--font-display); font-size: 1.4rem; font-style: italic; position: relative; z-index: 2; }
    
    /* Content sits below banner — no overlap */
    .video-content-container { padding-top: 60px; padding-bottom: 60px; position: relative; z-index: 5; }

    .breadcrumb-luxury { display:flex; align-items:center; gap:12px; font-size:0.85rem; letter-spacing:2px; text-transform:uppercase; font-weight: 700; color: var(--navy); }
    .breadcrumb-luxury a { color: var(--gold-dark); text-decoration:none; transition: 0.3s; }
    .breadcrumb-luxury a:hover { color: var(--navy); }
    .breadcrumb-luxury .sep { opacity: 0.3; }
    .breadcrumb-luxury .curr { opacity: 0.6; }

    .video-card-premium { background: white; border-radius: 24px; overflow:hidden; border: 1px solid rgba(201,168,76,0.1); transition: all 0.4s cubic-bezier(0.2, 0.8, 0.2, 1); height: 100%; display: flex; flex-direction: column; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
    .video-card-premium:hover { transform: translateY(-12px); box-shadow: 0 30px 60px rgba(0,0,0,0.12); border-color: var(--gold); }
    
    .video-thumb-wrap { position:relative; height:240px; cursor:pointer; overflow:hidden; }
    .video-thumb-wrap img { width:100%; height:100%; object-fit:cover; transition: 0.6s; }
    .video-card-premium:hover .video-thumb-wrap img { transform: scale(1.1); }
    
    .play-btn-premium { position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); width:64px; height:64px; background:var(--gold); border-radius:50%; display:flex; align-items:center; justify-content:center; color:var(--navy); font-size:1.2rem; transition: 0.3s; box-shadow: 0 10px 25px rgba(201,168,76,0.4); }
    .video-card-premium:hover .play-btn-premium { transform:translate(-50%, -50%) scale(1.15); background: white; }
    .play-btn-premium i { margin-left: 4px; }
    
    .video-platform-badge { position:absolute; bottom:15px; right:15px; background:rgba(0,0,0,0.8); color:#ff0000; width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1rem; }
    
    .video-details-premium { padding: 25px; }
    .video-title-premium { font-family: var(--font-display); font-size: 1.25rem; color: var(--navy); font-weight: 700; margin: 0; line-height: 1.4; }

    .empty-state-luxury { background: white; padding: 100px; border-radius: 40px; border: 2px dashed rgba(201,168,76,0.3); }

    /* Modal Styling */
    .video-modal-premium { position:fixed; inset:0; z-index:10000; display:none; align-items:center; justify-content:center; padding: 40px; }
    .video-modal-premium.active { display:flex; }
    .video-modal-overlay { position:absolute; inset:0; background:rgba(6,12,24,0.98); backdrop-filter: blur(10px); }
    .video-modal-container { position:relative; width:100%; max-width:1100px; z-index:2; }
    .modal-close-lux { position:absolute; top:-60px; right:0; background:none; border:none; color:white; font-size:3rem; cursor:pointer; transition: 0.3s; }
    .modal-close-lux:hover { color: var(--gold); transform: rotate(90deg); }
    .video-frame-lux { position:relative; padding-bottom:56.25%; height:0; border-radius:24px; overflow:hidden; box-shadow: 0 0 100px rgba(0,0,0,0.5); border: 1px solid rgba(201,168,76,0.2); }
    .video-frame-lux iframe { position:absolute; top:0; left:0; width:100%; height:100%; }

    .pagination-premium .pagination { justify-content: center; gap: 12px; }
    .pagination-premium .page-link { background: var(--white); border: 1px solid rgba(201,168,76,0.2); color: var(--navy); border-radius: 10px; padding: 12px 22px; font-weight: 600; }
    .pagination-premium .page-item.active .page-link { background: var(--gold); border-color: var(--gold); color: var(--navy); box-shadow: 0 5px 15px rgba(201,168,76,0.3); }

    .mt-n5 { margin-top: -100px !important; }
    .accent-line-gold { width: 80px; height: 3px; background: var(--gold); margin-top: 25px; position: relative; z-index: 2; }
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
