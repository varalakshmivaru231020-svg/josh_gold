@extends('layouts.lawfirm')

@section('title', 'Purchase Books | JOSHA GOLD AND DIAMOND JEWELLERY')

@section('content')
@include('layouts.partials.page-head', [
    'title'  => 'Purchase',
    'accent' => 'Books',
    'crumbs' => [['Purchase Books']],
])

<section class="purchase-section">
    <div class="jb-wrap">
        @php
            // Define available books for purchase
            $books = [
                'thanks-appa' => [
                    'id' => 'thanks-appa',
                    'name' => 'Thanks Appa',
                    'year' => 2026,
                    'category' => 'KANNADA NOVEL',
                    'emoji' => '💛',
                    'price' => 350,
                    'description' => 'A deeply emotional Kannada novel — a son\'s tribute to a father\'s wisdom, discipline, and unconditional love. Blending business insights with heartfelt storytelling, this work is a must-read for the new generation.'
                ],
                'swayam-krushi' => [
                    'id' => 'swayam-krushi',
                    'name' => 'Swayam Krushi',
                    'year' => 2018,
                    'category' => 'KANNADA LITERATURE',
                    'emoji' => '🌾',
                    'price' => 300,
                    'description' => 'A landmark Kannada work on self-sustaining agriculture, championing the dignity, wisdom, and resilience of farmers. Beloved across rural Karnataka, this impactful book was later adapted into a celebrated documentary.'
                ],
                'state-abhiram' => [
                    'id' => 'state-abhiram',
                    'name' => 'State v/s Abhiram',
                    'year' => 2020,
                    'category' => 'LEGAL PUBLICATION',
                    'emoji' => '⚖️',
                    'price' => 450,
                    'description' => 'A significant legal publication examining criminal justice through the lens of a landmark case. Written with the precision of an experienced advocate and the clarity of a storyteller.'
                ],
                'dainandina-kanunu' => [
                    'id' => 'dainandina-kanunu',
                    'name' => 'Dainandina Jeevanakkagi Kanunu',
                    'year' => 2022,
                    'category' => '12-VOLUME LEGAL',
                    'emoji' => '📚',
                    'price' => 2500,
                    'description' => 'A monumental 12-volume legal guide series in Kannada — making the law accessible to every citizen in their own language. An invaluable resource for homes, professionals, and students.'
                ],
                'aibe-2026' => [
                    'id' => 'aibe-2026',
                    'name' => 'AIBE–2026: Complete Guide for Law Graduates',
                    'year' => 2026,
                    'category' => 'EXAM GUIDE',
                    'emoji' => '📖',
                    'price' => 650,
                    'description' => 'A comprehensive and practical reference book for law graduates preparing for the All India Bar Examination. Covers all 19 core law subjects, landmark judgments, latest BNS, BNSS, BSA 2023 updates, practice MCQs, and exam strategies.'
                ]
            ];

            // Get the requested book or show all
            $requestedBook = request()->query('book');
            $displayBooks = $requestedBook && isset($books[$requestedBook]) ?
                [$requestedBook => $books[$requestedBook]] :
                $books;
        @endphp

        <div class="row g-4 justify-content-center">
            @foreach($displayBooks as $bookId => $book)
            <div class="col-lg-6 col-md-8" data-aos="fade-up">
                <div class="purchase-card">
                    <!-- Book Cover Section -->
                    <div class="purchase-cover">
                        <div class="purchase-emoji">{{ $book['emoji'] }}</div>
                        <h2 class="purchase-name">{{ $book['name'] }}</h2>
                        <div class="purchase-category">{{ $book['category'] }}</div>
                    </div>

                    <!-- Details Section -->
                    <div class="purchase-body">
                        <div class="purchase-desc">
                            <p>
                                {{ $book['description'] }}
                            </p>
                        </div>

                        <!-- Price Section -->
                        <div class="purchase-price">
                            <div class="purchase-price__label">Price</div>
                            <div class="purchase-price__value">
                                ₹{{ number_format($book['price']) }}
                            </div>
                            <div class="purchase-price__note">Available Now</div>
                        </div>

                        <!-- Year Badge -->
                        <div class="purchase-year">
                            <span>Published {{ $book['year'] }}</span>
                        </div>

                        <!-- CTA Buttons -->
                        <div class="purchase-actions">
                            <a href="{{ route('products.index') }}" class="jb-btn jb-btn--ghost jb-btn--lg">
                                ← Back
                            </a>
                            <a href="{{ route('products.index') }}" class="jb-btn jb-btn--primary jb-btn--lg">
                                Proceed to Buy
                            </a>
                        </div>

                        <p class="purchase-note">Secure checkout • Free shipping on orders above ₹500</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if(!$requestedBook || !isset($books[$requestedBook]))
        <hr class="purchase-rule">
        <p class="purchase-more">
            Don't find what you're looking for? <a href="{{ route('products.index') }}" class="jb-link">View all publications</a>
        </p>
        @endif
    </div>
