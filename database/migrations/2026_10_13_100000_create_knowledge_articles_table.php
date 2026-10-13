<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Knowledge articles shown on /knowledge, managed in admin.
     * Starts with the eight guides that used to be written into the page.
     */
    public function up(): void
    {
        Schema::create('knowledge_articles', function (Blueprint $table) {
            $table->id();
            $table->string('tag', 60);          // small label above the title, e.g. "Purity"
            $table->string('title');
            $table->text('intro')->nullable();
            $table->text('points')->nullable(); // one bullet point per line
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $articles = [
            [
                'Purity', 'Karats and fineness',
                'Gold purity is given in karats (K) out of 24, or as fineness in parts per thousand.',
                ['24K = 999: the purest gold sold, used for coins and bars.',
                 '22K = 916: the usual standard for Indian jewellery.',
                 '18K = 750 and 14K = 585: harder alloys that hold stones well.',
                 'The price follows the gold content, so 22K costs 22/24 of the 24K rate.'],
            ],
            [
                'Hallmark', 'Hallmarks and HUID',
                'Hallmarked gold jewellery in India carries a BIS mark that you can check yourself.',
                ['Look for the BIS logo, the purity grade (for example 22K916) and a six-character HUID code.',
                 'Every hallmarked piece has its own HUID.',
                 'Enter the HUID in the government\'s BIS CARE app to see the purity and the piece type on record.'],
            ],
            [
                'Pricing', 'How a jewellery price is built',
                'A quote for jewellery has three parts.',
                ['Metal value: weight × the day\'s rate for that purity.',
                 'Making charges: a fixed amount or a percentage, depending on the design and work.',
                 '3% GST on the metal value and making charges.',
                 'Stones and diamonds are priced separately on their own merits.'],
            ],
            [
                'Silver', 'Silver grades',
                'Silver is graded by fineness, like gold.',
                ['999 fine silver is used for bars and coins.',
                 '925 sterling is used for jewellery, utensils and articles.',
                 'Silver is quoted per kilogram; small pieces carry higher making charges per gram.'],
            ],
            [
                'Weights', 'Gram, sovereign, tola and ounce',
                'Bullion and jewellery use a few traditional weights alongside the gram.',
                ['1 sovereign (pavan) = 8 grams.',
                 '1 tola = 11.6638 grams.',
                 '1 troy ounce, the international bullion unit, = 31.1035 grams.',
                 '1 kilogram = 1,000 grams, the usual unit for silver bars.'],
            ],
            [
                'Rates', 'How we publish our rates',
                'We publish a reference rate twice on working days.',
                ['A morning rate and an evening rate, each with the time it went live.',
                 'One rate for 24K gold and one for 999 silver; every other purity is worked out from them.',
                 'The same reference rate applies to every city page on this site.'],
            ],
            [
                'Buying', 'Coins, bars or jewellery?',
                'Each form suits a different purpose.',
                ['Coins and bars are 24K or 999 and carry low making charges, so most of what you pay is metal.',
                 'Jewellery is usually 22K and carries making charges for the craft that goes into it.',
                 'Keep the invoice for every purchase; it records weight, purity and price.'],
            ],
            [
                'Safety', 'Before you pay',
                'A few checks protect every purchase.',
                ['Confirm the weight, purity and final price in writing before paying.',
                 'Pay only into the bank accounts listed on our Bank Details page.',
                 'Ask for a GST invoice and, for gold jewellery, check the HUID.'],
            ],
        ];

        $now = now();
        $rows = [];
        foreach ($articles as $i => [$tag, $title, $intro, $points]) {
            $rows[] = [
                'tag'        => $tag,
                'title'      => $title,
                'intro'      => $intro,
                'points'     => implode("\n", $points),
                'sort_order' => $i + 1,
                'is_active'  => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('knowledge_articles')->insert($rows);
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_articles');
    }
};
