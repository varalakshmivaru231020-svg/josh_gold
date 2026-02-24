@extends('layouts.lawfirm')

@section('title', 'Photo Gallery | Advocate Veerendrababu Nanjegowda')

@section('content')
<section class="gallery-luxury-page">

    {{-- Hero Header --}}
    <div class="gallery-hero-header">
        {{-- decorative background icon --}}
        <div class="gallery-hero-deco" aria-hidden="true"><i class="fas fa-images"></i></div>
        <div class="container text-center" data-aos="fade-up">
            <div class="label-gold">PHOTO GALLERY</div>
            <h1 class="heading-premium text-white">Moments Across Courtrooms, Culture &amp; Community</h1>
            <div class="accent-line-gold mx-auto"></div>
            <p class="gallery-subheader text-white mt-3">A visual chronicle of legal milestones, cultural pursuits, and community engagement by Advocate Veerendrababu Nanjegowda.</p>
        </div>
    </div>

    {{-- Gallery Grid --}}
    <div class="container gallery-content-container">

        {{-- Breadcrumb --}}
        <div class="breadcrumb-luxury mb-5" data-aos="fade-up">
            <a href="{{ route('home') }}">Home</a>
            <span class="sep">/</span>
            <span class="curr">Gallery</span>
        </div>

        @if($galleryPhotos && $galleryPhotos->count() > 0)

        {{-- Category Filter (if categories exist) --}}
        @php
            $categories = $galleryPhotos->pluck('category')->filter()->unique()->values();
        @endphp
        @if($categories->count() > 1)
        <div class="gallery-filter-bar" data-aos="fade-up">
            <button class="gallery-filter-btn active" data-filter="all">All Photos</button>
            @foreach($categories as $cat)
                <button class="gallery-filter-btn" data-filter="{{ Str::slug($cat) }}">{{ $cat }}</button>
            @endforeach
        </div>
        @endif

        {{-- Masonry / Mosaic Grid --}}
        <div class="gallery-mosaic-grid" data-aos="fade-up" data-aos-delay="100">
            @foreach($galleryPhotos as $index => $photo)
            <div class="gallery-mosaic-item {{ $index % 5 === 0 ? 'gallery-tall' : '' }} {{ $photo->category ? 'filter-' . Str::slug($photo->category) : '' }}"
                 data-aos="zoom-in"
                 data-aos-delay="{{ min($index * 50, 500) }}">
                <a href="{{ asset('storage/' . $photo->image) }}"
                   class="gallery-lightbox-link"
                   data-title="{{ $photo->title ?? '' }}"
                   data-caption="{{ $photo->caption ?? '' }}">
                    <img src="{{ asset('storage/' . $photo->image) }}"
                         alt="{{ $photo->title ?? 'Gallery Photo ' . ($index + 1) }}"
                         loading="lazy">
                    <div class="gallery-item-overlay">
                        <div class="gallery-overlay-inner">
                            <i class="fas fa-expand-alt gallery-zoom-icon"></i>
                            @if($photo->title)
                                <p class="gallery-item-title">{{ $photo->title }}</p>
                            @endif
                            @if($photo->caption)
                                <p class="gallery-item-caption">{{ $photo->caption }}</p>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        @else
        {{-- Empty State --}}
        <div class="gallery-empty-state text-center" data-aos="fade-up">
            <i class="fas fa-images fa-4x mb-4" style="color: var(--gold); opacity: 0.4;"></i>
            <h3 style="color: var(--navy);">Gallery Coming Soon</h3>
            <p class="text-muted">We are curating our photo gallery. Please check back soon.</p>
            <a href="{{ route('home') }}" class="btn-outline-navy mt-4">Back to Home</a>
        </div>
        @endif
    </div>
</section>

