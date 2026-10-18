<?php

namespace Database\Seeders;

use App\Models\Attedance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttedanceSeeder extends Seeder
{
    /**
     * Jumlah hari kerja terakhir yang diabsenkan untuk setiap user.
     */
    private const WORKING_DAYS = 10;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::count() === 0) {
            User::factory(10)->create();
        }

        // 10 hari terakhir tanpa Sabtu/Minggu, dari yang paling lama ke terbaru.
        $workingDays = collect(range(0, self::WORKING_DAYS - 1))
            ->map(fn ($offset) => now()->subDays($offset))
            ->filter(fn ($day) => ! in_array($day->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY]))
            ->reverse()
            ->values();

        User::query()->each(function ($user) use ($workingDays) {
            $workingDays->each(function ($day) use ($user) {
                $date = $day->toDateString();

                // Idempoten: satu user hanya punya satu absen per tanggal.
                $exists = Attedance::where('user_id', $user->id)
                    ->where('date', $date)
                    ->exists();

                if (! $exists) {
                    Attedance::factory()->create([
                        'user_id' => $user->id,
                        'date' => $date,
                    ]);
                }
            });
        });
    }
}
