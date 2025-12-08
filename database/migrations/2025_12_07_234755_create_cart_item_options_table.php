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
        Schema::create('cart_item_options', function (Blueprint $table) {
            $table->id();
            // Foreign key to cart_items
            $table->foreignId('cart_item_id')
                  ->constrained('cart_items')
                  ->onDelete('cascade');
            
            // Foreign key to meal_option_values
            $table->foreignId('meal_option_value_id')
                  ->constrained('meal_option_values')
                  ->onDelete('cascade');
            
            $table->timestamps();
            
            // Prevent duplicate selections for the same cart item
            $table->unique(['cart_item_id', 'meal_option_value_id'], 'cart_item_option_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_item_options');
    }
};