</section>

<style>
    .purchase-section { background: var(--jb-ground); padding: 32px 0 48px; }
    @media (min-width: 768px) { .purchase-section { padding: 48px 0 64px; } }

    .purchase-card { position: relative; height: 100%; overflow: hidden; border: 1px solid var(--jb-line); border-radius: var(--jb-r-card); background: var(--jb-panel); box-shadow: 0 4px 10px rgba(0, 0, 0, .8); transition: border-color .2s, box-shadow .2s; }
    .purchase-card::after { content: ""; position: absolute; left: 18%; right: 18%; bottom: 0; height: 1px; background: linear-gradient(90deg, transparent, var(--jb-gold-deep), transparent); opacity: .8; }
    @media (hover: hover) { .purchase-card:hover { border-color: var(--jb-line-strong); box-shadow: 0 16px 36px -12px rgba(0, 0, 0, .9); } }

    /* Cover */
    .purchase-cover { padding: 40px 24px 32px; text-align: center; border-bottom: 1px solid var(--jb-line-soft);
        background: radial-gradient(600px 240px at 50% 0%, rgba(201, 163, 92, .12), transparent 65%), radial-gradient(circle at 50% 40%, #1a1a1f 0%, #08080a 90%); }
    .purchase-emoji { margin-bottom: 16px; font-size: 3.4rem; line-height: 1; filter: drop-shadow(0 6px 16px rgba(0, 0, 0, .6)); }
    .purchase-name { margin: 0 0 12px; color: var(--jb-white); font: 600 clamp(22px, 3vw, 26px)/1.25 var(--jb-serif); }
    .purchase-category { display: inline-flex; align-items: center; min-height: 26px; padding: 0 12px; border: 1px solid rgba(201, 163, 92, .4); border-radius: 8px; background: rgba(201, 163, 92, .16); color: var(--jb-gold-light); font-size: 11px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; }

    /* Details */
    .purchase-body { padding: 24px; }
    @media (min-width: 768px) { .purchase-body { padding: 28px; } }
    .purchase-desc { margin-bottom: 20px; padding: 16px 18px; border: 1px solid var(--jb-line-soft); border-left: 2px solid var(--jb-gold); border-radius: var(--jb-r-ctl); background: rgba(201, 163, 92, .04); }
    .purchase-desc p { margin: 0; color: var(--jb-text); font-size: 14px; line-height: 1.75; }

    .purchase-price { margin-bottom: 20px; padding: 20px; border: 1px solid var(--jb-line); border-radius: var(--jb-r-ctl); text-align: center;
        background: linear-gradient(180deg, rgba(201, 163, 92, .08), transparent 70%), var(--jb-panel-2); }
    .purchase-price__label { margin-bottom: 8px; color: var(--jb-muted); font-size: 11px; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; }
    .purchase-price__value { margin: 0; color: var(--jb-gold-light); font: 600 2.4rem/1.1 var(--jb-serif); font-variant-numeric: tabular-nums; }
    .purchase-price__note { margin-top: 8px; color: var(--jb-muted); font-size: 12.5px; }

    .purchase-year { margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid var(--jb-line-soft); text-align: center; }
    .purchase-year span { display: inline-flex; align-items: center; min-height: 28px; padding: 0 14px; border: 1px solid rgba(201, 163, 92, .4); border-radius: 999px; background: rgba(201, 163, 92, .16); color: var(--jb-gold-light); font-size: 12.5px; font-weight: 700; }

    .purchase-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .purchase-actions .jb-btn { width: 100%; }
    @media (max-width: 359px) { .purchase-actions { grid-template-columns: 1fr; } }

    .purchase-note { margin: 14px 0 0; color: var(--jb-muted); font-size: 12.5px; text-align: center; }

    .purchase-rule { margin: 48px 0 0; border: 0; border-top: 1px solid var(--jb-line-soft); opacity: 1; }
    .purchase-more { margin: 28px 0 0; color: var(--jb-muted); font-size: 14px; text-align: center; }
</style>
@endsection
