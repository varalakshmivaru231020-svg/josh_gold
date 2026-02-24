<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'title' => 'Alternative Dispute Resolution',
                'description' => 'Specialist in ADR for civil disputes — mediation, arbitration, conciliation & negotiation.',
                'icon' => '⚖️',
                'sort_order' => 1,
            ],
            [
                'title' => 'Banking & Finance Law',
                'description' => 'Expert counsel in complex banking issues, financial litigation & regulatory matters.',
                'icon' => '🏦',
                'sort_order' => 2,
            ],
            [
                'title' => 'Criminal Law',
                'description' => 'Strategic and compassionate representation across criminal proceedings at all levels.',
                'icon' => '🔒',
                'sort_order' => 3,
            ],
            [
                'title' => 'Environmental Law',
                'description' => 'Practising before the National Green Tribunal on environmental and ecological disputes.',
                'icon' => '🌿',
                'sort_order' => 4,
            ],
            [
                'title' => 'Supreme Court Practice',
                'description' => 'Representing clients in matters of national importance before India\'s apex court.',
                'icon' => '🏛️',
                'sort_order' => 5,
            ],
            [
                'title' => 'Civil Disputes',
                'description' => 'Comprehensive civil litigation covering property, contracts, family & commercial matters.',
                'icon' => '📝',
                'sort_order' => 6,
            ],
        ];

        foreach ($services as $service) {
            \App\Models\Service::create($service);
        }
    }
}
