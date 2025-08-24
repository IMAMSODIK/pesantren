<?php

namespace Database\Seeders;

use App\Models\RekeningKas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RekeningKasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RekeningKas::create([
            'name' => 'Kas Utama'
        ]);

        RekeningKas::create([
            'name' => 'Kas Pendidikan'
        ]);

        RekeningKas::create([
            'name' => 'Kas Asrama'
        ]);

        RekeningKas::create([
            'name' => 'Kas Infaq & Zakat'
        ]);
    }
}
