<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            
            $table->foreignId('shop_id')
                ->constrained('shops')
                ->onDelete('cascade');
            
            $table->enum('order_status', [
                'PENDING',
                'PAID',
                'PREPARING',
                'READY',
                'COMPLETED',
                'CANCELLED'
            ])->default('PENDING');
            
            $table->decimal('total_amount', 10, 2);
            $table->datetime('scheduled_pickup')->nullable();
            
            // QR Code for order completion
            $table->string('qr_code')->unique()->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};