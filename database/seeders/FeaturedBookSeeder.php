<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeaturedBookSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('featured_book')->insert([
            [
                'title' => 'Featured Publication',
                'book_name' => 'Thanks Appa',
                'description' => 'A deeply emotional Kannada novel — a son\'s tribute to a father\'s wisdom, discipline, and unconditional love. Blending business insights with heartfelt storytelling, this work shares timeless life lessons wrapped in the warmth of Appa\'s voice.',
                'image' => 'images/publications-banner.jpg',
                'price' => 350.00,
                'button_text' => 'PURCHASE NOW',
                'button_link' => null,
                'product_id' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
