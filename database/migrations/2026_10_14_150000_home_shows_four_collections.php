<?php

use App\Models\Collection;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

// "Shop by category" on the home page shows four tiles: coins, bars, biscuits, jewellery.
// The other collections keep their pages but leave the home grid (Admin → Collections → Home).
return new class extends Migration
{
    private const HOME = ['gold-coins', 'gold-bars', 'gold-biscuits', 'gold-jewellery'];

    public function up(): void
    {
        DB::table('collections')->update(['show_on_home' => false]);
        foreach (self::HOME as $i => $slug) {
            DB::table('collections')->where('slug', $slug)->update(['show_on_home' => true, 'sort_order' => $i + 1]);
        }
        $next = count(self::HOME);
        foreach (DB::table('collections')->whereNotIn('slug', self::HOME)->orderBy('sort_order')->pluck('slug') as $slug) {
            DB::table('collections')->where('slug', $slug)->update(['sort_order' => ++$next]);
        }
        Collection::flushCache();
    }

    public function down(): void
    {
        $order = ['gold-jewellery', 'diamond-jewellery', 'gold-coins', 'gold-bars', 'gold-biscuits', 'silver-coins', 'silver-bars'];
        foreach ($order as $i => $slug) {
            DB::table('collections')->where('slug', $slug)->update(['show_on_home' => true, 'sort_order' => $i + 1]);
        }
        Collection::flushCache();
    }
};
