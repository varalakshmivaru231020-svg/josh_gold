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
        Schema::table('about_section', function (Blueprint $table) {
            $table->string('hero_image')->nullable()->after('image');
            $table->string('id_card_image')->nullable()->after('hero_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('about_section', function (Blueprint $table) {
            $table->dropColumn(['hero_image', 'id_card_image']);
        });
    }
};
