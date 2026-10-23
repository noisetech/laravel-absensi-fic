<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat role untuk guard API
        Role::create([
            'name' => 'super admin',
            'guard_name' => 'api'
        ]);

            Role::create([
            'name' => 'super admin',
            'guard_name' => 'web'
        ]);
    }
}
