<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** The News page became Updates: sliders placed on page "news" now belong to page "updates" (App\Models\Slider::PAGES). */
    public function up(): void
    {
        if (Schema::hasTable('sliders') && Schema::hasColumn('sliders', 'page')) {
            DB::table('sliders')->where('page', 'news')->update(['page' => 'updates']);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('sliders') && Schema::hasColumn('sliders', 'page')) {
            DB::table('sliders')->where('page', 'updates')->update(['page' => 'news']);
        }
    }
};
