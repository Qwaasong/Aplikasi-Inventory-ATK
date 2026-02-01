<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| 1. Route Public (Halaman Awal & Login)
|--------------------------------------------------------------------------
*/

// Redirect halaman utama (/) langsung ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| 2. Route Admin (Middleware Auth + Role Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('auth.admin.dashboard');
    })->name('dashboard');

    Route::get('/barang', function () {
        return view('auth.admin.barang');
    })->name('barang');

    Route::get('/user', function () {
        return view('auth.admin.user');
    })->name('user');

    // Route untuk Export Excel
    Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');

});

/*
|--------------------------------------------------------------------------
| 3. Route Staff (Middleware Auth + Role Staff)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/barang', function () {
        return view('auth.staff.barang');
    })->name('barang');
});

/*
|--------------------------------------------------------------------------
| 4. Route Supervisor (Middleware Auth + Role Supervisor)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:supervisor'])->prefix('supervisor')->name('supervisor.')->group(function () {
    Route::get('/dashboard', function () {
        return view('auth.supervisor.dashboard');
    })->name('dashboard');

    Route::get('/laporan', function () {
        return view('auth.kepsek.laporan');
    })->name('laporan');
});