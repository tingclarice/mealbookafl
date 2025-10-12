<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    protected $fillable = ["name", "description", "price", "category", "isAvailable", "image_url"];
    /** @use HasFactory<\Database\Factories\MealFactory> */
    use HasFactory;
}
