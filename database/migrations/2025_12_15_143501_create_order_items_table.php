<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            // Relation to orders
            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            // Relation to meals (nullable for historical safety)
            $table->foreignId('meal_id')
                ->nullable()
                ->constrained('meals')
                ->nullOnDelete();

            // Snapshot data (important)
            $table->string('meal_name', 100);
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->string('notes')->nullable();

            // Review
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
