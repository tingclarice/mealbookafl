<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Meal;

class MealSeeder extends Seeder
{
    public function run()
    {
        $meals = [
            // MAIN MENU
            [
                'name' => 'Nasi Goreng Ayam',
                'description' => 'Indonesian fried rice with chicken and vegetables',
                'price' => 15000,
                'category' => 'MEAL',
                'isAvailable' => true,
                'image_url' => 'images/meals/nasgor.webp',
                'shop_id' => 1,
            ],
            [
                'name' => 'Nasi Goreng Lap Jiong / Babi',
                'description' => 'Fried rice with Chinese sausage or pork, Indonesian-style',
                'price' => 17000,
                'category' => 'MEAL',
                'isAvailable' => true,
                'image_url' => 'images/meals/nasgor_lapjiong.webp',
                'shop_id' => 1,
            ],
            [
                'name' => 'Chicken Katsu',
                'description' => 'Breaded fried chicken cutlet served with rice',
                'price' => 15000,
                'category' => 'MEAL',
                'isAvailable' => true,
                'image_url' => 'images/meals/katsu.webp',
                'shop_id' => 1,
            ],
            [
                'name' => 'Ayam Krispy',
                'description' => 'Crispy fried chicken served with rice',
                'price' => 15000,
                'category' => 'MEAL',
                'isAvailable' => true,
                'image_url' => 'images/meals/ayam_geprek.png',
                'shop_id' => 2,
            ],
            [
                'name' => 'Soto Ayam',
                'description' => 'Traditional Indonesian chicken soup with vermicelli and boiled egg',
                'price' => 15000,
                'category' => 'MEAL',
                'isAvailable' => true,
                'image_url' => 'images/meals/soto_ayam.webp',
                'shop_id' => 2,
            ],
            [
                'name' => 'Ayam Teriyaki',
                'description' => 'Japanese-style teriyaki chicken served with rice',
                'price' => 15000,
                'category' => 'MEAL',
                'isAvailable' => true,
                'image_url' => 'images/meals/chicken_teriyaki.webp',
                'shop_id' => 2,
            ],
            [
                'name' => 'Nasi Mie',
                'description' => 'Rice served with stir-fried noodles',
                'price' => 12000,
                'category' => 'MEAL',
                'isAvailable' => true,
                'image_url' => 'images/meals/indomie_nasi.jpeg',
                'shop_id' => 2,
            ],
            [
                'name' => 'Mie Goreng',
                'description' => 'Fried noodles with vegetables and egg',
                'price' => 10000,
                'category' => 'MEAL',
                'isAvailable' => true,
                'image_url' => 'images/meals/mie_goreng.jpg',
                'shop_id' => 2,
            ],
            [
                'name' => 'Nasi Ayam Sisit Telur',
                'description' => 'Rice with shredded chicken and fried egg',
                'price' => 10000,
                'category' => 'MEAL',
                'isAvailable' => true,
                'image_url' => 'images/meals/nasi_ayam_sisit.webp',
                'shop_id' => 2,
            ],
            

            // SNACK
            [
                'name' => 'Roti Manis',
                'description' => 'Soft sweet bread',
                'price' => 5000,
                'category' => 'SNACK',
                'isAvailable' => true,
                'image_url' => 'images/meals/roti-manis.jpg',
                'shop_id' => 1,
            ],
            [
                'name' => 'Cakue / Roti Goreng (3 pcs)',
                'description' => 'Fried dough snack, 3 pieces per portion',
                'price' => 5000,
                'category' => 'SNACK',
                'isAvailable' => true,
                'image_url' => 'images/meals/cakue.jpg',
                'shop_id' => 1,
            ],
            [
                'name' => 'Otak-otak Ikan',
                'description' => 'Grilled fish cake wrapped in banana leaf',
                'price' => 5000,
                'category' => 'SNACK',
                'isAvailable' => true,
                'image_url' => 'images/meals/otak-otak-ikan.jpeg',
                'shop_id' => 1,
            ],
            [
                'name' => 'Roti Bakar',
                'description' => 'Toasted bread with various fillings',
                'price' => 5000,
                'category' => 'SNACK',
                'isAvailable' => true,
                'image_url' => 'images/meals/roti-bakar.jpg',
                'shop_id' => 1,
            ],

            // DESSERT
            [
                'name' => 'Semangka / Melon',
                'description' => 'Fresh sliced watermelon or melon',
                'price' => 5000,
                'category' => 'SNACK',
                'isAvailable' => true,
                'image_url' => 'images/meals/semangka-melon.jpg',
                'shop_id' => 2,
            ],
            [
                'name' => 'Puding',
                'description' => 'Sweet and creamy pudding dessert',
                'price' => 5000,
                'category' => 'SNACK',
                'isAvailable' => true,
                'image_url' => 'images/meals/puding.jpg',
                'shop_id' => 2,
            ],

            // DRINKS
            [
                'name' => 'Es Teh',
                'description' => 'Iced sweet tea',
                'price' => 3000,
                'category' => 'DRINK',
                'isAvailable' => true,
                'image_url' => 'images/meals/es-teh.jpg',
                'shop_id' => 2,
            ],
            [
                'name' => 'Es Teh Jumbo',
                'description' => 'Large glass of iced sweet tea',
                'price' => 5000,
                'category' => 'DRINK',
                'isAvailable' => true,
                'image_url' => 'images/meals/es-teh-jumbo.jpg',
                'shop_id' => 2,
            ],
            [
                'name' => 'Es Milo',
                'description' => 'Iced chocolate malt drink',
                'price' => 6000,
                'category' => 'DRINK',
                'isAvailable' => true,
                'image_url' => 'images/meals/es-milo.webp',
                'shop_id' => 2,
            ],
            [
                'name' => 'Lohankuo',
                'description' => 'Traditional herbal drink made from luo han guo fruit',
                'price' => 5000,
                'category' => 'DRINK',
                'isAvailable' => true,
                'image_url' => 'images/meals/lohankuo.webp',
                'shop_id' => 2,
            ],
            [
                'name' => 'Cappuccino Coffee GoodDay',
                'description' => 'Sweet iced cappuccino coffee drink',
                'price' => 6000,
                'category' => 'DRINK',
                'isAvailable' => true,
                'image_url' => 'images/meals/good-day-cappucino.jpg',
                'shop_id' => 2,
            ],
        ];

        foreach ($meals as $meal) {
            Meal::create($meal);
        }
    }
}