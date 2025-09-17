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
        // aset lancar
        $al = TipeTransaksi::create([
            'name' => 'Aset Lancar'
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $al->id,
            'kode' => '101',
            'name' => 'Kas',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $al->id,
            'kode' => '102',
            'name' => 'Bank',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $al->id,
            'kode' => '103',
            'name' => 'Piutang SPP',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $al->id,
            'kode' => '104',
            'name' => 'Piutang Uang Pembangunan',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $al->id,
            'kode' => '105',
            'name' => 'Pengadaan barang dan ATK',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $al->id,
            'kode' => '106',
            'name' => 'Piutang Guru dan Karyawan',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $al->id,
            'kode' => '107',
            'name' => 'Piutang Lainnya',
        ]);

        $at = TipeTransaksi::create([
            'name' => 'Aset Tetap'
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $at->id,
            'kode' => '299',
            'name' => 'Akumulasi Penyusutan',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $at->id,
            'kode' => '201',
            'name' => 'Tanah',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $at->id,
            'kode' => '202',
            'name' => 'Bangunan Gedung',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $at->id,
            'kode' => '203',
            'name' => 'Kendaraan',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $at->id,
            'kode' => '204',
            'name' => 'Aset Lainnya',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $at->id,
            'kode' => '205',
            'name' => 'Asrama Santri Wati',
        ]);

        $l = TipeTransaksi::create([
            'name' => 'Liabilitas'
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $l->id,
            'kode' => '301',
            'name' => 'Utang (Kendaraan)',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $l->id,
            'kode' => '302',
            'name' => 'Utang Koperasi Usaha (Santriwati)',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $l->id,
            'kode' => '303',
            'name' => 'Utang Koperasi Usaha (THR)',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $l->id,
            'kode' => '304',
            'name' => 'Utang Gaji, Lembuar Karyawan',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $l->id,
            'kode' => '305',
            'name' => 'Utang Listrik, Telepon, Internet',
        ]);

        $e = TipeTransaksi::create([
            'name' => 'Ekuitas'
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $e->id,
            'kode' => '403',
            'name' => 'Modal',
        ]);

        $db = TipeTransaksi::create([
            'name' => 'Dana Bersih'
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $db->id,
            'kode' => '401',
            'name' => 'Aset Neto tanpa pembatasan',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $db->id,
            'kode' => '402',
            'name' => 'Aset Neto tanpa pembatasan',
        ]);

        $ptt = TipeTransaksi::create([
            'name' => 'Pendapatan Tidak Terikat'
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $ptt->id,
            'kode' => '501',
            'name' => 'Pendapatan SPP',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $ptt->id,
            'kode' => '502',
            'name' => 'Pendapatan Uang pembangunan',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $ptt->id,
            'kode' => '503',
            'name' => 'Pendapatan Sumbangan',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $ptt->id,
            'kode' => '504',
            'name' => 'Pendapatan Bunga',
        ]);

        $pt = TipeTransaksi::create([
            'name' => 'Pendapatan Terikat'
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $pt->id,
            'kode' => '505',
            'name' => 'Pendapatan Dana Bos',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $pt->id,
            'kode' => '506',
            'name' => 'Japfa Foundation',
        ]);

        $btt = TipeTransaksi::create([
            'name' => 'Beban Tidak Terikat'
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $btt->id,
            'kode' => '615',
            'name' => 'Beban Penyusutan',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $btt->id,
            'kode' => '601',
            'name' => 'Beasiswa pengurang SPP',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $btt->id,
            'kode' => '603',
            'name' => 'THR dan Insentif Karyawan',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $btt->id,
            'kode' => '604',
            'name' => 'Listrik, Telepon dan Internet',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $btt->id,
            'kode' => '605',
            'name' => 'Iuran Kesehatan dan Asuransi',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $btt->id,
            'kode' => '606',
            'name' => 'Bahan Makanan',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $btt->id,
            'kode' => '607',
            'name' => 'BBM Kendaraan & Genset',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $btt->id,
            'kode' => '608',
            'name' => 'Perjalanan dinas',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $btt->id,
            'kode' => '609',
            'name' => 'Biaya Sumbangan/Hadiah Ramadhan',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $btt->id,
            'kode' => '610',
            'name' => 'Biaya Lain-lain',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $btt->id,
            'kode' => '611',
            'name' => 'Biaya Tamu',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $btt->id,
            'kode' => '612',
            'name' => 'Operasional Pengurus YWABS',
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $btt->id,
            'kode' => '614',
            'name' => 'Biaya Adm Bank dan Pajak',
        ]);

        $bt = TipeTransaksi::create([
            'name' => 'Beban Terikat'
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $bt->id,
            'kode' => '613',
            'name' => 'Kegiatan Japfa Foundation',
        ]);

        $bttbt = TipeTransaksi::create([
            'name' => 'Beban Tidak Terikat dan Beban Terikat'
        ]);

        KategoriTransaksi::create([
            'tipe_transaksi_id' => $bttbt->id,
            'kode' => '602',
            'name' => 'Gaji, Lembur Karyawan',
        ]);
    }
}
