@extends('layouts.lawfirm')

@section('title', 'Purchase Books | Bengaluru Law House')

@section('content')
<section class="purchase-section">
    <div class="container" style="padding-top: 80px; padding-bottom: 100px;">
        <h1 class="text-center mb-5" style="font-size: 2.5rem; font-weight: 700; color: #0c1428;">Purchase Books</h1>
        
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

        <div class="row">
            @foreach($displayBooks as $bookId => $book)
            <div class="col-lg-6 col-md-8 mx-auto mb-5" data-aos="fade-up">
                <div class="purchase-card" style="
                    background: white;
                    border-radius: 20px;
                    overflow: hidden;
                    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
                    border: 1px solid rgba(201,168,76,0.2);
                    transition: all 0.4s ease;
                ">
                    <!-- Book Cover Section -->
                    <div style="
                        background: linear-gradient(160deg, #0c1428, #1e3060);
                        padding: 60px 30px;
                        text-align: center;
                        color: white;
                    ">
                        <div style="font-size: 4rem; margin-bottom: 20px;">{{ $book['emoji'] }}</div>
                        <h2 style="font-weight: 700; font-size: 1.8rem; margin: 0; margin-bottom: 10px;">{{ $book['name'] }}</h2>
                        <div style="
                            background: rgba(201,168,76,0.2);
                            color: #c9a84c;
                            padding: 8px 16px;
                            border-radius: 50px;
                            display: inline-block;
                            font-size: 0.75rem;
                            font-weight: 700;
                            letter-spacing: 1.5px;
                            text-transform: uppercase;
                        ">{{ $book['category'] }}</div>
                    </div>

                    <!-- Details Section -->
                    <div style="padding: 40px; background: white;">
                        <div style="
                            background: rgba(201,168,76,0.05);
                            padding: 20px;
                            border-radius: 12px;
                            margin-bottom: 30px;
                            border-left: 4px solid #c9a84c;
                        ">
                            <p style="margin: 0; font-size: 0.9rem; color: #555; line-height: 1.8;">
                                {{ $book['description'] }}
                            </p>
                        </div>

                        <!-- Price Section -->
                        <div style="
                            background: linear-gradient(135deg, rgba(201,168,76,0.1), rgba(201,168,76,0.05));
                            padding: 25px;
                            border-radius: 12px;
                            margin-bottom: 30px;
                            text-align: center;
                        ">
                            <div style="font-size: 0.85rem; color: #999; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1px;">Price</div>
                            <div style="font-size: 2.5rem; font-weight: 700; color: #c9a84c; margin: 0;">
                                ₹{{ number_format($book['price']) }}
                            </div>
                            <div style="font-size: 0.8rem; color: #999; margin-top: 8px;">Available Now</div>
                        </div>

                        <!-- Year Badge -->
                        <div style="
                            text-align: center;
                            margin-bottom: 30px;
                            padding-bottom: 20px;
                            border-bottom: 1px solid rgba(201,168,76,0.1);
                        ">
                            <span style="
                                background: #c9a84c;
                                color: #0c1428;
                                padding: 8px 20px;
                                border-radius: 50px;
                                font-weight: 700;
                                font-size: 0.8rem;
                                display: inline-block;
                            ">Published {{ $book['year'] }}</span>
                        </div>

                        <!-- CTA Buttons -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <a href="{{ route('products.index') }}" style="
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                padding: 14px 24px;
                                border: 2px solid #c9a84c;
                                color: #c9a84c;
                                text-decoration: none;
                                border-radius: 50px;
                                font-weight: 700;
                                font-size: 0.85rem;
                                transition: all 0.3s ease;
                                text-transform: uppercase;
                                letter-spacing: 1px;
                            " onmouseover="this.style.background='rgba(201,168,76,0.1)'" onmouseout="this.style.background='transparent'">
                                ← Back
                            </a>
                            <a href="{{ route('products.index') }}" style="
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                padding: 14px 24px;
                                background: linear-gradient(135deg, #c9a84c, #b39441);
                                color: #0c1428;
                                text-decoration: none;
                                border-radius: 50px;
                                font-weight: 700;
                                font-size: 0.85rem;
                                transition: all 0.3s ease;
                                text-transform: uppercase;
                                letter-spacing: 1px;
                                box-shadow: 0 8px 20px rgba(201,168,76,0.25);
                            " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 12px 30px rgba(201,168,76,0.35)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 20px rgba(201,168,76,0.25)'">
                                Proceed to Buy
                            </a>
                        </div>

                        <p style="
                            text-align: center;
                            font-size: 0.8rem;
                            color: #999;
                            margin-top: 15px;
                            margin-bottom: 0;
                        ">Secure checkout • Free shipping on orders above ₹500</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if(!$requestedBook || !isset($books[$requestedBook]))
        <hr style="margin-top: 60px;">
        <p style="text-align: center; color: #999; font-size: 0.9rem; margin-top: 40px;">
            Don't find what you're looking for? <a href="{{ route('products.index') }}" style="color: #c9a84c; text-decoration: none; font-weight: 700;">View all publications</a>
        </p>
        @endif
    </div>
</section>

<style>
    .purchase-section {
        background: linear-gradient(135deg, #f9f5ed 0%, #fff 100%);
        min-height: 80vh;
    }
</style>
@endsection
