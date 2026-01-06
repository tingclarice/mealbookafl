<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'meal_id',
        'meal_name',
        'quantity',
        'price',
        'rate',
        'review_msg',
        'notes'
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(OrderItemOption::class);
    }

    // HELPERS

    // Base price * quantity
    public function getSubtotalAttribute(): float
    {
        return (float) ($this->price * $this->quantity);
    }

    // Options total * quantity
    public function getOptionsTotalAttribute(): float
    {
        return (float) (
            $this->options->sum('price') * $this->quantity
        );
    }

    // Final total for this item
    public function getTotalAttribute(): float
    {
        return $this->subtotal + $this->options_total;
    }
}
