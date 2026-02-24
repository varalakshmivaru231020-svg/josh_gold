<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offices', function (Blueprint $table) {
            $table->string('image')->nullable()->after('icon');        // Office photo
            $table->string('color')->nullable()->after('image');       // Card accent color (e.g. #1a2e5e or #8b1a24)
        });
    }

    public function down(): void
    {
        Schema::table('offices', function (Blueprint $table) {
            $table->dropColumn(['image', 'color']);
        });
    }
};
