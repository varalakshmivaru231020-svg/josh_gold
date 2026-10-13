@extends('layouts.lawfirm')

@section('title', $page->title . ' | JOSHA GOLD AND DIAMOND JEWELLERY')

@section('content')
{{-- The banner image chosen in admin sits behind the heading, darkened --}}
<div class="cms-head{{ $page->banner_image ? ' cms-head--image' : '' }}" @if($page->banner_image) style="--cms-banner: url('{{ asset('storage/' . $page->banner_image) }}');" @endif>
    @include('layouts.partials.page-head', [
        'title'  => $page->banner_title ?? $page->title,
        'crumbs' => [[$page->title]],
    ])
</div>

<section class="cms-luxury-page">
    <div class="jb-wrap cms-body">
        @php
            $hasContent = $page->content && trim(strip_tags($page->content)) !== '';
        @endphp
        
        @if($hasContent)
        <div class="cms-content-card" data-aos="fade-up">
            <div class="rich-text-legal">
                {!! $page->content !!}
            </div>
        </div>
        @endif

        {{-- Dynamic Sections handled by Admin CMS --}}
        <div class="dynamic-sections-wrap mt-5">
            @foreach($page->sections->where('is_active', true)->sortBy('sort_order') as $section)
                <div class="dynamic-section-item" data-aos="fade-up">
                    @includeIf('sections.' . $section->section_type, ['section' => $section])
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .cms-head--image .jb-pagehead {
        background: linear-gradient(rgba(0, 0, 0, .78), rgba(0, 0, 0, .9)), var(--cms-banner) center / cover no-repeat, var(--jb-black);
    }
    @media (min-width: 768px) { .cms-head--image .jb-pagehead { padding: 80px 0 72px; } }

    .cms-luxury-page { padding-bottom: 56px; }
    .cms-body { padding-top: 32px; padding-bottom: 16px; }
    @media (min-width: 768px) { .cms-body { padding-top: 48px; } }

    .cms-content-card {
        position: relative; padding: 48px;
        border: 1px solid var(--jb-line); border-radius: var(--jb-r-card);
        background: linear-gradient(180deg, rgba(201, 163, 92, .05), transparent 40%), var(--jb-panel);
    }
    .cms-content-card::before {
        content: ''; position: absolute; top: -1px; left: 18%; right: 18%; height: 1px;
        background: linear-gradient(90deg, transparent, var(--jb-gold-deep), transparent);
    }

    .rich-text-legal { font-size: 16.5px; line-height: 1.85; color: var(--jb-text); overflow-wrap: break-word; }
    .rich-text-legal h2 {
        margin-top: 40px; margin-bottom: 20px; padding-bottom: 16px;
        border-bottom: 1px solid var(--jb-line-soft);
        font-family: var(--jb-serif); color: var(--jb-white); font-size: 30px; font-weight: 600;
    }
    .rich-text-legal h3, .rich-text-legal h4 { font-family: var(--jb-serif); color: var(--jb-white); font-weight: 600; }
    .rich-text-legal p { margin-bottom: 24px; }
    .rich-text-legal a { color: var(--jb-gold-light); }
    .rich-text-legal a:hover { color: var(--jb-white); }
    .rich-text-legal strong, .rich-text-legal b { color: var(--jb-white); }
    .rich-text-legal img { max-width: 100%; height: auto; border-radius: 12px; }
    .rich-text-legal ul { margin-bottom: 24px; padding-left: 28px; }
    .rich-text-legal li { margin-bottom: 12px; position: relative; list-style: none; }
    .rich-text-legal li::before { content: '◈'; position: absolute; left: -26px; color: var(--jb-gold); font-size: 0.9rem; }

    @media (max-width: 991px) {
        .cms-content-card { padding: 32px; }
        .rich-text-legal h2 { font-size: 26px; }
    }
    @media (max-width: 575px) {
        .cms-content-card { padding: 22px; }
        .rich-text-legal { font-size: 15.5px; }
        .rich-text-legal h2 { font-size: 22px; }
    }
</style>
@endpush
