<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offices = [
            [
                'title' => 'Bengaluru Office',
                'subtitle' => 'Karnataka • High Courts • NGT',
                'address' => "1652/6, 20th Cross, 8th Main,\nCQAL Layout, C Block, Sahakaranagar,\nBengaluru, Karnataka – 560092",
                'phone' => '+91 80 4150 2373',
                'mobile' => '+91 96325 27961',
                'email' => 'veerendrababu.advocate@gmail.com',
                'website' => null,
                'icon' => '🏙️',
                'sort_order' => 1,
            ],
            [
                'title' => 'New Delhi Office',
                'subtitle' => 'Supreme Court of India • NGT',
                'address' => "Lawyer's Chambers,\nSupreme Court of India,\nNew Delhi – 110001",
                'phone' => null,
                'mobile' => '+91 96325 27961',
                'email' => 'veerendrababu.advocate@gmail.com',
                'website' => 'www.blrlawhouse.com',
                'icon' => '🏛️',
                'sort_order' => 2,
            ],
        ];

        foreach ($offices as $office) {
            \App\Models\Office::create($office);
        }
    }
}
