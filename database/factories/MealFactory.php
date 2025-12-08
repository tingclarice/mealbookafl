<?php

namespace Database\Factories;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meal>
 */
class MealFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'description' => fake()->sentence(10),
            'price' => fake()->randomFloat(2, 5, 50),
            'category' => fake()->randomElement(['MEAL', 'SNACK', 'DRINKS']),
            'isAvailable' => fake()->boolean(90),
            'image_url' => fake()->imageUrl(640, 480, 'food', true),
            'shop_id' => Shop::factory(),
        ];
    }
}
