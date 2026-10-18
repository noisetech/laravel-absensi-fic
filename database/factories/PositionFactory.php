<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Position>
 */
class PositionFactory extends Factory
{
    /**
     * Tangga jabatan standar: nama selalu sejajar dengan level-nya.
     * Dipakai oleh factory & seeder supaya data selaras.
     */
    public const LADDER = [
        ['name' => 'Staff', 'level' => 'staff'],
        ['name' => 'Senior Staff', 'level' => 'staff'],
        ['name' => 'Supervisor', 'level' => 'supervisor'],
        ['name' => 'Manager', 'level' => 'manager'],
        ['name' => 'Senior Manager', 'level' => 'manager'],
        ['name' => 'Director', 'level' => 'director'],
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $position = fake()->randomElement(self::LADDER);

        return [
            'company_id' => Company::query()->inRandomOrder()->first()?->id
                ?? Company::factory(),
            'name' => $position['name'],
            'level' => $position['level'],
            'description' => fake()->sentence(),
            'status' => 'active',
        ];
    }

    /**
     * Posisi yang sudah tidak aktif.
     */
    public function inactive(): static
    {
        return $this->state(fn () => [
            'status' => 'non-active',
        ]);
    }
}
