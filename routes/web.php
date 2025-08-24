<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriTransaksiController;
use App\Http\Controllers\RekeningKasController;
use App\Http\Controllers\TipeTransaksiController;
use App\Http\Controllers\UserController;
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

    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/login');
    })->name('logout');
});

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
