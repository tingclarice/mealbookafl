<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'user_id',

        'order_status',
        'payment_status',

        'midtrans_order_id',
        'midtrans_transaction_id',
        'payment_method',
        'snap_token',

        'total_amount',
        'payment_time',

        'raw_midtrans_response',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'total_amount' => 'decimal:2',
        'payment_time' => 'datetime',
        'raw_midtrans_response' => 'array',
    ];

    /**
     * Relationships
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * ---- Business helpers ----
     */

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }


    public function markAsPaid(array $midtransPayload = []): void
    {
        $rawType = $midtransPayload['payment_type'] ?? 'unknown';
        $method = $rawType; 

        // 1. Handle Virtual Accounts (e.g. BCA, BNI, BRI)
        if ($rawType === 'bank_transfer') {
            if (isset($midtransPayload['va_numbers'][0]['bank'])) {
                $bank = strtoupper($midtransPayload['va_numbers'][0]['bank']);
                $method = "$bank Virtual Account"; // Result: "BCA Virtual Account"
            } elseif (isset($midtransPayload['permata_va_number'])) {
                $method = "Permata Virtual Account";
            }
        }
        // 2. Handle Mandiri Bill Payment (Midtrans specific type 'echannel')
        elseif ($rawType === 'echannel') {
            $method = 'Mandiri Bill Payment';
        }
        // 3. Handle Convenience Stores (Indomaret/Alfamart)
        elseif ($rawType === 'cstore' && isset($midtransPayload['store'])) {
            $method = ucfirst($midtransPayload['store']); // Result: "Indomaret"
        }
        // 4. Handle E-Wallets & Others (Manual fix for proper capitalization)
        else {
            $method = match ($rawType) {
                'gopay' => 'GoPay',
                'shopeepay' => 'ShopeePay',
                'qris' => 'QRIS',
                'credit_card' => 'Credit Card',
                'akulaku' => 'Akulaku',
                default => ucwords(str_replace('_', ' ', $rawType)), // Fallback: "some_method" -> "Some Method"
            };
        }

        $this->update([
            'payment_status' => 'PAID',
            'payment_time' => $midtransPayload['settlement_time'] ?? $midtransPayload['transaction_time'] ?? now(),
            'raw_midtrans_response' => $midtransPayload,
            'payment_method' => $method, // Now saves "BCA Virtual Account" or "GoPay"
        ]);
    }

    public function markAsPending(array $midtransPayload = []): void
    {
        $this->update([
            'payment_status' => 'PENDING',
            'raw_midtrans_response' => $midtransPayload,
        ]);
    }

    public function markAsFailed(array $midtransPayload = []): void
    {
        $this->update([
            'payment_status' => 'FAILED',
            'raw_midtrans_response' => $midtransPayload,
        ]);
    }

    public function markAsExpired(array $midtransPayload = []): void
    {
        $this->update([
            'payment_status' => 'EXPIRED',
            'raw_midtrans_response' => $midtransPayload,
        ]);
    }

    public function markAsCancelled(array $midtransPayload = []): void
    {
        $this->update([
            'payment_status' => 'CANCELLED',
            'raw_midtrans_response' => $midtransPayload,
        ]);
    }

    /**
     * Query scopes
     */
    // public function scopePaid($query)
    // {
    //     return $query->where('payment_status', 'PAID');
    // }

    // public function scopePending($query)
    // {
    //     return $query->where('payment_status', 'PENDING');
    // }
}
