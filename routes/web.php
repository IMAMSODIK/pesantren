<?php

use App\Http\Controllers\AsetTetapController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuBesarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriTransaksiController;
use App\Http\Controllers\LabaRugiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MutasiBankController;
use App\Http\Controllers\NeracaSaldoController;
use App\Http\Controllers\PenyesuaianController;
use App\Http\Controllers\PenyusutanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RekeningKasController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\TipeTransaksiController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\TransaksiHarianController;
use App\Http\Controllers\UserController;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

\Carbon\Carbon::setLocale('id');

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        $data = [
            'pageTitle' => 'Login - ' . env('APP_NAME', 'Manajemen Keuangan'),
        ];

        return view('auth.login', $data);
    })->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::post('/user/store', [UserController::class, 'store']);
    Route::get('/user/edit', [UserController::class, 'edit']);
    Route::post('/user/update', [UserController::class, 'update']);
    Route::post('/user/delete', [UserController::class, 'delete']);

    Route::get('/tipe-transaksi', [TipeTransaksiController::class, 'index'])->name('tipe-transaksi');
    Route::post('/tipe-transaksi/store', [TipeTransaksiController::class, 'store']);
    Route::get('/tipe-transaksi/edit', [TipeTransaksiController::class, 'edit']);
    Route::post('/tipe-transaksi/update', [TipeTransaksiController::class, 'update']);
    Route::post('/tipe-transaksi/delete', [TipeTransaksiController::class, 'delete']);

    Route::get('/aset', [AsetTetapController::class, 'index'])->name('aset');
    Route::post('/aset/store', [AsetTetapController::class, 'store']);
    Route::get('/aset/edit', [AsetTetapController::class, 'edit']);
    Route::post('/aset/update', [AsetTetapController::class, 'update']);
    Route::post('/aset/delete', [AsetTetapController::class, 'delete']);

    Route::get('/kategori-transaksi', [KategoriTransaksiController::class, 'index'])->name('kategori-transaksi');
    Route::post('/kategori-transaksi/store', [KategoriTransaksiController::class, 'store']);
    Route::get('/kategori-transaksi/edit', [KategoriTransaksiController::class, 'edit']);
    Route::post('/kategori-transaksi/update', [KategoriTransaksiController::class, 'update']);
    Route::post('/kategori-transaksi/delete', [KategoriTransaksiController::class, 'delete']);

    Route::get('/rekening-kas', [RekeningKasController::class, 'index'])->name('rekening-kas');
    Route::post('/rekening-kas/store', [RekeningKasController::class, 'store']);
    Route::get('/rekening-kas/edit', [RekeningKasController::class, 'edit']);
    Route::post('/rekening-kas/update', [RekeningKasController::class, 'update']);
    Route::post('/rekening-kas/delete', [RekeningKasController::class, 'delete']);

    Route::get('/tahun-ajaran', [TahunAjaranController::class, 'index'])->name('tahun-ajaran');
    Route::post('/tahun-ajaran/store', [TahunAjaranController::class, 'store']);
    Route::get('/tahun-ajaran/edit', [TahunAjaranController::class, 'edit']);
    Route::post('/tahun-ajaran/update', [TahunAjaranController::class, 'update']);
    Route::post('/tahun-ajaran/delete', [TahunAjaranController::class, 'delete']);

    Route::get('/penerimaan-dana', [TransaksiController::class, 'index'])->name('penerimaan-dana');
    Route::post('/penerimaan-dana/store', [TransaksiController::class, 'store']);
    Route::get('/penerimaan-dana/edit', [TransaksiController::class, 'edit']);
    Route::post('/penerimaan-dana/update', [TransaksiController::class, 'update']);
    Route::post('/penerimaan-dana/delete', [TransaksiController::class, 'delete']);
    Route::get('/penerimaan-dana/search', [TransaksiController::class, 'search']);

    Route::get('/transaksi-harian', [TransaksiHarianController::class, 'index'])->name('transaksi-harian');
    Route::post('/transaksi-harian/store', [TransaksiHarianController::class, 'store']);
    Route::get('/transaksi-harian/edit', [TransaksiHarianController::class, 'edit']);
    Route::post('/transaksi-harian/update', [TransaksiHarianController::class, 'update']);
    Route::post('/transaksi-harian/delete', [TransaksiHarianController::class, 'delete']);
    Route::get('/transaksi-harian/search', [TransaksiHarianController::class, 'search']);

    Route::get('/penyesuaian', [PenyesuaianController::class, 'index'])->name('penyesuaian');
    Route::post('/penyesuaian/store', [PenyesuaianController::class, 'store']);
    Route::get('/penyesuaian/edit', [PenyesuaianController::class, 'edit']);
    Route::post('/penyesuaian/update', [PenyesuaianController::class, 'update']);
    Route::post('/penyesuaian/delete', [PenyesuaianController::class, 'delete']);
    Route::get('/penyesuaian/search', [PenyesuaianController::class, 'search']);

    Route::get('/penyusutan', [PenyusutanController::class, 'index'])->name('penyusutan');
    Route::post('/penyusutan/proses', [PenyusutanController::class, 'proses'])->name('penyusutan.proses');

    Route::get('/rekonsiliasi-bank', [MutasiBankController::class, 'index'])->name('rekonsiliasi-bank');
    Route::post('/rekonsiliasi-bank/import', [MutasiBankController::class, 'import'])->name('rekonsiliasi.import');
    Route::get('/rekonsiliasi-bank/proses', [MutasiBankController::class, 'reconcile'])->name('rekonsiliasi.proses');
    Route::get('/rekonsiliasi-bank/laporan', [MutasiBankController::class, 'laporan'])->name('rekonsiliasi.laporan');

    Route::get('/profile', [ProfileController::class, 'index']);
    Route::post('/profile/update-data', [ProfileController::class, 'updateProfile']);
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword']);

    Route::get('/laporan/jurnal-umum', [LaporanController::class, 'jurnalUmum'])->name('jurnal_umum');
    Route::get('/laporan/jurnal-umum/filter', [LaporanController::class, 'filterJurnalUmum']);
    Route::get('/laporan/jurnal-umum/pdf', [LaporanController::class, 'exportPdf']);

    Route::get('/laporan/buku-besar', [BukuBesarController::class, 'bukuBesar'])->name('buku_besar');
    Route::get('/laporan/buku-besar/filter', [BukuBesarController::class, 'filterBukuBesar']);
    Route::get('/laporan/buku-besar/pdf', [BukuBesarController::class, 'bukuBesarPdf']);

    Route::get('/laporan/neraca-saldo', [NeracaSaldoController::class, 'neracaSaldo'])->name('neraca_saldo');
    Route::get('/laporan/neraca-saldo/filter', [NeracaSaldoController::class, 'filterNeracaSaldo']);
    Route::get('/laporan/neraca-saldo/pdf', [NeracaSaldoController::class, 'exportNeracaSaldoPDF']);

    Route::get('/laporan/laba-rugi', [LabaRugiController::class, 'labaRugi'])->name('laba_rugi');
    Route::get('/laporan/laba-rugi/filter', [LabaRugiController::class, 'filterLabaRugi']);
    Route::get('/laporan/laba-rugi/pdf', [LabaRugiController::class, 'exportPdfLabaRugi']);

    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/login');
    })->name('logout');
});

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

Route::get('/', function () {
    return view('welcome');
});