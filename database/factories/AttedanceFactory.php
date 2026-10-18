<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attedance>
 */
class AttedanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Jam masuk/pulang mengikuti jam kerja umum (08:00 - 17:00).
        $timeIn = Carbon::parse(fake()->randomElement([
            '07:30:00', '07:45:00', '07:55:00', '08:00:00',
            '08:10:00', '08:20:00', '08:45:00',
        ]));

        // 15% kemungkinan user lupa check-out.
        $hasCheckout = fake()->boolean(85);

        $timeOut = $hasCheckout
            ? Carbon::parse(fake()->randomElement([
                '16:40:00', '17:00:00', '17:10:00', '17:25:00', '17:45:00',
            ]))
            : null;

        $status = ! $hasCheckout
            ? 'missed clock-out'
            : ($timeIn->gt(Carbon::parse('08:00:00'))
                ? 'late'
                : ($timeOut->lt(Carbon::parse('17:00:00'))
                    ? 'early departure'
                    : 'present'));

        return [
            'user_id' => User::query()->inRandomOrder()->first()?->id
                ?? User::factory(),
            'date' => $this->randomWeekday(),
            'time_in' => $timeIn->format('H:i:s'),
            'time_out' => $timeOut?->format('H:i:s'),
            'latlon_in' => $this->latlon(),
            'latlon_out' => $hasCheckout ? $this->latlon() : null,
            'status' => $status,
        ];
    }

    /**
     * Tanggal acak dalam 30 hari terakhir, tidak jatuh di akhir pekan.
     */
    private function randomWeekday(): string
    {
        do {
            $date = fake()->dateTimeBetween('-30 days', 'today');
        } while (in_array((int) $date->format('N'), [6, 7], true));

        return $date->format('Y-m-d');
    }

    /**
     * Format latitude/longitude sama dengan controller: "lat,lng".
     */
    private function latlon(): string
    {
        return fake()->latitude(-6.9, -6.1) . ',' . fake()->longitude(106.6, 107.0);
    }
}
