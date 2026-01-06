<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    // Run the database seeds
    public function run(): void
    {
        UserRole::firstOrCreate(
            [
                'shop_id' => 1,
                'user_id' => 53,
            ],
            [
                'role' => 'OWNER',
                'getNotification' => true,
            ]
        );

        UserRole::firstOrCreate(
            [
                'shop_id' => 2,
                'user_id' => 55,
            ],
            [
                'role' => 'OWNER',
                'getNotification' => true,
            ]
        );

    }
}
