<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopWallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'balance',
        'pending_balance',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'pending_balance' => 'decimal:2',
    ];

    // Wallet belongs to a Shop
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'wallet_id');
    }


    // Static helper to Credit Balance (Add)
    // usually for: Topup, Order Payout, Refund from platform
    public function creditBalance($amount, $message)
    {
        $this->increment('balance', $amount);

        $this->transactions()->create([
            'type' => 'credit',
            'amount' => $amount,
            'description' => $message,
        ]);
        
        return $this;
    }

    // Static helper to Debit Balance (Subtract)
    // usually for: Withdrawal, Penalty, Monthly Fee
    public function debitBalance($amount, $message)
    {
        $this->decrement('balance', $amount);

        $this->transactions()->create([
            'type' => 'debit',
            'amount' => $amount,
            'description' => $message,
        ]);

        return $this;
    }
}
