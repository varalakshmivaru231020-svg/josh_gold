@extends('layouts.lawfirm')

@section('title', 'Authoritative Legal Publications | Bengaluru Law House')

@section('content')
<section class="publications-luxury-page">
    <div class="pub-hero-header">
        {{-- decorative background icon --}}
        <div class="pub-hero-deco" aria-hidden="true"><i class="fas fa-book-journal-whills"></i></div>
        <div class="container text-center" data-aos="fade-up">
            <div class="label-gold">PUBLICATIONS &amp; BOOKS</div>
            <h1 class="heading-premium text-white">Our Publications</h1>
            <div class="accent-line-gold mx-auto"></div>
            <p class="pub-subheader text-white opacity-75">Landmark legal guides, celebrated Kannada novels and academic resources authored by Advocate Veerendrababu</p>
        </div>
    </div>

    <div class="container pub-products-container">
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
                    <a href="{{ $thanksAppaProduct ? route('products.show', $thanksAppaProduct) : route('products.index') }}" class="pub-buy-btn mt-auto">Buy Book</a>
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
    /* ── PUBLICATIONS PAGE ── */
    .publications-luxury-page { background: var(--cream, #f9f5ed); min-height: 80vh; padding-bottom: 120px; }

    /* Banner / Hero Header */
    .pub-hero-header { 
        background: 
            linear-gradient(rgba(8,14,28,0.85) 0%, rgba(10,18,36,0.9) 100%),
            url('/images/publications-banner.jpg') center center / cover no-repeat;
        padding: 160px 0 100px;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }
    .pub-hero-header > * { position: relative; z-index: 2; }
    .pub-hero-header .label-gold { display: block; margin-bottom: 16px; color: var(--gold); font-weight: 700; letter-spacing: 2px; }
    .pub-hero-header .heading-premium { font-size: clamp(2.2rem, 5vw, 3.5rem); font-weight: 700; color: white; margin-bottom: 20px; }
    .pub-hero-header .accent-line-gold { width: 60px; height: 3px; background: var(--gold, #c9a84c); margin: 18px auto 22px; border-radius: 2px; }
    .pub-subheader { max-width: 750px; margin: 0 auto; font-family: var(--font-display); font-size: 1.25rem; font-style: italic; color: rgba(255,255,255,0.8); line-height: 1.6; }

    /* Cards Grid */
    .pub-products-container { padding-top: 80px; }
    .pub-cards-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 24px;
        margin-bottom: 50px;
        overflow-x: auto;
        padding: 10px 0;
    }

    .pub-card {
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.06);
        border: 1px solid rgba(201,168,76,0.1);
        display: flex;
        flex-direction: column;
        position: relative;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        height: 100%;
    }
    .pub-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 30px 70px rgba(0,0,0,0.12);
        border-color: rgba(201,168,76,0.3);
    }

    /* Year Badge */
    .pub-badge {
        position: absolute; top: 18px; right: 18px;
        background: var(--gold, #c9a84c);
        color: var(--navy, #0c1428);
        font-size: 0.65rem; font-weight: 800; letter-spacing: 1.5px;
        padding: 5px 12px; border-radius: 50px; text-transform: uppercase; z-index: 2;
    }

    .pub-cover {
        background: linear-gradient(160deg, var(--navy, #0c1428), #1e3060);
        padding: 50px 24px 40px;
        text-align: center;
        position: relative;
        min-height: 220px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .pub-icon-bg { margin-bottom: 20px; }
    .pub-emoji { font-size: 3.8rem; display: block; filter: drop-shadow(0 4px 15px rgba(0,0,0,0.4)); }
    .pub-book-title {
        font-family: var(--font-display); font-size: 1.25rem; color: #fff;
        font-weight: 700; line-height: 1.3; margin: 0; text-shadow: 0 2px 10px rgba(0,0,0,0.4);
    }

    /* Category tag */
    .pub-category-tag {
        background: rgba(201,168,76,0.08);
        border-bottom: 1px solid rgba(201,168,76,0.1);
        color: var(--gold-dark, #b8913a);
        font-size: 0.65rem; font-weight: 800; letter-spacing: 2px;
        text-transform: uppercase; padding: 10px 18px; text-align: center;
    }

    /* Card body */
    .pub-card-body { padding: 30px; flex: 1; display: flex; flex-direction: column; }
    .pub-desc { font-size: 0.95rem; color: #555; line-height: 1.75; margin: 0; }

    /* Buy Button */
    .pub-buy-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        align-self: center;
        background: linear-gradient(135deg, var(--gold), #b39441);
        color: var(--navy) !important;
        padding: 12px 28px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.85rem;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 8px 20px rgba(201,168,76,0.25);
        border: none;
        cursor: pointer;
        text-align: center;
    }
    .pub-buy-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(201,168,76,0.35);
        filter: brightness(1.1);
        color: var(--navy) !important;
    }

    @media (max-width: 1400px) {
        .pub-cards-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
    }
    @media (max-width: 1200px) {
        .pub-cards-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    }
    @media (max-width: 768px) {
        .pub-hero-header { padding: 120px 0 80px; }
        .pub-cards-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px; }
        .pub-card-body { padding: 20px; }
    }
    @media (max-width: 576px) {
        .pub-cards-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush
