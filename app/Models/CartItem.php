<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    // Allow mass assignment for these columns
    protected $fillable = [
        'user_id',
        'meal_id',
        'quantity',
        'notes',
    ];

    // Relationships

    // Each cart item belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Each cart item belongs to a meal
    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

    // Relationship to selected options
    public function selectedOptions()
    {
        return $this->hasMany(CartItemOption::class);
    }

    // Helper method to get total price including options
    public function getTotalPriceAttribute()
    {
        $basePrice = $this->meal->price * $this->quantity;
        
        // Add option prices
        $optionsPrice = $this->selectedOptions()
            ->with('optionValue')
            ->get()
            ->sum(function ($option) {
                return $option->optionValue->price * $this->quantity;
            });
        
        return $basePrice + $optionsPrice;
    }

    public function getFormattedTotalPriceAttribute()
{
    return 'Rp. ' . number_format($this->total_price, 0, ',', '.');
}
}
