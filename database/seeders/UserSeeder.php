<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userCreate = User::create([
            'name'      => 'Super admin',
            'email'     => 'sa@gmail.com',
            'password'  => bcrypt('password')
        ]);

        //assign permission to role
        $role = Role::where('name', 'super admin')
            ->where('guard_name', 'api')
            ->firstOrFail();

        $permissions = Permission::all();

        $role->syncPermissions($permissions);


        $user = User::find(1);
        $user->assignRole($role);
    }
}
