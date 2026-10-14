<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

// The Gold rate menu shows 12 cities in a 4 × 3 grid: add four more cities and put the first 12 active ones in the menu.
return new class extends Migration
{
    private const NEW = [
        ['Pune',       'Maharashtra',   'maharashtra',   'pune'],
        ['Kochi',      'Kerala',        'kerala',        'kochi'],
        ['Lucknow',    'Uttar Pradesh', 'uttar-pradesh', 'lucknow'],
        ['Coimbatore', 'Tamil Nadu',    'tamil-nadu',    'coimbatore'],
    ];

    public function up(): void
    {
        $order = (int) DB::table('cities')->max('sort_order');
        foreach (self::NEW as [$name, $state, $stateSlug, $slug]) {
            if (DB::table('cities')->where('state_slug', $stateSlug)->where('slug', $slug)->exists()) {
                continue;
            }
            DB::table('cities')->insert([
                'name' => $name, 'state' => $state, 'state_slug' => $stateSlug, 'slug' => $slug,
                'show_in_menu' => true, 'is_active' => true, 'sort_order' => ++$order,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        $first12 = DB::table('cities')->where('is_active', true)->orderBy('sort_order')->orderBy('id')->limit(12)->pluck('id');
        DB::table('cities')->whereIn('id', $first12)->update(['show_in_menu' => true]);

        Cache::forget('cities.active');
    }

    public function down(): void
    {
        foreach (self::NEW as [, , $stateSlug, $slug]) {
            DB::table('cities')->where('state_slug', $stateSlug)->where('slug', $slug)->delete();
        }
        Cache::forget('cities.active');
    }
};
