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
        Schema::create('meal_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meal_id')
                ->constrained('meals')
                ->onDelete('cascade');
            $table->string('image_url');
            $table->integer('order')->default(0); // For sorting images
            $table->boolean('is_primary')->default(false); // Primary image for listings
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_images');
    }
};
