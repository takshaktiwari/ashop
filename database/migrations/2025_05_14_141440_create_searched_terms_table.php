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
        Schema::create('searched_terms', function (Blueprint $table) {
            $table->id();
            $table->string('term', 255);
            $table->integer('count')->default(1);
            $table->foreignId('user_id')->nullable()->default(null)->constrained()->onDelete('cascade');
            $table->string('user_ip');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('searched_terms');
    }
};
