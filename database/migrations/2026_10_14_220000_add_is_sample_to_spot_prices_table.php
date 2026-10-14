<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Sample rows (php artisan spot:sample) fill the charts until real history builds up; they are flagged so the
// charts can say "Sample data" and so they can be removed (automatically after 24 h of real prices).
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spot_prices', function (Blueprint $table) {
            $table->boolean('is_sample')->default(false)->index()->after('usd_inr');
        });
    }

    public function down(): void
    {
        Schema::table('spot_prices', function (Blueprint $table) {
            $table->dropColumn('is_sample');
        });
    }
};
