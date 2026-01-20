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
        if (!Schema::hasTable('leads')) {
            Schema::create('leads', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('mobile')->nullable();
                $table->string('email')->nullable();
                $table->unsignedBigInteger('assigned_to')->nullable()->index();
                $table->timestamps();
                $table->softDeletes();
            });
        } else {
            Schema::table('leads', function (Blueprint $table) {
                if (!Schema::hasColumn('leads', 'assigned_to')) {
                    $table->unsignedBigInteger('assigned_to')->nullable()->index();
                }
                if (!Schema::hasColumn('leads', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
