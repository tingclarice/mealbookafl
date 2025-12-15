<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('order_id')
                ->constrained('orders')
                ->onDelete('cascade');
            
            $table->foreignId('meal_id')
                ->nullable()
                ->constrained('meals')
                ->onDelete('set null');
            
            $table->string('meal_name', 100); // Store name in case meal is deleted
            $table->integer('quantity');
            $table->decimal('price', 10, 2); // Price at time of order
            
            // For reviews
            $table->integer('rate')->nullable();
            $table->text('review_msg')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};