<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
    }
}
