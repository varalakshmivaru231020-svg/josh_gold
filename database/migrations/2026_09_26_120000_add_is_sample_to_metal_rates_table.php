<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('metal_rates', function (Blueprint $table) {
            // Rows created by `php artisan rates:sample` (demo history), never real published rates
            $table->boolean('is_sample')->default(false)->after('remarks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('metal_rates', function (Blueprint $table) {
            $table->dropColumn('is_sample');
        });
    }
};
