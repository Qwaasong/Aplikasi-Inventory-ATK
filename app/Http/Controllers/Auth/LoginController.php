<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Menangani permintaan login.
     */
    public function login(Request $request)
    {
        // 1. Validasi input dari form
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Coba untuk melakukan autentikasi
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Jika berhasil, regenerate session
            $request->session()->regenerate();

            // 3. Redirect ke dashboard yang sesuai berdasarkan role
            return $this->redirectToRoleDashboard(Auth::user()->role);
        }

        // 4. Jika autentikasi gagal
        return back()->withErrors([
            'username' => 'Username atau password yang diberikan salah.',
        ])->onlyInput('username');
    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }

    /**
     * Redirect berdasarkan role user.
     */
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
                return redirect()->route('admin.dashboard');
        }
    }
}