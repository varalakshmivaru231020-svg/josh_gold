<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('referral_code')->nullable();
            $table->string('customer_name');
            $table->string('customer_mobile');
            $table->string('customer_email')->nullable();
            $table->text('shipping_address');
            $table->string('country');
            $table->string('state');
            $table->string('city');
            $table->string('pincode', 10);
            $table->string('payment_method')->default('razorpay');
            $table->string('payment_id')->nullable();
            $table->string('payment_status')->default('pending');
            $table->string('order_status')->default('pending');
            $table->string('tracking_id')->nullable();
            $table->string('tracking_url')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};