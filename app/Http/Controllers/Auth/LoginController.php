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

            // 3. Redirect ke dashboard yang sesuai berdasarkan role (jika ada)
            //    Ini adalah contoh sederhana, Anda bisa membuatnya lebih kompleks
            //    jika Anda memiliki kolom 'role' di tabel users.
            //    Untuk sekarang, kita arahkan ke dashboard admin.
            return redirect()->intended('admin/dashboard');
        }

        // 4. Jika autentikasi gagal
        return back()->withErrors([
            'username' => 'Username atau password yang diberikan salah.',
        ])->onlyInput('username');
    }
}
