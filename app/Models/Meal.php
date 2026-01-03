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

    // Relation to Shop
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    // Relationship to meal images
    public function images()
    {
        return $this->hasMany(MealImage::class)->orderBy('order');
    }

    // Get primary image
    public function primaryImage()
    {
        return $this->hasOne(MealImage::class)->where('is_primary', true);
    }

    // Helper to get primary image URL (fallback to old image_url)
    public function getPrimaryImageUrlAttribute()
    {
        // First check if there's a primary image in the images relationship
        if ($this->relationLoaded('primaryImage') && $this->primaryImage) {
            return $this->primaryImage->image_path;
        }

        // Check if there are any images at all
        $primaryImage = $this->images()->where('is_primary', true)->first();
        if ($primaryImage) {
            return $primaryImage->image_path;
        }

        // Fallback to old single image
        return $this->image_url;
    }

    /**
     * Check if meal has a legacy image (stored in image_url but not in meal_images)
     */
    public function hasLegacyImage()
    {
        return !empty($this->image_url) && $this->images()->count() === 0;
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