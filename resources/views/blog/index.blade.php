@extends('layouts.lawfirm')

@section('title', 'Updates | Josha Gold and Diamond Jewellery')
@section('meta_description', 'News, new arrivals, offers and announcements from Josha Gold and Diamond Jewellery.')

{{-- $blogs: paginated active posts (Admin → Updates). The first post of each page leads in a wide card. --}}
@php
    $lead    = $blogs->first();
    $rest    = $blogs->slice(1);
    $isFirst = $blogs->onFirstPage();
@endphp

@section('content')
@include('layouts.partials.page-head', [
    'eyebrow' => 'Updates',
    'title'   => 'The latest from',
    'accent'  => 'Josha Gold',
    'sub'     => 'News, new arrivals, offers and announcements from our showroom.',
    'crumbs'  => [['Updates']],
])

<section class="upd-page">
    <div class="jb-wrap">
        @if($lead)
            {{-- Lead post: the newest update on the first page --}}
            <a href="{{ route('updates.show', $lead->slug) }}" class="upd-feature" data-aos="fade-up">
                @include('blog._cover', ['post' => $lead, 'eager' => true])
                <div class="upd-feature__body">
                    <div class="upd-feature__meta">
                        @if($isFirst)<span class="upd-flag">Latest</span>@endif
                        <time class="upd-date" datetime="{{ $lead->created_at->toDateString() }}">{{ $lead->created_at->format('d M Y') }}</time>
                    </div>
                    <h2 class="upd-feature__title">{{ $lead->title }}</h2>
                    @if(filled($lead->excerpt))
                        <p class="upd-feature__excerpt">{{ $lead->excerpt }}</p>
                    @endif
                    <span class="jb-btn jb-btn--primary upd-feature__cta">Read update <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
                </div>
            </a>

            @if($rest->isNotEmpty())
                <div class="upd-head">
                    <h2>{{ $isFirst ? 'More updates' : 'Earlier updates' }}</h2>
                    <span class="upd-head__count">{{ $blogs->total() }} {{ \Illuminate\Support\Str::plural('update', $blogs->total()) }}</span>
                </div>

                <div class="upd-grid">
                    @foreach($rest as $post)
                        <div data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 60 }}">
                            @include('blog._card', ['post' => $post])
                        </div>
                    @endforeach
                </div>
            @endif

            @if($blogs->hasPages())
                <nav class="upd-pagination" aria-label="More updates">
                    {{ $blogs->links() }}
                </nav>
            @endif
        @else
            <div class="upd-empty" data-aos="fade-up">
                <span class="upd-empty__icon" aria-hidden="true"><i class="fas fa-newspaper"></i></span>
                <h2>No updates yet</h2>
                <p>News, new arrivals and offers will appear here. Please check back soon.</p>
                <div class="upd-empty__actions">
                    <a href="{{ route('rates.gold') }}" class="jb-btn jb-btn--primary">Today's gold rate</a>
                    <a href="{{ route('contact') }}" class="jb-btn jb-btn--ghost">Contact us</a>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
@include('blog._styles')
<style>
    /* Lead post */
    .upd-feature {
        display: grid; grid-template-columns: 1fr; overflow: hidden; margin-bottom: 48px; text-decoration: none; color: inherit;
        background: var(--jb-surface); border: 1px solid var(--jb-line); border-radius: 22px; box-shadow: var(--jb-shadow-lg);
        transition: border-color .25s, box-shadow .25s;
    }
    .upd-feature:hover { color: inherit; }
    .upd-feature .upd-cover { aspect-ratio: 16 / 10; }
    .upd-feature__body {
        position: relative; display: flex; flex-direction: column; align-items: flex-start; justify-content: center; padding: 24px 22px 26px;
        background: radial-gradient(520px 260px at 100% 0%, rgba(214, 178, 95, .16), transparent 70%), var(--jb-surface);
    }
    .upd-feature__meta { display: flex; flex-wrap: wrap; align-items: center; gap: 12px; }
    .upd-flag {
        display: inline-flex; align-items: center; height: 24px; padding: 0 10px; border-radius: 999px;
        background: var(--jb-gold-band); color: #2a1d06; border: 1px solid #b48b3c;
        font-size: 10.5px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase;
    }
    .upd-feature__title { margin: 14px 0 10px !important; font-family: var(--jb-serif); font-size: clamp(23px, 2.7vw, 34px); font-weight: 600; line-height: 1.2; color: var(--jb-ink); }
    .upd-feature__excerpt {
        margin: 0 0 22px !important; color: var(--jb-text); font-size: 15.5px; line-height: 1.7;
        display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden;
    }
    .upd-feature__cta i { font-size: 11px; transition: transform .2s; }
    @media (min-width: 992px) {
        .upd-feature { grid-template-columns: minmax(0, 1.2fr) minmax(0, 1fr); margin-bottom: 64px; }
        .upd-feature .upd-cover { aspect-ratio: auto; height: 100%; min-height: 420px; }
        .upd-feature__body { padding: 48px 52px; }
    }
    @media (hover: hover) {
        .upd-feature:hover { border-color: var(--jb-line-strong); }
        .upd-feature:hover .upd-cover img { transform: scale(1.04); }
        .upd-feature:hover .upd-feature__cta i { transform: translateX(4px); }
    }
    .upd-feature:focus-visible { outline: 2px solid var(--jb-gold); outline-offset: 3px; }

    .upd-pagination { margin-top: 48px; }
    .upd-pagination .pagination { justify-content: center; flex-wrap: wrap; gap: 6px; }
    .upd-pagination .page-link { border-radius: 10px !important; padding: 9px 15px; font-weight: 600; }

    /* No posts yet */
    .upd-empty {
        max-width: 640px; margin: 0 auto; padding: 48px 24px; text-align: center;
        background: radial-gradient(420px 200px at 50% 0%, rgba(214, 178, 95, .16), transparent 70%), var(--jb-surface);
        border: 1px solid var(--jb-line); border-radius: var(--jb-r-card); box-shadow: var(--jb-shadow);
    }
    @media (min-width: 768px) { .upd-empty { padding: 64px 48px; } }
    .upd-empty__icon { display: inline-grid; place-items: center; width: 64px; height: 64px; margin-bottom: 18px; border-radius: 50%; background: #fbf1da; border: 1px solid #ecd49c; color: var(--jb-gold-strong); font-size: 24px; }
    .upd-empty h2 { margin: 0 0 8px !important; font-size: 26px; font-weight: 600; }
    .upd-empty p { margin: 0 auto 24px !important; max-width: 420px; color: var(--jb-muted); font-size: 15px; line-height: 1.65; }
    .upd-empty__actions { display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; }
</style>
@endpush
