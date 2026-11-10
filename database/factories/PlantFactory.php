<?php

namespace Database\Factories;

use App\Models\Plant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Plant>
 */
class PlantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'plant_name' => fake()->words(2, true),
            'category' => fake()->randomElement(['ornamental', 'flowering', 'succulent', 'herb']),
            'unit_price' => fake()->randomFloat(2, 0, 1000),
            'description' => fake()->sentence(),
            'status' => fake()->randomElement(['in-stock', 'out of stock']),
            'user_id' => User::factory(),
        ];
    }
}
