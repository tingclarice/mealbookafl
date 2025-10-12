<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'isAvailable',
        'image_url'
    ];

    protected $casts = [
        'isAvailable' => 'boolean',
        'price' => 'decimal:2'
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}