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
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->default(null)->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->default(null)->constrained()->onDelete('set null');
            $table->string('name', 255);
            $table->string('image', 255);
            $table->integer('quantity');
            $table->float('net_price', 10, 2);
            $table->float('price', 10, 2);
            $table->json('others')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_products');
    }
};
