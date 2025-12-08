<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'google_id', // for Google OAuth
        'avatar', // for Google OAuth
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function shops(){
        return $this->belongsToMany(Shop::class, 'user_roles')
            ->withPivot('role')
            ->withTimestamps();
    }
    public function meals(){
        return Meal::whereIn('shop_id', $this->shops()->pluck('shops.id'))
                ->orderByDesc('created_at');
    }

    // Get Roles
    public function userRoles(){
        return $this->hasMany(UserRole::class);
    }

    public function isAdmin(){
        return $this->role === 'ADMIN';
    }

    public function isOwner(){
        return $this->userRoles()
            ->where('role', 'OWNER')
            ->exists();
    }

    public function isStaff(){
        return $this->userRoles()
            ->where('role', 'STAFF')
            ->exists();
    }

    public function isOwnerOrStaff(){
        return $this->isOwner() || $this->isStaff();
    }
}
