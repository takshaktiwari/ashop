<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('display_name');
            $table->foreignId('category_id')->nullable()->constrained()->default(null);
            $table->string('image_sm', 255)->nullable()->default(null);
            $table->string('image_md', 255)->nullable()->default(null);
            $table->string('image_lg', 255)->nullable()->default(null);
            $table->text('description')->default(false)->nullable();
            $table->boolean('status')->default(true)->nullable();
            $table->boolean('featured')->default(false)->nullable();
            $table->boolean('is_top')->default(false)->nullable()->comment('to view at homepage');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
