<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Department;
use Database\Factories\DepartmentFactory;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
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
            // Aman dijalankan ulang: lewati perusahaan yang sudah punya departemen.
            if (Department::where('company_id', $company->id)->exists()) {
                return;
            }

            foreach (DepartmentFactory::NAMES as $name) {
                Department::factory()->create([
                    'company_id' => $company->id,
                    'name' => $name,
                    'status' => 'active',
                ]);
            }
        });
    }
}