{{-- Lightbox Modal --}}
<div class="gallery-lightbox-modal" id="galleryModal">
    <div class="glb-overlay" id="glbOverlay"></div>
    <div class="glb-container">
        <button class="glb-close" id="glbClose"><i class="fas fa-times"></i></button>
        <button class="glb-prev" id="glbPrev"><i class="fas fa-chevron-left"></i></button>
        <button class="glb-next" id="glbNext"><i class="fas fa-chevron-right"></i></button>
        <div class="glb-img-wrap">
            <img src="" alt="" id="glbImage">
        </div>
        <div class="glb-caption-bar">
            <p id="glbTitle" class="glb-title"></p>
            <p id="glbCaption" class="glb-caption"></p>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* ── GALLERY PAGE ── */
    .gallery-luxury-page { background: var(--cream, #f8f5ef); min-height: 80vh; padding-bottom: 120px; }

    /* Hero */
    .gallery-hero-header {
        background:
            linear-gradient(rgba(8,14,28,0.78) 0%, rgba(10,18,36,0.82) 100%),
            url('/images/gallery-banner.jpg') center center / cover no-repeat;
        padding: 160px 0 100px;
        position: relative;
        overflow: hidden;
        z-index: 0;
    }
    /* Subtle gold grid overlay on top of image */
    .gallery-hero-header::before {
        content: ''; position: absolute; inset: 0; z-index: 0;
        background-image:
            linear-gradient(rgba(201,168,76,0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(201,168,76,0.05) 1px, transparent 1px);
        background-size: 55px 55px;
    }
    /* Bottom warm glow */
    .gallery-hero-header::after {
        content: ''; position: absolute; inset: 0; z-index: 0;
        background:
            radial-gradient(ellipse at 50% 110%, rgba(201,168,76,0.22) 0%, transparent 50%);
    }
    .gallery-hero-header .container { position: relative; z-index: 1; }
    .gallery-hero-header .label-gold { display: block; margin-bottom: 14px; }
    .gallery-hero-header .heading-premium { font-size: clamp(1.8rem, 4vw, 3rem); font-weight: 700; }
    .gallery-hero-header .accent-line-gold { width: 60px; height: 3px; background: var(--gold, #c9a84c); margin: 18px auto 0; border-radius: 2px; }
    .gallery-subheader { max-width: 650px; margin: 0 auto; font-family: var(--font-display); font-size: 1.1rem; font-style: italic; opacity: 0.85; }

    /* Decorative background icon */
    .gallery-hero-deco {
        position: absolute; right: 7%; top: 50%; transform: translateY(-50%);
        font-size: 14rem; color: rgba(201,168,76,0.08);
        pointer-events: none; z-index: 0; line-height: 1;
    }

    /* Content Container */
    .gallery-content-container { padding-top: 60px; }

    /* Breadcrumb */
    .breadcrumb-luxury { display:flex; align-items:center; gap:12px; font-size:0.85rem; letter-spacing:2px; text-transform:uppercase; font-weight: 700; color: var(--navy); }
    .breadcrumb-luxury a { color: var(--gold-dark, #8b6914); text-decoration:none; transition: 0.3s; }
    .breadcrumb-luxury a:hover { color: var(--navy); }
    .breadcrumb-luxury .sep { opacity: 0.3; }

    /* Filter Bar */
    .gallery-filter-bar {
        display: flex; flex-wrap: wrap; gap: 12px;
        margin-bottom: 40px;
        justify-content: center;
    }
    .gallery-filter-btn {
        padding: 8px 22px; border-radius: 50px;
        border: 2px solid rgba(201,168,76,0.3);
        background: white; color: var(--navy, #0c1428);
        font-size: 0.8rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase;
        cursor: pointer; transition: all 0.3s;
    }
    .gallery-filter-btn:hover,
    .gallery-filter-btn.active {
        background: var(--navy, #0c1428);
        color: var(--gold, #c9a84c);
        border-color: var(--navy, #0c1428);
    }

    /* Mosaic Grid */
    .gallery-mosaic-grid {
        columns: 4;
        column-gap: 16px;
    }
    .gallery-mosaic-item {
        break-inside: avoid;
        margin-bottom: 16px;
        border-radius: 16px;
        overflow: hidden;
        position: relative;
        cursor: pointer;
    }
    .gallery-mosaic-item a {
        display: block;
        position: relative;
        overflow: hidden;
    }
    .gallery-mosaic-item img {
        width: 100%;
        height: auto;
        display: block;
        transition: transform 0.5s cubic-bezier(0.2, 0.8, 0.2, 1);
        border-radius: 16px;
    }
    .gallery-mosaic-item:hover img { transform: scale(1.06); }

    /* Tall items */
    .gallery-tall img { /* let images define their height */ }

    /* Overlay */
    .gallery-item-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(12,20,40,0.85) 0%, transparent 60%);
        opacity: 0;
        transition: opacity 0.4s;
        border-radius: 16px;
        display: flex; align-items: flex-end; justify-content: center;
        padding: 20px;
    }
    .gallery-mosaic-item:hover .gallery-item-overlay { opacity: 1; }
    .gallery-overlay-inner { text-align: center; color: white; }
    .gallery-zoom-icon { font-size: 1.6rem; color: var(--gold, #c9a84c); margin-bottom: 8px; display: block; }
    .gallery-item-title { font-family: var(--font-display); font-size: 1rem; font-weight: 700; margin: 0 0 4px; }
    .gallery-item-caption { font-size: 0.85rem; opacity: 0.8; margin: 0; }

    /* Empty */
    .gallery-empty-state { background: white; padding: 80px 40px; border-radius: 30px; border: 2px dashed rgba(201,168,76,0.3); }

    /* CTA btn */
    .btn-outline-navy {
        display: inline-flex; align-items: center;
        padding: 14px 36px; border-radius: 50px;
        border: 2px solid var(--navy, #0c1428);
        color: var(--navy, #0c1428); font-weight: 700;
        text-decoration: none; font-size: 0.9rem; letter-spacing: 1px;
        transition: all 0.3s;
    }
    .btn-outline-navy:hover { background: var(--navy, #0c1428); color: var(--gold, #c9a84c); }

    /* ── LIGHTBOX ── */
    .gallery-lightbox-modal {
        display: none; position: fixed; inset: 0; z-index: 10000;
        align-items: center; justify-content: center;
    }
    .gallery-lightbox-modal.open { display: flex; }
    .glb-overlay { position: absolute; inset: 0; background: rgba(6,12,24,0.97); backdrop-filter: blur(8px); }
    .glb-container {
        position: relative; z-index: 2;
        max-width: 90vw; max-height: 90vh;
        display: flex; flex-direction: column; align-items: center;
    }
    .glb-img-wrap { max-height: 80vh; display: flex; align-items: center; justify-content: center; }
    .glb-img-wrap img {
        max-width: 90vw; max-height: 80vh;
        object-fit: contain; border-radius: 12px;
        box-shadow: 0 0 80px rgba(0,0,0,0.8);
        border: 1px solid rgba(201,168,76,0.2);
    }
    .glb-close {
        position: fixed; top: 24px; right: 32px;
        background: none; border: none; color: white;
        font-size: 1.8rem; cursor: pointer; z-index: 3;
        transition: color 0.3s;
    }
    .glb-close:hover { color: var(--gold, #c9a84c); }
    .glb-prev, .glb-next {
        position: fixed; top: 50%; transform: translateY(-50%);
        background: rgba(201,168,76,0.15); border: 1px solid rgba(201,168,76,0.3);
        color: white; width: 50px; height: 50px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; z-index: 3; font-size: 1.1rem; transition: all 0.3s;
    }
    .glb-prev { left: 24px; }
    .glb-next { right: 24px; }
    .glb-prev:hover, .glb-next:hover { background: var(--gold, #c9a84c); color: var(--navy, #0c1428); }
    .glb-caption-bar { text-align: center; margin-top: 16px; color: white; }
    .glb-title { font-family: var(--font-display); font-size: 1.1rem; font-weight: 700; color: var(--gold, #c9a84c); margin: 0 0 4px; }
    .glb-caption { font-size: 0.9rem; opacity: 0.7; margin: 0; }

    /* Responsive */
    @media (max-width: 1200px) { .gallery-mosaic-grid { columns: 3; } }
    @media (max-width: 768px)  { .gallery-mosaic-grid { columns: 2; } }
    @media (max-width: 480px)  { .gallery-mosaic-grid { columns: 1; } }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const links = Array.from(document.querySelectorAll('.gallery-lightbox-link'));
    const modal = document.getElementById('galleryModal');
    const glbImage = document.getElementById('glbImage');
    const glbTitle = document.getElementById('glbTitle');
    const glbCaption = document.getElementById('glbCaption');
    let current = 0;

    function openModal(index) {
        current = index;
        const link = links[index];
        glbImage.src = link.href;
        glbImage.alt = link.dataset.title || '';
        glbTitle.textContent = link.dataset.title || '';
        glbCaption.textContent = link.dataset.caption || '';
        modal.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.remove('open');
        document.body.style.overflow = '';
        glbImage.src = '';
    }

    links.forEach(function(link, i) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            openModal(i);
        });
    });

    document.getElementById('glbClose').addEventListener('click', closeModal);
    document.getElementById('glbOverlay').addEventListener('click', closeModal);

    document.getElementById('glbPrev').addEventListener('click', function() {
        current = (current - 1 + links.length) % links.length;
        openModal(current);
    });

    document.getElementById('glbNext').addEventListener('click', function() {
        current = (current + 1) % links.length;
        openModal(current);
    });

    document.addEventListener('keydown', function(e) {
        if (!modal.classList.contains('open')) return;
        if (e.key === 'Escape') closeModal();
        if (e.key === 'ArrowLeft') { current = (current - 1 + links.length) % links.length; openModal(current); }
        if (e.key === 'ArrowRight') { current = (current + 1) % links.length; openModal(current); }
    });

    // Category filter
    document.querySelectorAll('.gallery-filter-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.gallery-filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const filter = this.dataset.filter;
            document.querySelectorAll('.gallery-mosaic-item').forEach(function(item) {
                if (filter === 'all' || item.classList.contains('filter-' + filter)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});
</script>
@endpush
