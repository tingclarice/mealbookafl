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
            "password" => bcrypt("password"),
            "role" => "ADMIN"
        ]);
        User::forceCreate([
            "name" => "Seller Felix",
            "email" => "frichardo@student.ciputra.ac.id",
            "password" => bcrypt("password"),
            "role" => "USER"
        ]);
        User::forceCreate([
            "name" => "Clarice",
            "email" => "clarice.ciputra@gmail.com",
            "password" => bcrypt("password"),
            "role" => "ADMIN"
        ]);

    }
}
