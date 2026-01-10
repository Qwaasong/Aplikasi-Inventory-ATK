<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\StockController;
use App\Http\Controllers\Api\VendorController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\LedgerEntryController;
use App\Http\Controllers\Api\Barang_MasukController;
use App\Http\Controllers\Api\Barang_KeluarController;
use App\Http\Controllers\Api\FinancialTransactionController;
use App\Http\Controllers\Api\BerandaController;

// Beranda API Routes
Route::get('/beranda', [BerandaController::class, 'index'])->name('api.beranda.index');
Route::get('/beranda/persentase', [BerandaController::class, 'persentase'])->name('api.beranda.persentase');
Route::get('/beranda/chart', [BerandaController::class, 'chart'])->name('api.beranda.chart');
Route::get('/beranda/distribusi-produk', [BerandaController::class, 'distribusiProduk'])->name('api.beranda.distribusi-produk');

// Vendor API Routes
Route::get('/vendor', [VendorController::class, 'index'])->name('api.vendor.index');
Route::delete('/vendor/{id}', [VendorController::class, 'destroy']);


// Produk API Routes
Route::get('/produk', [ProductController::class, 'index'])->name('api.produk.index');
Route::get('/produk/{id}/stock', [ProductController::class, 'getStock'])->name('api.produk.stock');
Route::delete('/produk/{id}', [ProductController::class, 'destroy'])->name('api.produk.destroy');

// Stok Terkini API Routes
Route::get('/stok_terkini', [StockController::class, 'index'])->name('api.stok_terkini.index');

// Barang Masuk API Routes
Route::get('/barang_masuk', [Barang_MasukController::class, 'index'])->name('api.barang_masuk.index'); // <-- PERUBAHAN
Route::delete('/barang_masuk/{id}', [Barang_MasukController::class, 'destroy']); // <-- PERUBAHAN


// Barang keluar API Routes
Route::get('/barang_keluar', [Barang_KeluarController::class, 'index'])->name('api.barang_keluar.index');
Route::delete('/barang_keluar/{id}', [Barang_KeluarController::class, 'destroy'])->name('api.barang_keluar.destroy');

// Pembukuan Transaksi API Routes
Route::get('/pembukuan_transaksi', [FinancialTransactionController::class, 'index'])->name('api.pembukuan_transaksi.index');
Route::delete('/pembukuan_transaksi/{id}', [FinancialTransactionController::class, 'destroy'])->name('api.pembukuan_transaksi.destroy');

// Hutang Piutang API Routes
Route::get('/ledger_entries', [LedgerEntryController::class, 'index'])->name('api.ledger_entries.index');
Route::delete('/ledger_entries/{id}', [LedgerEntryController::class, 'destroy'])->name('api.ledger_entries.destroy');
Route::get('/ledger_entries/summary', [LedgerEntryController::class, 'summary'])->name('api.ledger_entries.summary');

// Pengguna API Routes
Route::get('/pengguna', [UserController::class, 'index'])->name('api.pengguna.index');
Route::delete('/pengguna/{id}', [UserController::class, 'destroy'])->name('api.pengguna.destroy');

