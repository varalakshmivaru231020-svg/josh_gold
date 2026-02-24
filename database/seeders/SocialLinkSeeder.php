<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $links = [
            [
                'platform' => 'WhatsApp',
                'url' => 'https://wa.me/918217215195',
                'icon' => 'fab fa-whatsapp',
                'sort_order' => 1,
            ],
            [
                'platform' => 'Facebook',
                'url' => 'https://facebook.com/blrlawhouse',
                'icon' => 'fab fa-facebook-f',
                'sort_order' => 2,
            ],
            [
                'platform' => 'Instagram',
                'url' => 'https://instagram.com/blrlawhouse',
                'icon' => 'fab fa-instagram',
                'sort_order' => 3,
            ],
            [
                'platform' => 'Website',
                'url' => 'https://blrlawhouse.com',
                'icon' => 'fas fa-globe',
                'sort_order' => 4,
            ],
            [
                'platform' => 'App',
                'url' => 'https://play.google.com/store/apps/details?id=com.blrlawhouse',
                'icon' => 'fab fa-google-play',
                'sort_order' => 5,
            ],
        ];

        foreach ($links as $link) {
            \App\Models\SocialLink::create($link);
        }
    }
}
