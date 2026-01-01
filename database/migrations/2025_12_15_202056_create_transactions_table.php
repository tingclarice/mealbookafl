<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('wallet_id')
                ->constrained('shop_wallets')
                ->cascadeOnDelete();

            $table->foreignId('order_id')
                ->nullable()
                ->constrained('orders')
                ->nullOnDelete();

            // Transaction info
            $table->enum('type', ['credit', 'debit']);

            $table->decimal('amount', 12, 2);
            $table->text('description')->nullable();

            $table->timestamps();

            // Indexes (important for reports)
            $table->index(['wallet_id', 'created_at']);
            $table->index(['order_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
