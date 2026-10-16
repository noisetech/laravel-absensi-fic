<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'name' => 'PT Ifast Solution Technology',
            'email' => 'contoh@perusahaan.com',
            'address' => 'Jl. Contoh No. 123',
            'latitude' => '-6.2088',
            'longitude' => '106.8456',
            'radius_km' => '0.5',
            'time_in' => '08:00',
            'time_out' => '17:00',
        ]);

        Company::factory(9)->create();
    }
}
