@extends('layouts.lawfirm')

@section('title', $blog->title . ' | Updates | Josha Gold and Diamond Jewellery')
@section('meta_description', $blog->excerpt)

{{-- $blog, $recentBlogs (sidebar), $relatedBlogs (end of the article), $previousBlog, $nextBlog --}}
@php
    $readTime = max(1, (int) ceil(str_word_count(strip_tags((string) $blog->description)) / 200));
    $shareUrl = route('updates.show', $blog->slug);
@endphp

@section('content')
@include('layouts.partials.page-head', [
    'eyebrow' => 'Updates · ' . $blog->created_at->format('d M Y'),
    'title'   => $blog->title,
    'sub'     => 'Josha Gold and Diamond Jewellery · ' . $readTime . ' min read',
    'crumbs'  => [['Updates', route('updates')], [\Illuminate\Support\Str::limit($blog->title, 60)]],
])

<section class="upd-page">
    <div class="jb-wrap">
        <div class="upd-layout">
            <article class="upd-article" data-aos="fade-up">
                @if($blog->image_url)
                    <figure class="upd-article__cover">
                        <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" decoding="async"
                             onerror="this.closest('figure').remove();">
                    </figure>
                @endif

                <div class="upd-article__body">
                    <div class="upd-prose">
                        {!! $blog->description !!}
                    </div>

                    <footer class="upd-article__foot">
                        <time class="upd-date" datetime="{{ $blog->created_at->toDateString() }}">Posted {{ $blog->created_at->format('d M Y') }}</time>
                        <div class="upd-article__actions">
                            <a href="https://wa.me/?text={{ urlencode($blog->title . ' - ' . $shareUrl) }}" target="_blank" rel="noopener" class="jb-btn jb-btn--ghost jb-btn--sm">
                                <i class="fab fa-whatsapp" aria-hidden="true"></i> Share
                            </a>
                            <a href="{{ route('updates') }}" class="jb-btn jb-btn--ghost jb-btn--sm">
                                <i class="fas fa-arrow-left" aria-hidden="true"></i> All updates
                            </a>
                        </div>
                    </footer>
                </div>

                @if($previousBlog || $nextBlog)
                    <nav class="upd-prevnext" aria-label="Previous and next update">
                        @if($previousBlog)
                            <a href="{{ route('updates.show', $previousBlog->slug) }}" class="upd-prevnext__link">
                                <span class="upd-prevnext__label"><i class="fas fa-arrow-left" aria-hidden="true"></i> Previous</span>
                                <span class="upd-prevnext__title">{{ \Illuminate\Support\Str::limit($previousBlog->title, 70) }}</span>
                            </a>
                        @endif
                        @if($nextBlog)
                            <a href="{{ route('updates.show', $nextBlog->slug) }}" class="upd-prevnext__link upd-prevnext__link--next">
                                <span class="upd-prevnext__label">Next <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
                                <span class="upd-prevnext__title">{{ \Illuminate\Support\Str::limit($nextBlog->title, 70) }}</span>
                            </a>
                        @endif
                    </nav>
                @endif
            </article>

            <aside class="upd-aside" data-aos="fade-up" data-aos-delay="80">
                @if($recentBlogs->isNotEmpty())
                    <div class="upd-aside__box">
                        <h2 class="upd-aside__title">Recent updates</h2>
                        <ul class="upd-recent">
                            @foreach($recentBlogs as $recent)
                                <li>
                                    <a href="{{ route('updates.show', $recent->slug) }}" class="upd-recent__link">
                                        @include('blog._cover', ['post' => $recent, 'size' => 'sm'])
                                        <span class="upd-recent__text">
                                            <time class="upd-recent__date" datetime="{{ $recent->created_at->toDateString() }}">{{ $recent->created_at->format('d M Y') }}</time>
                                            <span class="upd-recent__title">{{ \Illuminate\Support\Str::limit($recent->title, 64) }}</span>
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="upd-aside__box upd-aside__box--glow">
                    <p class="jb-eyebrow">Live rates</p>
                    <h2 class="upd-aside__title">Today's gold rate</h2>
                    <p class="upd-aside__text">Published every morning and evening, by purity and weight.</p>
                    <div class="upd-aside__actions">
                        <a href="{{ route('rates.gold') }}" class="jb-btn jb-btn--primary jb-btn--sm">See today's rate</a>
                        <a href="{{ route('contact') }}" class="jb-btn jb-btn--ghost jb-btn--sm">Contact us</a>
                    </div>
                </div>
            </aside>
        </div>

        @if($relatedBlogs->isNotEmpty())
            <section class="upd-related" aria-labelledby="updRelated">
                <div class="upd-head">
                    <h2 id="updRelated">More updates</h2>
                    <a href="{{ route('updates') }}" class="jb-link">View all <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                </div>
                <div class="upd-grid">
                    @foreach($relatedBlogs as $post)
                        <div data-aos="fade-up" data-aos-delay="{{ $loop->index * 60 }}">
                            @include('blog._card', ['post' => $post])
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</section>
@endsection

