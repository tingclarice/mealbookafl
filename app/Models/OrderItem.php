<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'meal_id',
        'meal_name',
        'quantity',
        'price',
        'rate',
        'review_msg'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'rate' => 'integer'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

    public function options()
    {
        return $this->hasMany(OrderItemOption::class);
    }
    
    // Calculate total for this item including options
    public function getTotalPriceAttribute()
    {
        $basePrice = $this->price * $this->quantity;
        $optionsPrice = $this->options->sum('price') * $this->quantity;
        return $basePrice + $optionsPrice;
    }
}
