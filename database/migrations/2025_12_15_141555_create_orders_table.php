<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
        
            // Relations
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('shop_id')
                ->constrained()
                ->cascadeOnDelete();

            // Order lifecycle
            $table->enum('order_status', [
                'PENDING',
                'CONFIRMED',
                'READY',
                'CANCELLED',
                'COMPLETED'
            ])->default('PENDING');
            $table->dateTime('pickup_date')->nullable();

            // Payment lifecycle (Midtrans)
            $table->enum('payment_status', [
                'PENDING',
                'PAID',
                'FAILED',
                'EXPIRED',
                'CANCELLED'
            ])->default('PENDING');

            // Midtrans fields
            $table->string('midtrans_order_id', 50)->unique();
            $table->string('midtrans_transaction_id', 50)->nullable();
            $table->string('payment_method', 30)->nullable();
            $table->string('snap_token', 255)->nullable();

            // Amount & time
            $table->decimal('total_amount', 10, 2);
            $table->timestamp('payment_time')->nullable();


            $table->json('raw_midtrans_response')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
