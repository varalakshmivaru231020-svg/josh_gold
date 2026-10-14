@extends('layouts.lawfirm')

@section('title', 'Photo Gallery | ' . config('company.name'))

@section('content')
<section class="gallery-luxury-page">

    @include('layouts.partials.page-head', [
        'eyebrow' => 'Photo gallery',
        'title'   => 'Our store, our pieces,',
        'accent'  => 'our people',
        'sub'     => 'Photos from Josha Gold & Diamond Jewellery: our showroom, jewellery, bullion and events.',
        'crumbs'  => [['Gallery']],
    ])

    {{-- Gallery Grid --}}
    <div class="jb-wrap gallery-content-container">

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
                <a href="{{ versioned_asset('storage/' . $photo->image) }}"
                   class="gallery-lightbox-link"
                   data-title="{{ $photo->title ?? '' }}"
                   data-caption="{{ $photo->caption ?? '' }}">
                    <img src="{{ versioned_asset('storage/' . $photo->image) }}"
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
            <i class="fas fa-images fa-3x mb-4 gallery-empty-icon"></i>
            <h3>Gallery Coming Soon</h3>
            <p class="text-muted">We are curating our photo gallery. Please check back soon.</p>
            <a href="{{ route('home') }}" class="jb-btn jb-btn--ghost mt-4">Back to Home</a>
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
    .gallery-luxury-page { background: var(--jb-ground); }

    /* Content Container */
    .gallery-content-container { padding-top: 40px; padding-bottom: 64px; }
    @media (max-width: 767px) { .gallery-content-container { padding-top: 28px; padding-bottom: 44px; } }

    /* Filter Bar (same look as the home page pills) */
    .gallery-filter-bar {
        display: flex; flex-wrap: wrap; gap: 8px;
        margin-bottom: 24px;
    }
    .gallery-filter-btn {
        display: inline-flex; align-items: center; min-height: 36px; padding: 0 14px;
        border: 1px solid var(--jb-line); border-radius: var(--jb-r-ctl);
        background: #fff; color: var(--jb-text);
        font: 600 13px/1 var(--jb-sans); white-space: nowrap;
        cursor: pointer; transition: border-color .15s, background-color .15s, color .15s;
    }
    .gallery-filter-btn:hover { color: var(--jb-ink); border-color: var(--jb-line-strong); }
    .gallery-filter-btn.active { color: var(--jb-gold-deep); border-color: var(--jb-gold); background: #fbf1da; }

    /* Mosaic Grid */
    .gallery-mosaic-grid {
        columns: 4;
        column-gap: 16px;
    }
    .gallery-mosaic-item {
        break-inside: avoid;
        margin-bottom: 16px;
        border: 1px solid var(--jb-line);
        border-radius: var(--jb-r-card);
        overflow: hidden;
        position: relative;
        cursor: pointer;
        background: var(--jb-panel-3);
        box-shadow: var(--jb-shadow);
        transition: border-color .2s, box-shadow .2s;
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
    }
    @media (hover: hover) {
        .gallery-mosaic-item:hover { border-color: var(--jb-line-strong); box-shadow: var(--jb-shadow-lg); }
        .gallery-mosaic-item:hover img { transform: scale(1.04); }
    }

    /* Overlay */
    .gallery-item-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(20, 14, 4, .85) 0%, transparent 62%);
        opacity: 0;
        transition: opacity 0.4s;
        display: flex; align-items: flex-end; justify-content: center;
        padding: 20px;
    }
    .gallery-mosaic-item:hover .gallery-item-overlay,
    .gallery-lightbox-link:focus-visible .gallery-item-overlay { opacity: 1; }
    /* Text sits on the photo, so it stays white over the dark gradient */
    .gallery-overlay-inner { text-align: center; color: #fff; }
    .gallery-zoom-icon { font-size: 1.4rem; color: #f0d79b; margin-bottom: 8px; display: block; }
    .gallery-item-title { font-family: var(--jb-serif); font-size: 1rem; font-weight: 600; color: #fff; margin: 0 0 4px; }
    .gallery-item-caption { font-size: 0.85rem; color: rgba(255, 255, 255, .85); margin: 0; }

    /* Empty */
    .gallery-empty-state { background: #fff; box-shadow: var(--jb-shadow); padding: 64px 24px; border-radius: var(--jb-r-card); border: 1px dashed var(--jb-line-strong); color: var(--jb-muted); }
    .gallery-empty-icon { color: var(--jb-gold); opacity: .7; }

    /* ── LIGHTBOX ── */
    .gallery-lightbox-modal {
        display: none; position: fixed; inset: 0; z-index: 10000;
        align-items: center; justify-content: center;
    }
    .gallery-lightbox-modal.open { display: flex; }
    .glb-overlay { position: absolute; inset: 0; background: rgba(251, 248, 242, .96); backdrop-filter: blur(8px); }
    .glb-container {
        position: relative; z-index: 2;
        max-width: 90vw; max-height: 90vh;
        display: flex; flex-direction: column; align-items: center;
    }
    .glb-img-wrap { max-height: 80vh; display: flex; align-items: center; justify-content: center; }
    .glb-img-wrap img {
        max-width: 90vw; max-height: 80vh;
        object-fit: contain; border-radius: 14px;
        box-shadow: var(--jb-shadow-lg);
        border: 4px solid #fff; outline: 1px solid var(--jb-line);
    }
    .glb-close {
        position: fixed; top: 20px; right: 20px;
        width: 44px; height: 44px; display: grid; place-items: center;
        background: #fff; border: 1px solid var(--jb-line-strong); border-radius: 12px; color: var(--jb-ink); box-shadow: var(--jb-shadow);
        font-size: 1.1rem; cursor: pointer; z-index: 3;
        transition: border-color .15s, color .15s;
    }
    .glb-close:hover { color: var(--jb-gold-strong); border-color: var(--jb-gold); }
    .glb-prev, .glb-next {
        position: fixed; top: 50%; transform: translateY(-50%);
        background: #fff; border: 1px solid var(--jb-line-strong); box-shadow: var(--jb-shadow);
        color: var(--jb-ink); width: 48px; height: 48px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; z-index: 3; font-size: 1rem; transition: background-color .15s, color .15s, border-color .15s;
    }
    .glb-prev { left: 20px; }
    .glb-next { right: 20px; }
    .glb-prev:hover, .glb-next:hover { background: var(--jb-gold-band); border-color: #b48b3c; color: #2a1d06; }
    .glb-caption-bar { text-align: center; margin-top: 16px; color: var(--jb-text); }
    .glb-title { font-family: var(--jb-serif); font-size: 1.1rem; font-weight: 600; color: var(--jb-ink); margin: 0 0 4px; }
    .glb-caption { font-size: 0.9rem; color: var(--jb-muted); margin: 0; }
    @media (max-width: 575px) {
        .glb-prev, .glb-next { width: 40px; height: 40px; }
        .glb-prev { left: 8px; }
        .glb-next { right: 8px; }
    }

    /* Responsive */
    @media (max-width: 1200px) { .gallery-mosaic-grid { columns: 3; } }
    @media (max-width: 768px)  { .gallery-mosaic-grid { columns: 2; column-gap: 12px; } .gallery-mosaic-item { margin-bottom: 12px; } }
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
