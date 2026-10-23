<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(UserSeeder::class);

        \App\Models\User::factory(10)->create();

        $this->call(CompanySeeder::class);

        $this->call(DepartmentSeeder::class);

        $this->call(PositionSeeder::class);

        $this->call(AttedanceSeeder::class);

        $this->call(EmpeloyeAssignmentSeeder::class);
    }
}
