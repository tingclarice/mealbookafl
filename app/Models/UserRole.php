<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;

    protected $table = 'user_roles';

    protected $fillable = [
        'shop_id',
        'user_id',
        'role',
    ];

    // Role belongs to a Shop
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    // Role belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
