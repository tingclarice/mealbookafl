<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_item_options', function (Blueprint $table) {
            $table->id();

            // Relation to order_items
            $table->foreignId('order_item_id')
                ->constrained('order_items')
                ->cascadeOnDelete();

            // Option data
            $table->string('option_name', 100);
            $table->decimal('price', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_item_options');
    }
};
