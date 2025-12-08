<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItemOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_item_id',
        'meal_option_value_id',
    ];

    // Relationship to cart item
    public function cartItem()
    {
        return $this->belongsTo(CartItem::class);
    }

    // Relationship to option value
    public function optionValue()
    {
        return $this->belongsTo(MealOptionValue::class, 'meal_option_value_id');
    }
}
