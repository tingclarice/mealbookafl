<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(50)->user()->create();
        User::factory()->count(2)->admin()->create();
        User::forceCreate([
            "name" => "Felix",
            "email" => "ffelixrichardo@gmail.com",
            "phone" => "628123456789",
            "password" => bcrypt("password"),
            "role" => "ADMIN"
        ]);
        User::forceCreate([
            "name" => "Seller Felix",
            "phone" => "628987654321",
            "email" => "frichardo@student.ciputra.ac.id",
            "password" => bcrypt("password"),
            "role" => "USER"
        ]);
        User::forceCreate([
            "name" => "Clarice",
            "phone" => "8888888888",
            "email" => "clarice.ciputra@gmail.com",
            "password" => bcrypt("password"),
            "role" => "ADMIN"
        ]);
        User::forceCreate([
            "name" => "Seller Clarice",
            "phone" => "888888888",
            "email" => "charijanto01@student.ciputra.ac.id",
            "password" => bcrypt("password"),
            "role" => "USER"
        ]);

    }
}
