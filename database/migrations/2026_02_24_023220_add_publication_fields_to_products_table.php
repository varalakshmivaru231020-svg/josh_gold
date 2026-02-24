<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('year')->nullable()->after('name');          // e.g. "2008", "2026", "Legal", "12 Volumes"
            $table->string('category')->nullable()->after('year');      // e.g. "Kannada Literature", "Legal Publication"
            $table->string('book_icon')->nullable()->after('category'); // emoji or icon class, e.g. "🌾", "💛", "⚖️", "📚"
            $table->string('cover_image')->nullable()->after('book_icon'); // single cover image path
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['year', 'category', 'book_icon', 'cover_image']);
        });
    }
};
