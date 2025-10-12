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
}
