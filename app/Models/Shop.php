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

    // Users connected through roles (including owner and staff)
    public function users(){
        return $this->belongsToMany(User::class, 'user_roles')
            ->withPivot('role')
            ->withTimestamps();
    }
    // get owner
    public function owner(){
        return $this->users()->wherePivot('role', 'OWNER')->first();
    }

    // Check if shop is approved
    public function isApproved(){
        return $this->status == 'OPEN' || $this->status == 'CLOSED';
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

    /*
    * Add Staff to Shop
    */
    public function addStaff($email)
    {
        // 1. Check if user is registered
        $user = User::where('email', $email)->first();
        if (!$user) {
            throw new \Exception('Email is not registered in our system.');
        }

        // 2. Check if user already has roles on that shop
        $exists = $this->userRoles()->where('user_id', $user->id)->exists();
        if ($exists) {
            throw new \Exception('User is already registered in this shop.');
        }

        // 3. Add Staff Role
        $this->userRoles()->create([
            'user_id' => $user->id,
            'role' => 'STAFF'
        ]);

        return true;
    }
}
