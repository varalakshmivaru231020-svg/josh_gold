<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Slider Banners
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('image');
            $table->string('button_text')->nullable();
            $table->string('button_link')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // About Section
        Schema::create('about_section', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->longText('content'); // CKEditor content
            $table->string('image')->nullable();
            $table->string('years_experience')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Featured Book Section
        Schema::create('featured_book', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('book_name');
            $table->longText('description');
            $table->string('image');
            $table->decimal('price', 10, 2)->nullable();
            $table->string('button_text')->default('Purchase Now');
            $table->string('button_link')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();


        });

        // Testimonial Videos
        Schema::create('testimonial_videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('youtube_url');
            $table->string('youtube_id')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('author_name')->nullable();
            $table->string('author_designation')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false); // For homepage slider
            $table->timestamps();
        });

        // Order Flow Steps
        Schema::create('order_flow_steps', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('icon')->default('fas fa-check'); // FontAwesome icon class
            $table->integer('step_number');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // WhatsApp Flow Steps
        Schema::create('whatsapp_flow_steps', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('icon')->default('fab fa-whatsapp');
            $table->integer('step_number');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Promotional Banners
        Schema::create('promo_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('image');
            $table->string('button_text')->nullable();
            $table->string('button_link')->nullable();
            $table->string('position')->default('bottom'); // bottom, middle
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Site Settings (for general configurations)
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->string('type')->default('text'); // text, textarea, image, json
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('promo_banners');
        Schema::dropIfExists('whatsapp_flow_steps');
        Schema::dropIfExists('order_flow_steps');
        Schema::dropIfExists('testimonial_videos');
        Schema::dropIfExists('featured_book');
        Schema::dropIfExists('about_section');
        Schema::dropIfExists('sliders');
    }
};
