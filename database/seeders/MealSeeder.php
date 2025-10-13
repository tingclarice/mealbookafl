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
                'image_url' => 'images/menu_images/nasgor.png',
            ],
            [
                'name' => 'Nasi Goreng Lap Jiong / Babi',
                'description' => 'Fried rice with Chinese sausage or pork, Indonesian-style',
                'price' => 17000,
                'category' => 'MEAL',
                'isAvailable' => true,
                'image_url' => 'images/menu_images/nasgor_lapjiong.png',
            ],
            [
                'name' => 'Chicken Katsu',
                'description' => 'Breaded fried chicken cutlet served with rice',
                'price' => 15000,
                'category' => 'MEAL',
                'isAvailable' => true,
                'image_url' => 'images/menu_images/katsu.png',
            ],
            [
                'name' => 'Ayam Krispy',
                'description' => 'Crispy fried chicken served with rice',
                'price' => 15000,
                'category' => 'MEAL',
                'isAvailable' => true,
                'image_url' => 'images/ayam-krispy.png',
            ],
            [
                'name' => 'Soto Ayam',
                'description' => 'Traditional Indonesian chicken soup with vermicelli and boiled egg',
                'price' => 15000,
                'category' => 'MEAL',
                'isAvailable' => true,
                'image_url' => 'images/soto-ayam.png',
            ],
            [
                'name' => 'Ayam Teriyaki',
                'description' => 'Japanese-style teriyaki chicken served with rice',
                'price' => 15000,
                'category' => 'MEAL',
                'isAvailable' => true,
                'image_url' => 'images/ayam-teriyaki.png',
            ],
            [
                'name' => 'Nasi Mie',
                'description' => 'Rice served with stir-fried noodles',
                'price' => 12000,
                'category' => 'MEAL',
                'isAvailable' => true,
                'image_url' => 'images/nasi-mie.png',
            ],
            [
                'name' => 'Mie Goreng',
                'description' => 'Fried noodles with vegetables and egg',
                'price' => 10000,
                'category' => 'MEAL',
                'isAvailable' => true,
                'image_url' => 'images/mie-goreng.png',
            ],
            [
                'name' => 'Nasi Ayam Sisit Telur',
                'description' => 'Rice with shredded chicken and fried egg',
                'price' => 10000,
                'category' => 'MEAL',
                'isAvailable' => true,
                'image_url' => 'images/nasi-ayam-sisit.png',
            ],
            

            // SNACK
            [
                'name' => 'Roti Manis',
                'description' => 'Soft sweet bread',
                'price' => 5000,
                'category' => 'SNACK',
                'isAvailable' => true,
                'image_url' => 'images/roti-manis.png',
            ],
            [
                'name' => 'Cakue / Roti Goreng (3 pcs)',
                'description' => 'Fried dough snack, 3 pieces per portion',
                'price' => 5000,
                'category' => 'SNACK',
                'isAvailable' => true,
                'image_url' => 'images/cakue.png',
            ],
            [
                'name' => 'Otak-otak Ikan',
                'description' => 'Grilled fish cake wrapped in banana leaf',
                'price' => 5000,
                'category' => 'SNACK',
                'isAvailable' => true,
                'image_url' => 'images/otak-otak.png',
            ],
            [
                'name' => 'Roti Bakar',
                'description' => 'Toasted bread with various fillings',
                'price' => 5000,
                'category' => 'SNACK',
                'isAvailable' => true,
                'image_url' => 'images/roti-bakar.png',
            ],

            // DESSERT
            [
                'name' => 'Semangka / Melon',
                'description' => 'Fresh sliced watermelon or melon',
                'price' => 5000,
                'category' => 'DESSERT',
                'isAvailable' => true,
                'image_url' => 'images/semangka.png',
            ],
            [
                'name' => 'Puding',
                'description' => 'Sweet and creamy pudding dessert',
                'price' => 5000,
                'category' => 'DESSERT',
                'isAvailable' => true,
                'image_url' => 'images/puding.png',
            ],

            // DRINKS
            [
                'name' => 'Es Teh',
                'description' => 'Iced sweet tea',
                'price' => 3000,
                'category' => 'DRINK',
                'isAvailable' => true,
                'image_url' => 'images/es-teh.png',
            ],
            [
                'name' => 'Es Teh Jumbo',
                'description' => 'Large glass of iced sweet tea',
                'price' => 5000,
                'category' => 'DRINK',
                'isAvailable' => true,
                'image_url' => 'images/es-teh-jumbo.png',
            ],
            [
                'name' => 'Es Milo',
                'description' => 'Iced chocolate malt drink',
                'price' => 6000,
                'category' => 'DRINK',
                'isAvailable' => true,
                'image_url' => 'images/es-milo.png',
            ],
            [
                'name' => 'Lohankuo',
                'description' => 'Traditional herbal drink made from luo han guo fruit',
                'price' => 5000,
                'category' => 'DRINK',
                'isAvailable' => true,
                'image_url' => 'images/lohankuo.png',
            ],
            [
                'name' => 'Cappuccino Coffee GoodDay',
                'description' => 'Sweet iced cappuccino coffee drink',
                'price' => 6000,
                'category' => 'DRINK',
                'isAvailable' => true,
                'image_url' => 'images/cappuccino-goodday.png',
            ],
        ];

        foreach ($meals as $meal) {
            Meal::create($meal);
        }
    }
}