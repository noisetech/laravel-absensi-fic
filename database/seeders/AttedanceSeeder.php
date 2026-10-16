<?php

namespace Database\Seeders;

use App\Models\Attedance;
use App\Models\Attedances;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttedanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Attedance::factory(10)->create();
    }
}
