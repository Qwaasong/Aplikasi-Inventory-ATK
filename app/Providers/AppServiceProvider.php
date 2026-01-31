<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Mendefinisikan Gate untuk Admin
        Gate::define('admin-only', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('akses-barang', function (User $user) {
            return in_array($user->role, ['admin', 'staff']);
        });

        Gate::define('akses-laporan', function (User $user) {
            return in_array($user->role, ['supervisor']);
        });
    }
}