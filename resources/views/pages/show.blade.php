@extends('layouts.lawfirm')

@section('title', $page->title . ' | JOSHA GOLD AND DIAMOND JEWELLERY')

@section('content')
{{-- The banner image chosen in admin sits behind the heading, darkened, with white text --}}
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
        background: linear-gradient(rgba(20, 14, 4, .62), rgba(20, 14, 4, .8)), var(--cms-banner) center / cover no-repeat, var(--jb-panel-3);
        border-bottom: 3px solid var(--jb-gold);
    }
    /* Text on the photo stays white */
    .cms-head--image .jb-pagehead__title, .cms-head--image .jb-pagehead__sub { color: #fff; }
    .cms-head--image .jb-pagehead__crumbs { color: rgba(255, 255, 255, .78); }
    .cms-head--image .jb-pagehead__crumbs a { color: #f0d79b; }
    .cms-head--image .jb-pagehead__crumbs a:hover { color: #fff; }
    @media (min-width: 768px) { .cms-head--image .jb-pagehead { padding: 80px 0 72px; } }

    .cms-luxury-page { padding-bottom: 56px; }
    .cms-body { padding-top: 32px; padding-bottom: 16px; }
    @media (min-width: 768px) { .cms-body { padding-top: 48px; } }

    .cms-content-card {
        position: relative; padding: 48px;
        border: 1px solid var(--jb-line); border-radius: var(--jb-r-card);
        background: linear-gradient(180deg, #fffaf0, #fff 30%); box-shadow: var(--jb-shadow-lg);
    }
    .cms-content-card::before {
        content: ''; position: absolute; top: -1px; left: 18%; right: 18%; height: 2px;
        background: linear-gradient(90deg, transparent, var(--jb-gold), transparent);
    }

    /* Long-form text (policies, terms): a comfortable reading width */
    .cms-content-card { max-width: 980px; margin: 0 auto; }

    .rich-text-legal { font-size: 16.5px; line-height: 1.8; color: var(--jb-text); overflow-wrap: break-word; }
    .rich-text-legal > :first-child { margin-top: 0; }
    .rich-text-legal > :last-child { margin-bottom: 0; }
    .rich-text-legal h2 {
        margin-top: 44px; margin-bottom: 18px; padding-bottom: 14px;
        border-bottom: 1px solid var(--jb-line);
        font-family: var(--jb-serif); color: var(--jb-ink); font-size: 28px; font-weight: 600; line-height: 1.25;
    }
    .rich-text-legal h3, .rich-text-legal h4 { font-family: var(--jb-serif); color: var(--jb-ink); font-weight: 600; line-height: 1.3; }
    .rich-text-legal h3 { margin: 30px 0 12px; font-size: 21px; }
    .rich-text-legal h4 { margin: 24px 0 10px; font-size: 18px; }
    .rich-text-legal :is(h2, h3, h4)[id] { scroll-margin-top: calc(var(--jb-header-h, 52px) + 24px); }
    .rich-text-legal p { margin-bottom: 20px; }
    .rich-text-legal a { color: var(--jb-gold-strong); text-decoration: underline; text-decoration-thickness: 1px; text-underline-offset: 3px; }
    .rich-text-legal a:hover { color: var(--jb-gold-deep); text-decoration-thickness: 2px; }
    .rich-text-legal strong, .rich-text-legal b { color: var(--jb-ink); }
    .rich-text-legal img { max-width: 100%; height: auto; border-radius: 12px; }
    .rich-text-legal ul, .rich-text-legal ol { margin-bottom: 22px; padding-left: 28px; }
    .rich-text-legal li { margin-bottom: 10px; position: relative; }
    .rich-text-legal ul > li { list-style: none; }
    .rich-text-legal ul > li::before { content: '◈'; position: absolute; left: -26px; color: var(--jb-gold); font-size: 0.9rem; }
    .rich-text-legal ol > li { list-style: decimal; padding-left: 4px; }
    .rich-text-legal ol > li::marker { color: var(--jb-gold-strong); font-weight: 700; }
    .rich-text-legal code {
        padding: 2px 7px; border-radius: 6px; background: var(--jb-panel-3); color: var(--jb-ink);
        font-size: .86em; overflow-wrap: anywhere;
    }

    /* Policy page parts: "Last updated" chip, intro paragraph, table of contents */
    .rich-text-legal .policy-updated {
        display: inline-block; margin-bottom: 18px; padding: 6px 14px;
        border: 1px solid var(--jb-line-strong); border-radius: 999px; background: #fffaf0;
        color: var(--jb-muted); font-size: 12px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; line-height: 1.4;
    }
    .rich-text-legal .policy-lead { font-size: 18px; line-height: 1.75; color: var(--jb-ink); }
    .rich-text-legal .policy-toc {
        margin: 8px 0 32px; padding: 20px 24px;
        border: 1px solid var(--jb-line); border-left: 3px solid var(--jb-gold); border-radius: 14px; background: #fffaf0;
    }
    .rich-text-legal .policy-toc__title { margin: 0 0 10px; color: var(--jb-gold-strong); font-size: 11px; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; }
    .rich-text-legal .policy-toc ol { margin: 0; padding-left: 24px; columns: 2; column-gap: 40px; font-size: 15px; }
    .rich-text-legal .policy-toc li { margin-bottom: 6px; break-inside: avoid; }
    .rich-text-legal .policy-toc a { text-decoration: none; }
    .rich-text-legal .policy-toc a:hover { text-decoration: underline; }

    /* Details still to be confirmed by the owner: impossible to miss */
    .rich-text-legal mark, .rich-text-legal .tbc { padding: 0; background: #fff1c2; color: inherit; }
    .rich-text-legal mark.tbc, .rich-text-legal .tbc {
        padding: 1px 6px; border: 1px dashed #c98a00; border-radius: 6px;
        background: #ffe8a3; color: #5a3a00; font-weight: 600;
        -webkit-box-decoration-break: clone; box-decoration-break: clone;
    }

    /* Tables */
    .rich-text-legal table {
        width: 100%; margin: 6px 0 26px; border-collapse: separate; border-spacing: 0;
        border: 1px solid var(--jb-line); border-radius: 14px; overflow: hidden;
        background: #fff; box-shadow: var(--jb-shadow); font-size: 15px; line-height: 1.6;
    }
    .rich-text-legal th, .rich-text-legal td { padding: 13px 16px; text-align: left; vertical-align: top; }
    .rich-text-legal th {
        background: #fbf1da; border-bottom: 1px solid var(--jb-line);
        color: var(--jb-ink); font-family: var(--jb-sans); font-size: 11.5px; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
    }
    .rich-text-legal td { border-bottom: 1px solid var(--jb-line-soft); }
    .rich-text-legal tbody tr:last-child td { border-bottom: 0; }
    .rich-text-legal tbody tr:nth-child(even) td { background: #fffdf7; }
    .rich-text-legal td a { overflow-wrap: anywhere; }

    @media (max-width: 991px) {
        .cms-content-card { padding: 32px; }
        .rich-text-legal h2 { font-size: 25px; }
    }
    @media (max-width: 720px) {
        .rich-text-legal .policy-toc ol { columns: 1; }
        /* Tables whose cells carry data-label become stacked cards on phones */
        .rich-text-legal table:has(td[data-label]) { border: 0; border-radius: 0; overflow: visible; background: none; box-shadow: none; }
        .rich-text-legal table:has(td[data-label]) :is(thead, tbody, tr, td) { display: block; }
        .rich-text-legal table:has(td[data-label]) thead { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0 0 0 0); white-space: nowrap; }
        .rich-text-legal table:has(td[data-label]) tr {
            margin-bottom: 12px; padding: 12px 16px;
            border: 1px solid var(--jb-line); border-radius: 14px; background: #fff; box-shadow: var(--jb-shadow);
        }
        .rich-text-legal table:has(td[data-label]) td { padding: 6px 0; border: 0; background: none !important; }
        .rich-text-legal table:has(td[data-label]) td + td { border-top: 1px dashed var(--jb-line-soft); }
        .rich-text-legal td[data-label]::before {
            content: attr(data-label); display: block; margin-bottom: 2px;
            color: var(--jb-gold-strong); font-size: 10.5px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase;
        }
        /* Any other table scrolls sideways instead of squeezing */
        .rich-text-legal table:not(:has(td[data-label])) { display: block; overflow-x: auto; }
    }
    @media (max-width: 575px) {
        .cms-content-card { padding: 22px 18px; }
        .rich-text-legal { font-size: 15.5px; }
        .rich-text-legal h2 { font-size: 22px; margin-top: 36px; }
        .rich-text-legal h3 { font-size: 19px; }
        .rich-text-legal .policy-lead { font-size: 16.5px; }
        .rich-text-legal .policy-toc { padding: 16px 18px; }
        .rich-text-legal ul, .rich-text-legal ol { padding-left: 24px; }
        .rich-text-legal ul > li::before { left: -22px; }
    }
</style>
@endpush
