@extends('layouts.lawfirm')

@section('title', 'Our Services | JOSHA GOLD AND DIAMOND JEWELLERY')

@section('content')
<section class="pas-page">
    @include('layouts.partials.page-head', [
        'eyebrow' => 'Our services',
        'title'   => 'Gold & Silver Services',
        'accent'  => 'You Can Trust',
        'sub'     => 'Transparent pricing, dependable quality and service you can count on.',
        'crumbs'  => [['Services']],
    ])

    <div class="jb-wrap pas-page-body">
        {{-- Cards Grid --}}
        <div class="pas-grid">
            @forelse($services as $index => $service)
            <div class="pas-card" data-aos="fade-up" data-aos-delay="{{ $index * 80 }}">
                @if($service->image)
                <div class="pas-card-media">
                    <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}" loading="lazy">
                </div>
                @endif
                <div class="pas-card-body">
                    <h3 class="pas-card-title">{{ $service->title }}</h3>
                    @if(filled($service->description))<p class="pas-card-text">{{ $service->description }}</p>@endif
                </div>
            </div>
            @empty
            <div class="pas-empty text-center">
                <h3 class="text-navy">Services Coming Soon</h3>
                <p class="text-muted mb-0">Please contact us for details of our services.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .pas-page { background: var(--jb-ground); }
    .pas-page-body { padding-top: 40px; padding-bottom: 64px; }
    @media (max-width: 767px) { .pas-page-body { padding-top: 28px; padding-bottom: 44px; } }

    .pas-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 18px; }
    .pas-card {
        position: relative; display: flex; flex-direction: column; overflow: hidden;
        border: 1px solid var(--jb-line); border-radius: var(--jb-r-card);
        background: linear-gradient(180deg, var(--jb-panel-2), var(--jb-panel));
        transition: transform .2s, border-color .2s, box-shadow .2s;
    }
    .pas-card::after {
        content: ''; position: absolute; left: 18%; right: 18%; bottom: 0; height: 1px;
        background: linear-gradient(90deg, transparent, var(--jb-gold-deep), transparent); opacity: .8;
    }
    .pas-card-media {
        aspect-ratio: 16 / 10; overflow: hidden; border-bottom: 1px solid var(--jb-line);
        background: radial-gradient(circle at 50% 42%, #17171b 0%, #08080a 90%);
    }
    .pas-card-media img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .45s ease; }
    @media (hover: hover) {
        .pas-card:hover { transform: translateY(-3px); border-color: var(--jb-line-strong); box-shadow: 0 16px 36px -14px rgba(0, 0, 0, .9); }
        .pas-card:hover .pas-card-media img { transform: scale(1.04); }
    }
    .pas-card-body { flex: 1; padding: 22px 22px 26px; }
    .pas-card-title {
        margin: 0; font-family: var(--jb-serif); font-size: 18px; font-weight: 600;
        color: var(--jb-white); line-height: 1.3;
    }
    .pas-card-text { margin: 8px 0 0; font-size: 14px; color: var(--jb-muted); line-height: 1.65; }

    .pas-empty {
        grid-column: 1 / -1; padding: 56px 24px;
        border: 1px dashed var(--jb-line-strong); border-radius: var(--jb-r-card); background: var(--jb-panel);
    }

    @media (max-width: 991px) {
        .pas-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
    }
    @media (max-width: 576px) {
        .pas-grid { grid-template-columns: 1fr; gap: 12px; }
        .pas-card-body { padding: 18px 18px 20px; }
    }
</style>
@endpush
