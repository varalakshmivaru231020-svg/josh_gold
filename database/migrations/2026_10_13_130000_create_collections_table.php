<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Collections: one public page per product category (/collections/{slug}), managed in Admin → Collections.
     * Starts with the seven categories the home page links to.
     */
    public function up(): void
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('slug', 120)->unique();
            $table->string('type', 20);                 // bullion | jewellery
            $table->string('metal', 20);                // gold | silver
            $table->string('form', 20)->nullable();     // bullion: coin | bar | biscuit; jewellery: null
            $table->string('tagline', 160)->nullable(); // short line on the category cards
            $table->text('intro')->nullable();
            $table->longText('body')->nullable();
            $table->string('image')->nullable();        // images/… (public asset) or collections/… (upload on the public disk)
            $table->json('gallery')->nullable();        // image paths, same rules as image
            $table->string('weights')->nullable();      // bullion: comma-separated grams, e.g. "1,2,5,116.64 (10 tola)"
            $table->string('purities')->nullable();     // comma-separated, e.g. "24K,22K" or "999,925"
            $table->boolean('show_on_home')->default(true);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $collections = [
            [
                'name'     => 'Gold jewellery',
                'slug'     => 'gold-jewellery',
                'type'     => 'jewellery',
                'metal'    => 'gold',
                'form'     => null,
                'tagline'  => 'Chains, bangles, necklaces',
                'intro'    => 'Chains, bangles, necklaces, rings and earrings in 22K and 18K gold. Tell us the piece you have in mind and ask our desk for a quote at the day’s published rate.',
                'body'     => "A jewellery price is built from the gold rate we publish each morning and evening, plus the making charge for the design and GST. Ask our desk for a full quote and check each part before you pay.\n\nShare a photo, a design reference or the weight you have in mind, and our desk will get back to you with a quote.",
                'image'    => 'images/site/gold-bracelet-wide.webp',
                'gallery'  => ['images/site/gold-necklace-wide.webp', 'images/site/gold-rings-wide.webp', 'images/site/gold-bracelet-wide.webp'],
                'weights'  => null,
                'purities' => '22K,18K',
            ],
            [
                'name'     => 'Diamond jewellery',
                'slug'     => 'diamond-jewellery',
                'type'     => 'jewellery',
                'metal'    => 'gold',
                'form'     => null,
                'tagline'  => 'Rings, necklaces, earrings',
                'intro'    => 'Diamond rings, necklaces and earrings set in gold. Ask our desk about designs, stone details and a full price for the piece you like.',
                'body'     => "A diamond piece is priced in parts: the gold in the setting at the day’s rate, the diamonds by their weight and quality, the making charge and GST. Ask our desk for a full quote and check each part before you pay.\n\nSend us a photo or describe what you are looking for, and our desk will get back to you with a quote.",
                'image'    => 'images/site/diamond-necklace-wide.webp',
                'gallery'  => ['images/site/diamond-necklace.webp', 'images/site/diamond-ring.webp'],
                'weights'  => null,
                'purities' => '18K,22K',
            ],
            [
                'name'     => 'Gold coins',
                'slug'     => 'gold-coins',
                'type'     => 'bullion',
                'metal'    => 'gold',
                'form'     => 'coin',
                'tagline'  => '1 g to 100 g, priced live',
                'intro'    => '24K and 22K gold coins from 1 gram to 100 grams, priced at our latest published gold rate.',
                'body'     => null,
                'image'    => 'images/site/gold-coins-wide.webp',
                'gallery'  => null,
                'weights'  => '1,2,5,8,10,20,50,100',
                'purities' => '24K,22K',
            ],
            [
                'name'     => 'Gold bars',
                'slug'     => 'gold-bars',
                'type'     => 'bullion',
                'metal'    => 'gold',
                'form'     => 'bar',
                'tagline'  => '5 g to 1 kg, priced live',
                'intro'    => '24K gold bars from 5 grams to 1 kilogram, priced at our latest published gold rate.',
                'body'     => null,
                'image'    => 'images/site/gold-bars-2-wide.webp',
                'gallery'  => null,
                'weights'  => '5,10,20,50,100,250,500,1000',
                'purities' => '24K',
            ],
            [
                'name'     => 'Gold biscuits',
                'slug'     => 'gold-biscuits',
                'type'     => 'bullion',
                'metal'    => 'gold',
                'form'     => 'biscuit',
                'tagline'  => '50 g to 1 kg, incl. 10 tola',
                'intro'    => '24K gold biscuits from 50 grams to 1 kilogram, including the 10 tola size, priced at our latest published gold rate.',
                'body'     => null,
                'image'    => 'images/site/gold-biscuits-wide.webp',
                'gallery'  => null,
                'weights'  => '50,100,116.64 (10 tola),200,500,1000',
                'purities' => '24K',
            ],
            [
                'name'     => 'Silver coins',
                'slug'     => 'silver-coins',
                'type'     => 'bullion',
                'metal'    => 'silver',
                'form'     => 'coin',
                'tagline'  => '5 g to 250 g, priced live',
                'intro'    => '999 fine and 925 sterling silver coins from 5 grams to 250 grams, priced at our latest published silver rate.',
                'body'     => null,
                'image'    => 'images/site/silver-coins-wide.webp',
                'gallery'  => null,
                'weights'  => '5,10,20,50,100,250',
                'purities' => '999,925',
            ],
            [
                'name'     => 'Silver bars',
                'slug'     => 'silver-bars',
                'type'     => 'bullion',
                'metal'    => 'silver',
                'form'     => 'bar',
                'tagline'  => '100 g to 5 kg, priced live',
                'intro'    => '999 fine silver bars from 100 grams to 5 kilograms, priced at our latest published silver rate.',
                'body'     => null,
                'image'    => 'images/site/silver-bar-card.webp',
                'gallery'  => null,
                'weights'  => '100,250,500,1000,5000',
                'purities' => '999',
            ],
        ];

        $now = now();
        $rows = [];
        foreach ($collections as $i => $row) {
            $rows[] = array_merge($row, [
                'gallery'      => $row['gallery'] ? json_encode($row['gallery']) : null,
                'show_on_home' => true,
                'sort_order'   => $i + 1,
                'is_active'    => true,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }

        DB::table('collections')->insert($rows);
    }

    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
