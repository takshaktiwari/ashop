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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('discount_type')->nullable()->default('percent')->comment('percent, amount');
            $table->integer('percent')->nullable()->default(null);
            $table->integer('amount')->nullable()->default(null);
            $table->integer('min_purchase')->nullable()->default(null);
            $table->integer('max_discount')->nullable()->default(null);
            $table->string('expires_at')->nullable()->default(null);
            $table->integer('max_usable')->nullable()->default(false);
            $table->boolean('status')->nullable()->default(false);
            $table->boolean('featured')->nullable()->default(false);
            $table->string('title', 255)->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
