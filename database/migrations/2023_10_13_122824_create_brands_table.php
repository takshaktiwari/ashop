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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('image_sm', 255)->nullable()->default(null);
            $table->string('image_md', 255)->nullable()->default(null);
            $table->string('image_lg', 255)->nullable()->default(null);
            $table->string('name');
            $table->string('slug');
            $table->boolean('status')->nullable()->default(true);
            $table->boolean('featured')->nullable()->default(false);
            $table->integer('user_id')->comment('added by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
