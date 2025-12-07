<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealOptionGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'meal_id',
        'name',
        'is_multiple',
        'is_required'
    ];

    protected $casts = [
        'is_multiple' => 'boolean',
        'is_required' => 'boolean'
    ];

    // Relationship to meal
    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

    // Relationship to option values
    public function values() {
        return $this->hasMany(MealOptionValue::class);
    }
}
