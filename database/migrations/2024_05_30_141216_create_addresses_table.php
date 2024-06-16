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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->default(null)->constrained()->onDelete('set null');
            $table->string('name', 200);
            $table->string('mobile', 50);
            $table->string('address_line_1', 255);
            $table->string('address_line_2', 255);
            $table->string('landmark', 255)->nullable()->default(null);
            $table->string('city', 200);
            $table->string('pincode', 10);
            $table->string('state', 200);
            $table->string('country', 200)->default('India');
            $table->boolean('default_addr')->default(false);
            $table->boolean('billing_addr')->default(false);
            $table->string('user_ip');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
