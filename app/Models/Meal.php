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

    // Relationship to images
    public function images()
    {
        return $this->hasMany(MealImage::class)->orderBy('order');
    }
    
    // Get primary image or first image
    public function primaryImage()
    {
        return $this->hasOne(MealImage::class)
                    ->where('is_primary', true)
                    ->orWhere(function($q) {
                        $q->orderBy('order')->limit(1);
                    });
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

    // Get display image (prioritize meal_images, fallback to image_url)
    public function getDisplayImageAttribute()
    {
        $primaryImage = $this->images()->where('is_primary', true)->first();
        if ($primaryImage) {
            return $primaryImage->image_url;
        }
        
        $firstImage = $this->images()->first();
        if ($firstImage) {
            return $firstImage->image_url;
        }
        
        return $this->image_url;
    }
}