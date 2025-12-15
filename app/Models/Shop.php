<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'address',
        'phone',
        'profileImage',
        'status',
        'description'
    ];

    /**
     * Model events
     */
    protected static function booted()
    {
        // Generate slug before saving
        static::creating(function ($shop) {
            if (empty($shop->slug)) {
                $shop->slug = Str::slug($shop->name);

                // Ensure slug is unique
                $originalSlug = $shop->slug;
                $count = 1;

                while (static::where('slug', $shop->slug)->exists()) {
                    $shop->slug = $originalSlug . '-' . $count;
                    $count++;
                }
            }
        });

        // Auto create wallet after shop is created
        static::created(function ($shop) {
            $shop->wallet()->create([
                'balance' => 0,
                'pending_balance' => 0,
            ]);
        });
    }

    /**
     * Use slug for route model binding
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // One Shop has one Wallet
    public function wallet()
    {
        return $this->hasOne(ShopWallet::class);
    }

    // One Shop has many Meals
    public function meals()
    {
        return $this->hasMany(Meal::class);
    }

    // One Shop has many User Roles
    public function userRoles()
    {
        return $this->hasMany(UserRole::class);
    }

    // Users connected through roles
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles')
            ->withPivot('role')
            ->withTimestamps();
    }

    // One Shop has many Orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
