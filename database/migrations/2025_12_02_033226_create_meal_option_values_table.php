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
        Schema::create('meal_option_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meal_option_group_id')->constrained()->onDelete('cascade');
            $table->string('name', 100); // For example: Small/Medium/Large
            $table->decimal('price', 10, 2)->default(0); // Additional price
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_option_values');
    }
};
