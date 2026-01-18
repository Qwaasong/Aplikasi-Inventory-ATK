<?php
// Tambahkan Use Statement di bagian atas file
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BarangApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\KategoriApiController; // Jika ada controller kategori

// --- Route Baru Anda ---

// Barang (ATK) API Routes
Route::get('/barang', [BarangApiController::class, 'index'])->name('api.barang.index');
Route::get('/barang/{id}', [BarangApiController::class, 'show'])->name('api.barang.show');
Route::post('/barang', [BarangApiController::class, 'store'])->name('api.barang.store');
Route::put('/barang/{id}', [BarangApiController::class, 'update'])->name('api.barang.update');
Route::post('/barang/keluar', [BarangApiController::class, 'keluar'])->name('api.barang.keluar');
Route::delete('/barang/{id}', [BarangApiController::class, 'destroy'])->name('api.barang.destroy');

// Pengguna (User) API Routes
// Catatan: Saya sesuaikan namanya menjadi /user agar tidak bentrok dengan /pengguna yang sudah ada
Route::get('/user', [UserApiController::class, 'index'])->name('api.user.index');
Route::get('/user/{id}', [UserApiController::class, 'show'])->name('api.user.show');
Route::post('/user', [UserApiController::class, 'store'])->name('api.user.store');
Route::put('/user/{id}', [UserApiController::class, 'update'])->name('api.user.update');
Route::delete('/user/{id}', [UserApiController::class, 'destroy'])->name('api.user.destroy');

// Kategori API Routes (Opsional - Untuk mengisi dropdown di modal)
Route::get('/kategori', [KategoriApiController::class, 'index'])->name('api.kategori.index');
