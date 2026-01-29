<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $closure, ...$roles)
    {
        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Cek apakah user memiliki role yang diizinkan
        // Asumsi: Model User memiliki kolom 'role' dengan nilai: 'admin', 'staff', 'supervisor'
        if (in_array($user->role, $roles)) {
            return $closure($request);
        }

        // Jika tidak memiliki akses, redirect ke dashboard sesuai role
        return $this->redirectToRoleDashboard($user->role);
    }

    private function redirectToRoleDashboard($role)
    {
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'staff':
                return redirect()->route('staff.barang');
            case 'supervisor':
                return redirect()->route('supervisor.dashboard');
            default:
                return redirect()->route('login');
        }
    }
}