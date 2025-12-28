<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'meal_id',
        'image_path',
        'order',
        'is_primary'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'order' => 'integer'
    ];

    /**
     * Get the meal that owns this image
     */
    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

    /**
     * Get the full URL for the image
     */
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }
}
