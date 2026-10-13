<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cities with a gold rate page (/gold-rate/{state_slug}/{slug}), managed in admin.
     * Starts with the eight cities that used to be fixed in App\Support\RateBook.
     */
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('state', 100);
            $table->string('state_slug', 100);
            $table->string('slug', 100);
            $table->boolean('show_in_menu')->default(false); // linked from the header menu
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['state_slug', 'slug']);
        });

        // [name, state, state slug, city slug, in the header menu]
        $cities = [
            ['Mumbai',    'Maharashtra', 'maharashtra', 'mumbai',    true],
            ['Delhi',     'Delhi',       'delhi',       'delhi',     true],
            ['Chennai',   'Tamil Nadu',  'tamil-nadu',  'chennai',   true],
            ['Bengaluru', 'Karnataka',   'karnataka',   'bengaluru', false],
            ['Hyderabad', 'Telangana',   'telangana',   'hyderabad', false],
            ['Ahmedabad', 'Gujarat',     'gujarat',     'ahmedabad', false],
            ['Kolkata',   'West Bengal', 'west-bengal', 'kolkata',   false],
            ['Jaipur',    'Rajasthan',   'rajasthan',   'jaipur',    false],
        ];

        $now = now();
        $rows = [];
        foreach ($cities as $i => [$name, $state, $stateSlug, $slug, $inMenu]) {
            $rows[] = [
                'name'         => $name,
                'state'        => $state,
                'state_slug'   => $stateSlug,
                'slug'         => $slug,
                'show_in_menu' => $inMenu,
                'sort_order'   => $i + 1,
                'is_active'    => true,
                'created_at'   => $now,
                'updated_at'   => $now,
            ];
        }

        DB::table('cities')->insert($rows);
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
