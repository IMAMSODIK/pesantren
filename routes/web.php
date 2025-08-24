<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
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

    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/login');
    })->name('logout');
});

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