@push('styles')
@include('blog._styles')
<style>
    .upd-layout { display: grid; gap: 28px; grid-template-columns: minmax(0, 1fr); align-items: start; }
    @media (min-width: 992px) { .upd-layout { grid-template-columns: minmax(0, 1fr) 340px; gap: 40px; } }

    /* Article */
    .upd-article__cover { margin: 0 0 22px; overflow: hidden; border: 1px solid var(--jb-line); border-radius: var(--jb-r-card); background: var(--jb-panel-3); box-shadow: var(--jb-shadow); }
    .upd-article__cover img { display: block; width: 100%; max-height: 520px; object-fit: cover; }
    .upd-article__body { padding: 24px 20px; background: var(--jb-surface); border: 1px solid var(--jb-line); border-radius: var(--jb-r-card); box-shadow: var(--jb-shadow); }
    @media (min-width: 768px) { .upd-article__body { padding: 40px 44px; } }

    .upd-prose { color: var(--jb-text); font-size: 16px; line-height: 1.8; overflow-wrap: break-word; }
    @media (min-width: 768px) { .upd-prose { font-size: 16.5px; } }
    .upd-prose > :first-child { margin-top: 0; }
    .upd-prose > :last-child { margin-bottom: 0; }
    .upd-prose p { margin: 0 0 20px; }
    .upd-prose a { color: var(--jb-gold-strong); text-decoration: underline; text-underline-offset: 3px; }
    .upd-prose a:hover { color: var(--jb-gold-deep); }
    .upd-prose strong, .upd-prose b { color: var(--jb-ink); }
    .upd-prose h2, .upd-prose h3, .upd-prose h4 { margin: 32px 0 14px; font-family: var(--jb-serif); font-weight: 600; line-height: 1.3; color: var(--jb-ink); }
    .upd-prose h2 { font-size: 24px; }
    .upd-prose h3 { font-size: 20px; }
    .upd-prose ul, .upd-prose ol { margin: 0 0 20px; padding-left: 1.3em; }
    .upd-prose li { margin-bottom: 6px; }
    .upd-prose li::marker { color: var(--jb-gold); }
    .upd-prose img { max-width: 100%; height: auto; border-radius: 12px; }
    .upd-prose blockquote {
        margin: 28px 0; padding: 18px 24px; border-left: 3px solid var(--jb-gold); border-radius: 0 14px 14px 0; background: var(--jb-panel-2);
        font-family: var(--jb-serif); font-style: italic; font-size: 1.12rem; color: var(--jb-ink);
    }
    .upd-prose blockquote p:last-child { margin-bottom: 0; }

    .upd-article__foot { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 14px; margin-top: 32px; padding-top: 22px; border-top: 1px solid var(--jb-line-soft); }
    .upd-article__actions { display: flex; flex-wrap: wrap; gap: 8px; }
    .upd-article__actions .fa-whatsapp { color: #1fa855; font-size: 15px; }

    /* Previous / next */
    .upd-prevnext { display: grid; gap: 12px; margin-top: 22px; }
    @media (min-width: 768px) { .upd-prevnext { grid-template-columns: 1fr 1fr; } }
    .upd-prevnext__link {
        display: flex; flex-direction: column; gap: 6px; padding: 16px 20px; text-decoration: none;
        background: var(--jb-surface); border: 1px solid var(--jb-line); border-radius: 14px; transition: border-color .2s, transform .2s;
    }
    .upd-prevnext__link--next { text-align: right; align-items: flex-end; }
    @media (min-width: 768px) { .upd-prevnext__link--next:only-child { grid-column: 2; } }
    .upd-prevnext__link:hover { border-color: var(--jb-line-strong); transform: translateY(-2px); }
    .upd-prevnext__label { display: inline-flex; align-items: center; gap: 6px; color: var(--jb-gold); font-size: 11px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; }
    .upd-prevnext__title { color: var(--jb-ink); font-family: var(--jb-serif); font-size: 16px; font-weight: 600; line-height: 1.35; }

    /* Sidebar */
    .upd-aside { display: grid; gap: 20px; }
    @media (min-width: 992px) { .upd-aside { position: sticky; top: calc(var(--jb-header-h) + 20px); } }
    .upd-aside__box { padding: 22px; background: var(--jb-surface); border: 1px solid var(--jb-line); border-radius: var(--jb-r-card); box-shadow: var(--jb-shadow); }
    .upd-aside__box--glow { background: radial-gradient(360px 180px at 100% 0%, rgba(214, 178, 95, .2), transparent 70%), linear-gradient(180deg, #fffaf0, #fff 70%); }
    .upd-aside__title { margin: 0 0 16px !important; padding-bottom: 14px; border-bottom: 1px solid var(--jb-line-soft); font-size: 19px; font-weight: 600; color: var(--jb-ink); }
    .upd-aside__box--glow .upd-aside__title { margin: 6px 0 8px !important; padding: 0; border: 0; }
    .upd-aside__text { margin: 0 0 16px !important; color: var(--jb-text); font-size: 14px; line-height: 1.6; }
    .upd-aside__actions { display: flex; flex-wrap: wrap; gap: 8px; }

    .upd-recent { margin: 0; padding: 0; list-style: none; display: grid; gap: 14px; }
    .upd-recent__link { display: flex; align-items: center; gap: 14px; text-decoration: none; }
    .upd-recent__text { display: flex; flex-direction: column; gap: 4px; min-width: 0; }
    .upd-recent__date { color: var(--jb-gold); font-size: 10.5px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; }
    .upd-recent__title { color: var(--jb-ink); font-family: var(--jb-serif); font-size: 15px; font-weight: 600; line-height: 1.35; transition: color .15s; }
    .upd-recent__link:hover .upd-recent__title { color: var(--jb-gold-strong); }

    /* More updates */
    .upd-related { margin-top: 56px; padding-top: 40px; border-top: 1px solid var(--jb-line); }
    @media (min-width: 768px) { .upd-related { margin-top: 72px; } }
    .upd-related .upd-head { align-items: center; }
    .upd-related .jb-link i { font-size: 11px; margin-left: 4px; }
</style>
@endpush
