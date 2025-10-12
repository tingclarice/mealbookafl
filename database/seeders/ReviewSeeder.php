<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // To ensure consistent timestamps
        $now = Carbon::now();

        DB::table('reviews')->insert([
            [
                'user_id' => 1,
                'meal_id' => 1, // Correct for Nasi Goreng
                'message' => 'Nasi gorengnya juara! Bumbunya pas, porsinya banyak, dan telurnya setengah matang sempurna. Pasti pesan lagi!',
                'created_at' => $now->subDays(2),
                'updated_at' => $now->subDays(2),
            ],
            [
                'user_id' => 2,
                'meal_id' => 1, // Another review for Nasi Goreng
                'message' => 'Enak banget, tapi kemarin saya minta pedas, datangnya kurang pedas. Tapi overall rasanya tetap mantap.',
                'created_at' => $now->subDays(1),
                'updated_at' => $now->subDays(1),
            ],
            [
                'user_id' => 1,
                'meal_id' => 4, // CORRECTED: Sate Ayam is ID 4, not 5
                'message' => 'Bumbu satenya medok dan dagingnya empuk. Lontongnya juga pas. Recommended!',
                'created_at' => $now->subHours(5),
                'updated_at' => $now->subHours(5),
            ],
            [
                'user_id' => 3,
                'meal_id' => 2, // Correct for Mie Goreng
                'message' => 'Mie ayamnya biasa aja sih, kuahnya kurang gurih. Baksonya oke.',
                'created_at' => $now->subDays(3),
                'updated_at' => $now->subDays(3),
            ],
            [
                'user_id' => 4,
                'meal_id' => 7, // CORRECTED: Es Teh Manis is ID 7, not 10
                'message' => 'Es tehnya segar, manisnya pas, tidak berlebihan.',
                'created_at' => $now->subMinutes(30),
                'updated_at' => $now->subMinutes(30),
            ],
        ]);
    }
}