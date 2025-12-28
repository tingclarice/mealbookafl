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

    // Helpers for cleaner business logic
    public function addBalance(float $amount): void
    {
        $this->increment('balance', $amount);
    }

    public function subtractBalance(float $amount): void
    {
        $this->decrement('balance', $amount);
    }

    public function addPending(float $amount): void
    {
        $this->increment('pending_balance', $amount);
    }

    public function clearPending(float $amount): void
    {
        $this->decrement('pending_balance', $amount);
        $this->increment('balance', $amount);
    }
}
