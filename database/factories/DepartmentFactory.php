<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * Daftar standar departemen yang dipakai factory & seeder.
     */
    public const NAMES = [
        'Human Resources',
        'Finance',
        'Operations',
        'Technology',
        'Marketing',
        'Sales',
        'General Affairs',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::query()->inRandomOrder()->first()?->id
                ?? Company::factory(),
            'name' => fake()->randomElement(self::NAMES),
            'status' => 'active',
        ];
    }

    /**
     * Departemen yang sudah tidak aktif.
     */
    public function inactive(): static
    {
        return $this->state(fn () => [
            'status' => 'non-active',
        ]);
    }
}
