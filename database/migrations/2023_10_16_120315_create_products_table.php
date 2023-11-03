<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()
                ->default(null)
                ->constrained()
                ->onDelete('cascade');
            $table->string('name', 255);
            $table->string('slug')->unique();
            $table->string('subtitle', 255)->nullable();
            $table->string('image_sm', 255);
            $table->string('image_md', 255);
            $table->string('image_lg', 255);
            $table->float('net_price', 10, 2);
            $table->float('sell_price', 10, 2);
            $table->float('deal_price', 10, 2)->nullable()->default(null);
            $table->dateTime('deal_expiry')->nullable()->default(null);
            $table->integer('stock')->default('0');
            $table->foreignId('brand_id')->nullable()->constrained()->onDelete('no action');
            $table->string('sku')->nullable()->default(null);
            $table->boolean('featured')->default(false)->nullable();
            $table->boolean('status')->default(true)->nullable();
            $table->text('info')->nullable()->comment('short description');
            $table->text('search_tags')->nullable()->comment('search tags');
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
