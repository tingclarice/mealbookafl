<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'profileImage',
        'status',
        'description'
    ];

    // One Shop has one Wallet
    public function wallet(){
        return $this->hasOne(ShopWallet::class);
    }

    // One Shop has many Meals
    public function meals(){
        return $this->hasMany(Meal::class);
    }

    // One Shop has many User Roles
    public function userRoles(){
        return $this->hasMany(UserRole::class);
    }

    // Users connected through roles
    public function users(){
        return $this->belongsToMany(User::class, 'user_roles')
            ->withPivot('role')
            ->withTimestamps();
    }

    // Auto create wallet when a shop is created
    protected static function booted(){
        static::created(function ($shop) {
            $shop->wallet()->create([
                'balance' => 0,
                'pending_balance' => 0,
            ]);
        });
    }
}
