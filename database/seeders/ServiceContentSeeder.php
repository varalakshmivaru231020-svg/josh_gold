<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

/**
 * The Home page "Services Preview" used to be hardcoded. It now shows the services managed in
 * Admin → Services, so this keeps its three cards as editable services (add a photo to each).
 * Idempotent: only runs when there are no services yet.
 *
 *   php artisan db:seed --class=ServiceContentSeeder
 */
class ServiceContentSeeder extends Seeder
{
    public function run(): void
    {
        if (Service::count() > 0) {
            return;
        }

        $services = [
            ['title' => 'Precious Metal Refining', 'description' => 'State-of-the-art machines and treatment for pure output.'],
            ['title' => 'Wholesale Trade',         'description' => 'Providing mammoth supply capabilities with conscientious workmanship.'],
            ['title' => 'Specialized Audits',      'description' => 'Professional work place audits ensuring top-tier health & safety.'],
        ];

        foreach ($services as $i => $service) {
            Service::create($service + ['is_active' => true, 'sort_order' => $i + 1]);
        }
    }
}
