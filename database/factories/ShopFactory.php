<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shop>
 */
class ShopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company . ' Shop',
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'profileImage' => $this->faker->imageUrl(300, 300, 'business', true),
        ];
    }

    /**
     * Optional: Create without image
     */
    public function withoutImage(): static
    {
        return $this->state(fn () => [
            'profileImage' => null,
        ]);
    }
}
