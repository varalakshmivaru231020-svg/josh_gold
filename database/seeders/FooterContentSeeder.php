<?php

namespace Database\Seeders;

use App\Models\Office;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

/**
 * Moves the company details that used to be hardcoded in the site footer into the admin-managed
 * tables (Offices, Contact Settings). Idempotent: it only fills what is empty, so anything
 * already edited in admin is never overwritten.
 *
 *   php artisan db:seed --class=FooterContentSeeder
 */
class FooterContentSeeder extends Seeder
{
    public function run(): void
    {
        if (Office::count() === 0) {
            $offices = [
                ['title' => 'Registered Office',         'address' => "# 7 Geminis Building, Cambridge,\nHalasuru Bangalore 560008"],
                ['title' => 'Branch Office (Kochi)',     'address' => "#38/354B, Arakkakkadavu Road,\nChittupabukara, Edappally,\nKochi, Kerala - 682 024"],
                ['title' => 'Branch Office (Bangalore)', 'address' => "No.194 1st Floor, AMR Complex,\nHoramavu Agara, Main Road, Bangalore 560043"],
            ];

            foreach ($offices as $i => $office) {
                Office::create($office + ['is_active' => true, 'sort_order' => $i + 1]);
            }
        }

        $settings = [
            'contact_phone'         => '+91 8714634801',
            'contact_email'         => 'Josha.infinity777@gmail.com',
            'contact_whatsapp'      => '919632527961',
            'contact_address'       => "No.194 1st Floor, AMR Complex,\nHoramavu Agara, Main Road,\nBangalore 560043",
            'contact_working_hours' => 'Mon – Sat: 9:00 AM – 6:00 PM',
        ];

        foreach ($settings as $key => $value) {
            if (blank(SiteSetting::get($key))) {
                SiteSetting::set($key, $value);
            }
        }
    }
}
