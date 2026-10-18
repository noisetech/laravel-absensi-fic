<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Position;
use Database\Factories\PositionFactory;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Company::count() === 0) {
            $this->call(CompanySeeder::class);
        }

        Company::query()->each(function ($company) {
            // Aman dijalankan ulang: lewati perusahaan yang sudah punya posisi.
            if (Position::where('company_id', $company->id)->exists()) {
                return;
            }

            foreach (PositionFactory::LADDER as $position) {
                Position::factory()->create([
                    'company_id' => $company->id,
                    'name' => $position['name'],
                    'level' => $position['level'],
                    'status' => 'active',
                ]);
            }
        });
    }
}
