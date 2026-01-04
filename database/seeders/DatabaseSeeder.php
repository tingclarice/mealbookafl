<?php

namespace Database\Seeders;

use App\Models\CartItem;
use App\Models\User;
use App\Models\UserRole;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ShopSeeder::class,
            UserRoleSeeder::class,
            MealSeeder::class,
            CartItemSeeder::class,
            AnalyticsTestSeeder::class
        ]);
    }
}
