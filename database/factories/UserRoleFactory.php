<?php

namespace Database\Factories;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserRole>
 */
class UserRoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'shop_id' => Shop::factory(),
            'user_id' => User::factory(),
            'role' => $this->faker->randomElement(['OWNER', 'STAFF']),
        ];
    }

    /**
     * Force OWNER role
     */
    public function owner(): static
    {
        return $this->state(fn () => [
            'role' => 'OWNER',
        ]);
    }

    /**
     * Force STAFF role
     */
    public function staff(): static
    {
        return $this->state(fn () => [
            'role' => 'STAFF',
        ]);
    }
}
