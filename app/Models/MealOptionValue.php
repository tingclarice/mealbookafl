<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealOptionValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'meal_option_group_id',
        'name',
        'price'
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    // Relationship to option group
    public function group() {
        return $this->belongsTo(MealOptionGroup::class, 'meal_option_group_id');
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp. ' . number_format($this->price, 0, ',', '.');
    }
}
