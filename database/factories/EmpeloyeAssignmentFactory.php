<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Department;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmpeloyeAssignment>
 */
class EmpeloyeAssignmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Ambil company yang sudah ada, jika belum ada maka buat baru.
        $company = Company::query()->inRandomOrder()->first()
            ?? Company::factory()->create();

        // Department & Position harus berasal dari company yang sama.
        $department = Department::query()
            ->where('company_id', $company->id)
            ->inRandomOrder()
            ->first()
            ?? Department::factory()->create(['company_id' => $company->id]);

        $position = Position::query()
            ->where('company_id', $company->id)
            ->inRandomOrder()
            ->first()
            ?? Position::factory()->create(['company_id' => $company->id]);

        $startDate = fake()->dateTimeBetween('-3 years', '-1 month');
        $status = fake()->randomElement(['active', 'non-active']);

        return [
            'user_id' => User::query()->inRandomOrder()->first()?->id
                ?? User::factory(),
            'company_id' => $company->id,
            'departemen_id' => $department->id,
            'position_id' => $position->id,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $status === 'active'
                ? null
                : fake()->dateTimeBetween($startDate, 'now')->format('Y-m-d'),
            'status' => $status,
        ];
    }

    /**
     * Assignment yang masih berjalan.
     */
    public function active(): static
    {
        return $this->state(fn () => [
            'start_date' => fake()->dateTimeBetween('-2 years', '-1 month')->format('Y-m-d'),
            'end_date' => null,
            'status' => 'active',
        ]);
    }

    /**
     * Assignment yang sudah berakhir.
     */
    public function nonActive(): static
    {
        $startDate = fake()->dateTimeBetween('-4 years', '-2 years');

        return $this->state(fn () => [
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => fake()->dateTimeBetween($startDate, 'now')->format('Y-m-d'),
            'status' => 'non-active',
        ]);
    }
}
