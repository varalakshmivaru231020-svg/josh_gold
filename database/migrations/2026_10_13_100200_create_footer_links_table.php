<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Links in the website footer's "Rates", "Company" and "Notices & letters" columns, managed in admin.
     * Starts with the links that used to be written into the footer. URLs are stored as site paths
     * (/gold-rate-today) so they work on any domain; full https:// URLs are allowed too.
     */
    public function up(): void
    {
        Schema::create('footer_links', function (Blueprint $table) {
            $table->id();
            $table->string('column_key', 20); // rates | company | notices
            $table->string('label');
            $table->string('url', 500);
            $table->boolean('open_in_new_tab')->default(false);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['column_key', 'sort_order']);
        });

        $columns = [
            'rates' => [
                ['Gold rate today',   '/gold-rate-today'],
                ['Silver rate today', '/silver-rate-today'],
                ['Rates by city',     '/gold-rate'],
                ['Charts',            '/charts'],
                ['Gold Pulse',        '/gold-pulse'],
                ['Full rate table',   '/live-rates'],
                ['Knowledge',         '/knowledge'],
            ],
            'company' => [
                ['About us',       '/about-us'],
                ['Services',       '/services'],
                ['Our offices',    '/offices'],
                ['Gallery',        '/gallery'],
                ['News & updates', '/news'],
                ['Bank details',   '/bank-details'],
            ],
            'notices' => [
                ['Central Anti-Conversion Law',                '/anti-conversion-law'],
                ['SC/ST (Prevention of Atrocities) Act, 1989', '/sc-st-act-1989'],
                ['Letter to PM Modi',                          '/letter-to-pm-modi'],
                ['Letter to CM Karnataka',                     '/letter-to-cm-karnataka'],
                ['Water Filtration Project',                   '/water-filtration'],
                ['Water Filtration (Kannada)',                 '/water-filtration-kannada'],
            ],
        ];

        $now = now();
        $rows = [];
        foreach ($columns as $column => $links) {
            foreach ($links as $i => [$label, $url]) {
                $rows[] = [
                    'column_key'      => $column,
                    'label'           => $label,
                    'url'             => $url,
                    'open_in_new_tab' => false,
                    'sort_order'      => $i + 1,
                    'is_active'       => true,
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ];
            }
        }

        DB::table('footer_links')->insert($rows);
    }

    public function down(): void
    {
        Schema::dropIfExists('footer_links');
    }
};
