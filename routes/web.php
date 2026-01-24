<?php

use Illuminate\Support\Facades\Route;
// Pastikan namespace ini sesuai dengan lokasi file Controller Anda
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;

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
// PENTING: Nama route harus 'login' agar middleware 'auth' tahu kemana harus melempar user yang belum login.
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Route POST: Memproses data form login
Route::post('/login', [LoginController::class, 'login']);

// Route POST: Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| 2. Route Admin (Middleware Auth)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', function () {
        return view('auth.admin.dashboard'); // Pastikan file view ini ada
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
| 3. Route Role 2 (Contoh: Staff Gudang)
|--------------------------------------------------------------------------
*/
// Sebaiknya tambahkan middleware auth juga di sini
Route::middleware(['auth'])->prefix('role2')->name('role2.')->group(function () {

    Route::get('/dashboard', function(){
        return view('auth.role2.dashboard');
    })->name('dashboard');

    Route::get('/barang_masuk', function(){
        return view('auth.role2.barang_masuk');
    })->name('barang_masuk');

});

/*
|--------------------------------------------------------------------------
| 4. Route Role 3 (Contoh: User Biasa)
|--------------------------------------------------------------------------
*/
// Sebaiknya tambahkan middleware auth juga di sini
Route::middleware(['auth'])->prefix('role3')->name('role3.')->group(function () {

    Route::get('/dashboard', function(){
        return view('auth.role3.dashboard');
    })->name('dashboard');

    Route::get('/barang_keluar', function(){   
        return view('auth.role3.barang_keluar');
    })->name('barang_keluar');

});