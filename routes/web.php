<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| 1. Route Public (Halaman Awal & Login)
|--------------------------------------------------------------------------
*/

// Redirect halaman utama (/) langsung ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// Route GET: Menampilkan Halaman Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Route POST: Memproses data form login
Route::post('/login', [LoginController::class, 'login']);

// Route POST: Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| 2. Route Admin (Middleware Auth + Role Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', function () {
        // View berada di: resources/views/admin/dashboard.blade.php
        return view('auth.admin.dashboard');
    })->name('dashboard');

    Route::get('/barang', function () {
        return view('auth.admin.barang');
    })->name('barang');

    Route::get('/user', function () {
        return view('auth.admin.user');
    })->name('user');
    
});

/*
|--------------------------------------------------------------------------
| 3. Route Staff (Middleware Auth + Role Staff)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {

    Route::get('/barang', function() {
        // View berada di: resources/views/staff/barang.blade.php
        return view('auth.staff.barang');
    })->name('barang');

    // Jika staff hanya boleh akses barang, hapus dashboard untuk staff
    // Atau tambahkan jika diperlukan
    // Route::get('/dashboard', function(){
    //     return view('staff.dashboard');
    // })->name('dashboard');

});

/*
|--------------------------------------------------------------------------
| 4. Route Supervisor (Middleware Auth + Role Supervisor)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:supervisor'])->prefix('supervisor')->name('supervisor.')->group(function () {

    Route::get('/dashboard', function() {
        // View berada di: resources/views/supervisor/dashboard.blade.php
        return view('auth.supervisor.dashboard');
    })->name('dashboard');

    // Supervisor hanya memiliki akses ke dashboard untuk laporan
    // Jika perlu route lain, tambahkan di sini

    Route::get('/laporan', function(){
        return view('auth.kepsek.laporan');
    })->name('laporan');
});