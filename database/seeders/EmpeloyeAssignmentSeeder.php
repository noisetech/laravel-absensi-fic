<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Department;
use App\Models\EmpeloyeAssignment;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;

class EmpeloyeAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan data relasi sudah tersedia sebelum membuat assignment.
        if (User::count() === 0) {
            User::factory(10)->create();
        }

        if (Company::count() === 0) {
            $this->call(CompanySeeder::class);
        }

        if (Department::count() === 0) {
            $this->call(DepartmentSeeder::class);
        }

        if (Position::count() === 0) {
            $this->call(PositionSeeder::class);
        }

        // 10 assignment aktif dan 5 yang sudah berakhir.
        EmpeloyeAssignment::factory(10)->active()->create();
        EmpeloyeAssignment::factory(5)->nonActive()->create();
    }
}
