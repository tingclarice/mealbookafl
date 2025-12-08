<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Make sure we have users
        // if (User::count() < 5) {
        //     User::factory(10)->create();
        // }

        // Make sure we have shops
        // if (Shop::count() < 3) {
        //     Shop::factory(5)->create();
        // }


        UserRole::firstOrCreate(
            [
                'shop_id' => 1,
                'user_id' => 1,
            ],
            [
                'role' => 'ADMIN',
                'getNotification' => true,
            ]
        );

        UserRole::firstOrCreate(
            [
                'shop_id' => 2,
                'user_id' => 2,
            ],
            [
                'role' => 'ADMIN',
                'getNotification' => true,
            ]
        );

    }
}
