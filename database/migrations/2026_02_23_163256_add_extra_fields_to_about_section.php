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
            $table->string('designation')->nullable();
            $table->string('name_kn')->nullable();
            $table->string('qualifications')->nullable();
            $table->text('tagline')->nullable();
            $table->string('stat_courts')->nullable();
            $table->string('stat_books')->nullable();
            $table->string('stat_offices')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('about_section', function (Blueprint $table) {
            $table->dropColumn(['designation', 'name_kn', 'qualifications', 'tagline', 'stat_courts', 'stat_books', 'stat_offices']);
        });
    }
};
