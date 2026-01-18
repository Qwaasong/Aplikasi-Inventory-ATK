<?php

use Illuminate\Support\Facades\Route;
// Perhatikan Namespace ini harus sesuai dengan lokasi file controller Anda
// use App\Http\Controllers\Api\BarangApiController; 
// use App\Http\Controllers\TransaksiController;
// use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes Admin
|--------------------------------------------------------------------------
*/

// Halaman login sebagai halaman utama
Route::get('/', function () {
    return view('auth.login');
});

// Route untuk dashboard admin
Route::get('admin/dashboard', function(){
    return view('auth.admin.dashboard');
})->name('admin.dashboard');

// Route untuk barang admin
Route::get('admin/barang', function(){
    return view('auth.admin.barang');
})->name('admin.barang');

// Route untuk halaman user admin
Route::get('admin/user', function(){
    return view('auth.admin.user');
})->name('admin.user');

/*
|--------------------------------------------------------------------------
| Web Routes Role 2
|--------------------------------------------------------------------------
*/

// Route untuk dashboard role 2
Route::get('role2/dashboard', function(){
    return view('auth.role2.dashboard');
})->name('role2.dashboard');

// Route untuk halaman barang masuk role 2
Route::get('role2/barang_masuk', function(){
    return view('auth.role2.barang_masuk');
})->name('role2.barang_masuk');

/*
|--------------------------------------------------------------------------
| Web Routes Role 3
|--------------------------------------------------------------------------
*/

// Route untuk dashboard role 3
Route::get('role3/dashboard', function(){
    return view('auth.role3.dashboard');
})->name('role3.dashboard');

// Route untuk halaman barang keluar role 3
Route::get('role3/barang_keluar', function(){   
    return view('auth.role3.barang_keluar');
})->name('role3.barang_keluar');