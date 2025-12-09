<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Meal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'isAvailable',
        'image_url',
        'shop_id'
    ];

    protected $casts = [
        'isAvailable' => 'boolean',
        'price' => 'decimal:2'
    ];

    // Relationship to option groups
    public function optionGroups()
    {
        return $this->hasMany(MealOptionGroup::class);
    }

    // Relationship to shop
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    // Accessor untuk format harga
    public function getFormattedPriceAttribute()
    {
        return 'Rp. ' . number_format($this->price, 0, ',', '.');
    }

    // Accessor untuk deskripsi pendek
    public function getShortDescriptionAttribute()
    {
        return Str::limit($this->description, 60);
    }
}