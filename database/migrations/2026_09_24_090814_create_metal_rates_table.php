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
        Schema::create('metal_rates', function (Blueprint $table) {
            $table->id();
            $table->date('rate_date');
            $table->enum('session', ['morning', 'evening']);
            // Gold 999 purity — admin enters per 10 gm
            $table->decimal('gold_999_rate', 12, 2);
            // Silver 999 purity — admin enters per 1 kg
            $table->decimal('silver_999_rate', 12, 2);
            $table->text('remarks')->nullable();
            $table->timestamps();

            // Only one morning & one evening entry per date
            $table->unique(['rate_date', 'session']);
            $table->index('rate_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metal_rates');
    }
};
