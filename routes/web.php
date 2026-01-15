<?php

use Illuminate\Support\Facades\Route;
// Perhatikan Namespace ini harus sesuai dengan lokasi file controller Anda
use App\Http\Controllers\Api\BarangApiController; 
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

//--- ROUTE BARANG (CRUD) ---
//Menggunakan BarangApiController sesuai keinginan Anda
//Akses URL:
//GET  /barang        -> Masih return JSON (sesuai kode index Anda)
//GET  /barang/create -> Menampilkan View (Form Tambah)
//POST /barang        -> Proses Simpan & Redirect
Route::resource('barang', BarangApiController::class);

// --- ROUTE TRANSAKSI ---
Route::post('/barang/{id}/masuk', [TransaksiController::class, 'barangMasuk'])->name('barang.masuk');
Route::post('/barang/{id}/keluar', [TransaksiController::class, 'barangKeluar'])->name('barang.keluar');

// --- ROUTE USER ---
Route::resource('user', UserController::class)->except(['show']);