<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LedgerEntryController;
use App\Http\Controllers\Barang_MasukController;
use App\Http\Controllers\Barang_KeluarController;
use App\Http\Controllers\FinancialTransactionController;

// Route untuk redirect default ketika user sudah login
Route::get('/home', function () {
    return redirect()->route('beranda.index');
})->name('home');

Route::get('/', [DashboardController::class, 'index']);

Route::middleware('auth')->group(function () {
    //Route::get('/profile', [UserController::class, 'edit'])->name('profile.edit');
    //Route::patch('/profile', [UserController::class, 'update'])->name('profile.update');
    //Route::delete('/profile', [UserController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/beranda', function () {
        return view('beranda.index');
    })->name('beranda.index');

    // Vendor
    Route::get('/vendor', function () {
        return view('vendor.index');
    })->name('vendor.index');

    //Ke halaman create vendor
    Route::get('/vendor/create', function () {
        return view('vendor.store');
    })->name('vendor.create');

    //Ketika Submit Akan Menjalankan Method Store di VendorController
    Route::post('/vendor/create', [VendorController::class, 'store'])->name('vendor.store');

    //Ke halaman edit vendor
    Route::get('/vendor/{id}/edit', [VendorController::class, 'edit'])->name('vendor.edit');

    //Ketika Submit Akan Menjalankan Method Update di VendorController
    Route::put('/vendor/{id}', [VendorController::class, 'update'])->name('vendor.update');

    //=========================================================================================================
    // Produk

    //Ke halaman create Produk
    Route::get('/produk/create', function () {
        return view('produk.store');
    })->name('produk.create');

    //Ketika Submit Akan Menjalankan Method Store di ProdukController
    Route::post('/produk/create', [ProductController::class, 'store'])->name('produk.store');

    //Ke halaman edit Produk
    Route::get('/produk/{id}/edit', [ProductController::class, 'edit'])->name('produk.edit');

    //Ketika Submit Akan Menjalankan Method Update di ProdukController
    Route::put('/produk/{id}', [ProductController::class, 'update'])->name('produk.update');

    //memanggil resource controller untuk produk
    Route::resource('produk', ProductController::class);

    //=========================================================================================================
    // Stok Terkini

    Route::get('/stok_terkini', function () {
        return view('stok_terkini.index');
    })->name('stok_terkini.index');

    //=========================================================================================================
    // Barang Masuk
    Route::get('/barang_masuk', function () {
        return view('barang_masuk.index');
    })->name('barang_masuk.index');

    //Ke halaman create Barang Masuk
    Route::get('/barang_masuk/create', function () {
        return view('barang_masuk.store');
    })->name('barang_masuk.create');

    //Ketika Submit Akan Menjalankan Method Store di BarangMasukController
    Route::post('/barang_masuk/create', [Barang_MasukController::class, 'store'])->name('barang_masuk.store');

    //Ke halaman edit Barang Masuk
    Route::get('/barang_masuk/{id}/edit', [Barang_MasukController::class, 'edit'])->name('barang_masuk.edit');

    //Ketika Submit Akan Menjalankan Method Update di BarangMasukController
    Route::put('/barang_masuk/{id}', [Barang_MasukController::class, 'update'])->name('barang_masuk.update');

    //Ketika Menjalankan Metode Delete di BarangMasukController  <-- TAMBAHKAN INI
    Route::delete('/barang_masuk/{id}', [Barang_MasukController::class, 'destroy'])->name('barang_masuk.destroy'); // <-- TAMBAHKAN INI

    //=========================================================================================================
    // Barang Keluar
    Route::get('/barang_keluar', function () {
        return view('barang_keluar.index');
    })->name('barang_keluar.index');

    //Ke halaman create Barang Keluar
    Route::get('/barang_keluar/create', [Barang_KeluarController::class, 'create'])
        ->name('barang_keluar.create');   // ✔ BENAR



    Route::get('/barang_keluar/edit', function () {
        return view('barang_keluar.edit');
    });

    //Ketika Submit Akan Menjalankan Method Store di BarangKeluarController
    Route::post('/barang_keluar/create', [Barang_KeluarController::class, 'store'])->name('barang_keluar.store');

    //Ke halaman edit Barang Keluar
    Route::get('/barang_keluar/{id}/edit', [Barang_KeluarController::class, 'edit'])->name('barang_keluar.edit');

    //Ketika Submit Akan Menjalankan Method Update di BarangKeluarController
    Route::put('/barang_keluar/{id}', [Barang_KeluarController::class, 'update'])->name('barang_keluar.update');

    //=========================================================================================================
    // Pembukuan Transaksi
    Route::get('/pembukuan_transaksi', function () {
        return view('pembukuan_transaksi.index');
    })->name('pembukuan_transaksi.index');

    //Ke halaman create Barang Keluar
    Route::get('/pembukuan_transaksi/create', function () {
        return view('pembukuan_transaksi.store');
    })->name('pembukuan_transaksi.create');

    //Ketika Submit Akan Menjalankan Method Store di BarangKeluarController
    Route::post('/pembukuan_transaksi/create', [FinancialTransactionController::class, 'store'])->name('pembukuan_transaksi.store');

    //Ke halaman edit Barang Keluar
    Route::get('/pembukuan_transaksi/{id}/edit', [FinancialTransactionController::class, 'edit'])->name('pembukuan_transaksi.edit');

    //Ketika Submit Akan Menjalankan Method Update di BarangKeluarController
    Route::put('/pembukuan_transaksi/{id}', [FinancialTransactionController::class, 'update'])->name('pembukuan_transaksi.update');

    //Export PDF Pembukuan Transaksi
    Route::get('/laporan-keuangan-pdf', [FinancialTransactionController::class, 'generatePDF'])->name('pembukuan_transaksi.export_pdf');

    //Route Delete Pembukuan Transaksi
    Route::delete('/pembukuan_transaksi/{id}', [FinancialTransactionController::class, 'destroy'])->name('pembukuan_transaksi.destroy');

    //=========================================================================================================
   // Hutang Piutang Routes
    Route::get('/ledger_entries', function () {
        return view('ledger_entries.index');
    })->name('ledger_entries.index');

    Route::post('/ledger_entries', [LedgerEntryController::class, 'store'])->name('ledger_entries.store');
    Route::get('/ledger_entries/create', [LedgerEntryController::class, 'create'])->name('ledger_entries.create');
    Route::get('/ledger_entries/{id}/edit', [LedgerEntryController::class, 'edit'])->name('ledger_entries.edit');
    Route::put('/ledger_entries/{id}', [LedgerEntryController::class, 'update'])->name('ledger_entries.update');
    Route::post('/ledger_entries/{id}/lunaskan', [LedgerEntryController::class, 'lunaskan'])->name('ledger_entries.lunaskan');

    Route::post('/ledger_entries/{id}/update-jatuh-tempo', [LedgerEntryController::class, 'updateJatuhTempo']);
    Route::post('/ledger_entries/{id}/tambah-utang', [LedgerEntryController::class, 'tambahUtang']);
    Route::post('/ledger_entries/{id}/bayar-utang', [LedgerEntryController::class, 'bayarUtang']);

    //=========================================================================================================
    // Pengguna
    Route::get('/pengguna', function () {
        return view('pengguna.index');
    })->name('pengguna.index');
    
    //Ke halaman create Pengguna
    Route::get('/pengguna/create', function () {
        return view('pengguna.store');
    })->name('pengguna.create');
    
    //Ketika Submit Akan Menjalankan Method Store di UserController
    Route::post('/pengguna/create', [UserController::class, 'store'])->name('pengguna.store');
    
    //Ke halaman edit Pengguna
    Route::get('/pengguna/{id}/edit', [UserController::class, 'edit'])->name('pengguna.edit');
    
    //Ketika Submit Akan Menjalankan Method Update di UserController
    Route::put('/pengguna/{id}', [UserController::class, 'update'])->name('pengguna.update');
});

require __DIR__ . '/auth.php';
