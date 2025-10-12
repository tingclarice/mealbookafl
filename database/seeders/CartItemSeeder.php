<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CartItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {

        $now = Carbon::now();

        DB::table('cart_items')->insert([
            [
                'user_id' => 1,
                'meal_id' => 1,
                'quantity' => 2,
                'notes' => 'Tolong yang satu pedas, yang satu tidak pedas.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => 1,
                'meal_id' => 5,
                'quantity' => 1,
                'notes' => 'Banyakin bawang gorengnya ya.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => 1,
                'meal_id' => 7,
                'quantity' => 3,
                'notes' => null, 
                'created_at' => $now,
                'updated_at' => $now,
            ],

            [
                'user_id' => 2,
                'meal_id' => 2, 
                'quantity' => 1,
                'notes' => 'Baksonya minta 5.', 
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}