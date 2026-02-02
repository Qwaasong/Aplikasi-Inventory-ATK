<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| 1. Route Public (Halaman Awal & Login)
|--------------------------------------------------------------------------
*/

// web.php

Route::get('/', function () {
    if (Auth::check()) {
        // Jika sudah login, arahkan ke dashboard masing-masing
        return match (Auth::user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'staff' => redirect()->route('staff.barang'),
            'supervisor' => redirect()->route('supervisor.dashboard'),
            default => redirect()->route('login'),
        };
    }
    // Jika belum login, tampilkan halaman login
    return redirect()->route('login');
});

// Pastikan rute login tetap seperti ini
Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->middleware('guest')
    ->name('login.submit');

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

    Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');
});