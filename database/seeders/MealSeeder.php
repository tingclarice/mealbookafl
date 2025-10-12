<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Meal;

class MealSeeder extends Seeder
{
    public function run()
    {
        $meals = [
            // MEALS
            [
                'name' => 'Nasi Goreng',
                'description' => 'Indonesian fried rice with egg and vegetables',
                'price' => 20000,
                'category' => 'MEAL',
                'isAvailable' => true,
                'image_url' => 'images/nasi-goreng.png',
            ],
            [
                'name' => 'Mie Goreng',
                'description' => 'Fried noodles with vegetables and egg',
                'price' => 18000,
                'category' => 'MEAL',
                'isAvailable' => true,
                'image_url' => 'images/mie-goreng.png',
            ],
            [
                'name' => 'Ayam Bakar',
                'description' => 'Grilled chicken with special sauce',
                'price' => 25000,
                'category' => 'MEAL',
                'isAvailable' => true,
                'image_url' => 'images/ayam-bakar.png',
            ],
            [
                'name' => 'Sate Ayam',
                'description' => 'Chicken satay with peanut sauce',
                'price' => 22000,
                'category' => 'MEAL',
                'isAvailable' => true,
                'image_url' => 'images/sate-ayam.png',
            ],
            
            // SNACKS
            [
                'name' => 'Pisang Goreng',
                'description' => 'Fried banana fritters',
                'price' => 10000,
                'category' => 'SNACK',
                'isAvailable' => true,
                'image_url' => 'images/pisang-goreng.png',
            ],
            [
                'name' => 'Risoles',
                'description' => 'Indonesian pastry filled with vegetables',
                'price' => 8000,
                'category' => 'SNACK',
                'isAvailable' => true,
                'image_url' => 'images/risoles.png',
            ],
            
            // DRINKS
            [
                'name' => 'Es Teh Manis',
                'description' => 'Sweet iced tea',
                'price' => 5000,
                'category' => 'DRINKS',
                'isAvailable' => true,
                'image_url' => 'images/es-teh.png',
            ],
            [
                'name' => 'Es Jeruk',
                'description' => 'Fresh orange juice with ice',
                'price' => 8000,
                'category' => 'DRINKS',
                'isAvailable' => true,
                'image_url' => 'images/es-jeruk.png',
            ],
            [
                'name' => 'Kopi Hitam',
                'description' => 'Black coffee',
                'price' => 7000,
                'category' => 'DRINKS',
                'isAvailable' => true,
                'image_url' => 'images/kopi-hitam.png',
            ],
        ];

        foreach ($meals as $meal) {
            Meal::create($meal);
        }
    }
}