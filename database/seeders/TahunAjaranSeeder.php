<?php

namespace Database\Seeders;

use App\Models\TahunAjaran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TahunAjaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $awal = 2020;
        $akhir = 2021;

        for($i = 1; $i <= 15; $i++){
            TahunAjaran::create([
                'ta' => $awal++ . '/' . $akhir++
            ]);
        }
    }
}
