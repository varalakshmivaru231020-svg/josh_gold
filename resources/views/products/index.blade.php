@extends('layouts.lawfirm')

@section('title', 'Authoritative Legal Publications | JOSHA GOLD AND DIAMOND JEWELLERY')

@section('content')
@include('layouts.partials.page-head', [
    'eyebrow' => 'Publications & Books',
    'title'   => 'Our',
    'accent'  => 'Publications',
    'sub'     => 'Landmark legal guides, celebrated Kannada novels and academic resources authored by Advocate Veerendrababu',
    'crumbs'  => [['Publications']],
])

<section class="publications-luxury-page">
    <div class="jb-wrap pub-products-container">
        <div class="pub-cards-grid">
            {{-- Static Books as per request --}}
            <div class="pub-card" data-aos="fade-up" data-aos-delay="0">
                <div class="pub-badge">2018</div>
                <div class="pub-cover">
                    <div class="pub-icon-bg"><span class="pub-emoji">🌾</span></div>
                    <div class="pub-book-meta-top">
                        <h3 class="pub-book-title">Swayam Krushi</h3>
                    </div>
                </div>
                <div class="pub-category-tag">KANNADA LITERATURE</div>
                <div class="pub-card-body">
                    <p class="pub-desc">A landmark Kannada work on self-sustaining agriculture, championing the dignity, wisdom, and resilience of farmers. Beloved across rural Karnataka, this impactful book was later adapted into a celebrated documentary.</p>
                </div>
            </div>

            <div class="pub-card" data-aos="fade-up" data-aos-delay="100">
                <div class="pub-badge">2026</div>
                <div class="pub-cover">
                    <div class="pub-icon-bg"><span class="pub-emoji">💛</span></div>
                    <div class="pub-book-meta-top">
                        <h3 class="pub-book-title">Thanks Appa</h3>
                    </div>
                </div>
                <div class="pub-category-tag">KANNADA NOVEL</div>
                <div class="pub-card-body">
                    <p class="pub-desc">A deeply emotional Kannada novel — a son's tribute to a father's wisdom, discipline, and unconditional love. Blending business insights with heartfelt storytelling, this work is a must-read for the new generation.</p>
                    @php
                        $thanksAppaProduct = \App\Models\Product::where('name', 'like', '%Thanks Appa%')->first();
                    @endphp
                    <a href="{{ $thanksAppaProduct ? route('products.show', $thanksAppaProduct) : route('products.index') }}" class="pub-buy-btn jb-btn jb-btn--primary mt-auto">Buy Book</a>
                </div>
            </div>

            <div class="pub-card" data-aos="fade-up" data-aos-delay="200">
                <div class="pub-badge">Legal</div>
                <div class="pub-cover">
                    <div class="pub-icon-bg"><span class="pub-emoji">⚖️</span></div>
                    <div class="pub-book-meta-top">
                        <h3 class="pub-book-title">State v/s Abhiram</h3>
                    </div>
                </div>
                <div class="pub-category-tag">LEGAL PUBLICATION</div>
                <div class="pub-card-body">
                    <p class="pub-desc">A significant legal publication examining criminal justice through the lens of a landmark case. Written with the precision of an experienced advocate and the clarity of a storyteller.</p>
                </div>
            </div>

            <div class="pub-card" data-aos="fade-up" data-aos-delay="300">
                <div class="pub-badge">12-Volume</div>
                <div class="pub-cover">
                    <div class="pub-icon-bg"><span class="pub-emoji">📚</span></div>
                    <div class="pub-book-meta-top">
                        <h3 class="pub-book-title">Dainandina Jeevanakkagi Kanunu</h3>
                    </div>
                </div>
                <div class="pub-category-tag">12-VOLUME LEGAL</div>
                <div class="pub-card-body">
                    <p class="pub-desc">A monumental 12-volume legal guide series in Kannada — making the law accessible to every citizen in their own language. An invaluable resource for homes, professionals, and students.</p>
                </div>
            </div>

            <div class="pub-card" data-aos="fade-up" data-aos-delay="400">
                <div class="pub-badge">2026</div>
                <div class="pub-cover">
                    <div class="pub-icon-bg"><span class="pub-emoji">📖</span></div>
                    <div class="pub-book-meta-top">
                        <h3 class="pub-book-title">AIBE–2026: Complete Guide</h3>
                    </div>
                </div>
                <div class="pub-category-tag">EXAM GUIDE</div>
                <div class="pub-card-body">
                    <p class="pub-desc">A comprehensive and practical reference book for law graduates preparing for the All India Bar Examination. Covers all 19 core law subjects, landmark judgments, latest BNS, BNSS, BSA 2023 updates, practice MCQs, and exam strategies.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    /* ── PUBLICATIONS PAGE (dark bullion cards, after the home page .jb-card) ── */
    .publications-luxury-page { background: var(--jb-ground); padding: 32px 0 48px; }
    @media (min-width: 768px) { .publications-luxury-page { padding: 48px 0 64px; } }

    /* Cards grid */
    .pub-cards-grid { display: grid; grid-template-columns: minmax(0, 1fr); gap: 14px; }
    @media (min-width: 576px) { .pub-cards-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; } }
    @media (min-width: 992px) { .pub-cards-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 18px; } }
    @media (min-width: 1200px) { .pub-cards-grid { grid-template-columns: repeat(5, minmax(0, 1fr)); } }

    .pub-card {
        position: relative; display: flex; flex-direction: column; height: 100%; overflow: hidden;
        border: 1px solid var(--jb-line); border-radius: var(--jb-r-card); background: var(--jb-black);
        box-shadow: 0 4px 10px rgba(0, 0, 0, .8); transition: transform .2s, border-color .2s, box-shadow .2s;
    }
    .pub-card::after { content: ""; position: absolute; left: 18%; right: 18%; bottom: 0; height: 1px; background: linear-gradient(90deg, transparent, var(--jb-gold-deep), transparent); opacity: .8; }
    @media (hover: hover) {
        .pub-card:hover { transform: translateY(-3px); border-color: var(--jb-line-strong); box-shadow: 0 16px 36px -12px rgba(0, 0, 0, .9); }
    }

    /* Year badge (as .jb-chip--gold) */
    .pub-badge {
        position: absolute; top: 10px; left: 10px; z-index: 2;
        display: inline-flex; align-items: center; height: 21px; padding: 0 8px; border-radius: 7px;
        color: var(--jb-gold-light); background: rgba(201, 163, 92, .16); border: 1px solid rgba(201, 163, 92, .4);
        backdrop-filter: blur(8px); font-size: 11px; font-weight: 700; letter-spacing: .02em; white-space: nowrap;
    }

    .pub-cover {
        position: relative; display: flex; flex-direction: column; align-items: center; justify-content: center;
        min-height: 200px; padding: 44px 18px 22px; text-align: center;
        background: radial-gradient(circle at 50% 40%, #1a1a1f 0%, #08080a 90%);
        border-bottom: 1px solid var(--jb-line-soft);
    }
    .pub-icon-bg { margin-bottom: 14px; }
    .pub-emoji { display: block; font-size: 3rem; line-height: 1; filter: drop-shadow(0 6px 16px rgba(0, 0, 0, .6)); }
    .pub-book-title { margin: 0; color: var(--jb-white); font: 600 17px/1.3 var(--jb-serif); }

    /* Category tag */
    .pub-category-tag {
        padding: 10px 16px; text-align: center; color: var(--jb-gold);
        background: rgba(201, 163, 92, .05); border-bottom: 1px solid var(--jb-line-soft);
        font-size: 10.5px; font-weight: 700; letter-spacing: .2em; text-transform: uppercase;
    }

    /* Card body */
    .pub-card-body { flex: 1; display: flex; flex-direction: column; gap: 16px; padding: 16px 16px 20px; }
    .pub-desc { margin: 0; color: var(--jb-muted); font-size: 13.5px; line-height: 1.65; }

    /* Buy button (champagne, as .jb-btn--primary) */
    .pub-buy-btn { width: 100%; min-height: 40px; }

    @media (max-width: 575px) {
        .pub-cover { min-height: 170px; padding-top: 40px; }
        .pub-card-body { padding: 14px 16px 18px; }
    }
</style>
@endpush
