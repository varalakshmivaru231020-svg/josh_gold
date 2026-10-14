<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Live international spot prices, saved about once a minute; the charts build their candles from these rows.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spot_prices', function (Blueprint $table) {
            $table->id();
            $table->decimal('xau_usd', 12, 4);   // gold, USD per troy ounce
            $table->decimal('xag_usd', 12, 5);   // silver, USD per troy ounce
            $table->decimal('usd_inr', 10, 4);   // rupees per US dollar
            $table->dateTime('recorded_at')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spot_prices');
    }
};
