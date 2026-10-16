<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->company(),
            'email' => fake()->unique()->companyEmail(),
            'address' => fake()->address(),
            'latitude' => fake()->latitude(-6.9, -6.1),
            'longitude' => fake()->longitude(106.6, 107.0),
            'radius_km' => fake()->randomElement(['0.3', '0.5', '1', '2', '3']),
            'time_in' => fake()->randomElement(['07:00', '07:30', '08:00', '08:30']),
            'time_out' => fake()->randomElement(['16:00', '16:30', '17:00', '17:30']),
        ];
    }
}
