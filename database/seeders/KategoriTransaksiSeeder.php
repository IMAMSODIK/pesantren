<?php

namespace Database\Seeders;

use App\Models\KategoriTransaksi;
use App\Models\TipeTransaksi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriTransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipeTransaksi::create([
            'name' => 'Pengeluaran'
        ]);

        TipeTransaksi::create([
            'name' => 'Pemasukan'
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => 1,
            'name' => 'SPP Santri',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => 1,
            'name' => 'Infaq',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => 1,
            'name' => 'Sedekah',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => 1,
            'name' => 'Hibah',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => 2,
            'name' => 'Listrik, Air, Internet',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => 2,
            'name' => 'Transportasi',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => 2,
            'name' => 'Konsumsi santri',
        ]);
    }
}
