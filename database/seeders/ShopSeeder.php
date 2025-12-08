<?php

namespace Database\Seeders;

use App\Models\Meal;
use App\Models\Shop;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Shop::factory()->create([
            'name' => 'MealBook Cafetaria',
            'address' => 'Jl. Ciputra Raya No. 1, Surabaya',
            'phone' => '081-12345678',
            'profileImage' => 'shops/mealbook-cafetaria.jpg',
        ]);

        Shop::factory()->create([
            'name' => 'Kantin 88',
            'address' => 'Jl. Mahendradata No. 1, Denpasar',
            'phone' => '021-1132456',
            'profileImage' => 'shops/mealbook-cafetaria.jpg',
        ]);

        // Shop::factory($shopCount)->create()->each(function ($shop) use ($staffPerShop, $mealsPerShop) {

        //     // Create STAFF
        //     User::factory($staffPerShop)->create()->each(function ($staff) use ($shop) {
        //         UserRole::factory()->staff()->create([
        //             'shop_id' => $shop->id,
        //             'user_id' => $staff->id,
        //         ]);
        //     });

        //     // Create Meals for this shop
        //     Meal::factory($mealsPerShop)->create([
        //         'shop_id' => $shop->id,
        //     ]);

        // });
    }
}
