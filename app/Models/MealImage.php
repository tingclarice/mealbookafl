<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'meal_id',
        'image_url',
        'order',
        'is_primary'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'order' => 'integer'
    ];

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }
}