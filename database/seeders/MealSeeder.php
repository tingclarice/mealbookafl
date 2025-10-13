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
                'image_url' => 'images/menu_images/nasgor.webp',
            ],
            [
                'name' => 'Nasi Goreng Lap Jiong / Babi',
                'description' => 'Fried rice with Chinese sausage or pork, Indonesian-style',
                'price' => 17000,
                'category' => 'MEAL',
                'isAvailable' => true,
                'image_url' => 'images/menu_images/nasgor_lapjiong.webp',
            ],
            [
                'name' => 'Chicken Katsu',
                'description' => 'Breaded fried chicken cutlet served with rice',
                'price' => 15000,
                'category' => 'MEAL',
                'isAvailable' => true,
                'image_url' => 'images/menu_images/katsu.webp',
            ],
            [
                'name' => 'Ayam Krispy',
                'description' => 'Crispy fried chicken served with rice',
                'price' => 15000,
                'category' => 'MEAL',
                'isAvailable' => true,
                'image_url' => 'images/menu_images/ayam_geprek.png',
            ],
            [
                'name' => 'Soto Ayam',
                'description' => 'Traditional Indonesian chicken soup with vermicelli and boiled egg',
                'price' => 15000,
                'category' => 'MEAL',
                'isAvailable' => true,
                'image_url' => 'images/menu_images/soto_ayam.webp',
            ],
            [
                'name' => 'Ayam Teriyaki',
                'description' => 'Japanese-style teriyaki chicken served with rice',
                'price' => 15000,
                'category' => 'MEAL',
                'isAvailable' => true,
                'image_url' => 'images/menu_images/chicken_teriyaki.webp',
            ],
            [
                'name' => 'Nasi Mie',
                'description' => 'Rice served with stir-fried noodles',
                'price' => 12000,
                'category' => 'MEAL',
                'isAvailable' => true,
                'image_url' => 'images/menu_images/indomie_nasi.jpeg',
            ],
            [
                'name' => 'Mie Goreng',
                'description' => 'Fried noodles with vegetables and egg',
                'price' => 10000,
                'category' => 'MEAL',
                'isAvailable' => true,
                'image_url' => 'images/menu_images/mie_goreng.jpg',
            ],
            [
                'name' => 'Nasi Ayam Sisit Telur',
                'description' => 'Rice with shredded chicken and fried egg',
                'price' => 10000,
                'category' => 'MEAL',
                'isAvailable' => true,
                'image_url' => 'images/menu_images/nasi_ayam_sisit.webp',
            ],
            

            // SNACK
            [
                'name' => 'Roti Manis',
                'description' => 'Soft sweet bread',
                'price' => 5000,
                'category' => 'SNACK',
                'isAvailable' => true,
                'image_url' => 'images/menu_images/roti-manis.jpg',
            ],
            [
                'name' => 'Cakue / Roti Goreng (3 pcs)',
                'description' => 'Fried dough snack, 3 pieces per portion',
                'price' => 5000,
                'category' => 'SNACK',
                'isAvailable' => true,
                'image_url' => 'images/menu_images/cakue.jpg',
            ],
            [
                'name' => 'Otak-otak Ikan',
                'description' => 'Grilled fish cake wrapped in banana leaf',
                'price' => 5000,
                'category' => 'SNACK',
                'isAvailable' => true,
                'image_url' => 'images/menu_images/otak-otak-ikan.jpeg',
            ],
            [
                'name' => 'Roti Bakar',
                'description' => 'Toasted bread with various fillings',
                'price' => 5000,
                'category' => 'SNACK',
                'isAvailable' => true,
                'image_url' => 'images/menu_images/roti-bakar.jpg',
            ],

            // DESSERT
            [
                'name' => 'Semangka / Melon',
                'description' => 'Fresh sliced watermelon or melon',
                'price' => 5000,
                'category' => 'SNACK',
                'isAvailable' => true,
                'image_url' => 'images/menu_images/semangka-melon.jpg',
            ],
            [
                'name' => 'Puding',
                'description' => 'Sweet and creamy pudding dessert',
                'price' => 5000,
                'category' => 'SNACK',
                'isAvailable' => true,
                'image_url' => 'images/menu_images/puding.jpg',
            ],

            // DRINKS
            [
                'name' => 'Es Teh',
                'description' => 'Iced sweet tea',
                'price' => 3000,
                'category' => 'DRINK',
                'isAvailable' => true,
                'image_url' => 'images/menu_images/es-teh.jpg',
            ],
            [
                'name' => 'Es Teh Jumbo',
                'description' => 'Large glass of iced sweet tea',
                'price' => 5000,
                'category' => 'DRINK',
                'isAvailable' => true,
                'image_url' => 'images/menu_images/es-teh-jumbo.jpg',
            ],
            [
                'name' => 'Es Milo',
                'description' => 'Iced chocolate malt drink',
                'price' => 6000,
                'category' => 'DRINK',
                'isAvailable' => true,
                'image_url' => 'images/menu_images/es-milo.webp',
            ],
            [
                'name' => 'Lohankuo',
                'description' => 'Traditional herbal drink made from luo han guo fruit',
                'price' => 5000,
                'category' => 'DRINK',
                'isAvailable' => true,
                'image_url' => 'images/menu_images/lohankuo.webp',
            ],
            [
                'name' => 'Cappuccino Coffee GoodDay',
                'description' => 'Sweet iced cappuccino coffee drink',
                'price' => 6000,
                'category' => 'DRINK',
                'isAvailable' => true,
                'image_url' => 'images/menu_images/good-day-cappucino.jpg',
            ],
        ];

        foreach ($meals as $meal) {
            Meal::create($meal);
        }
    }
}