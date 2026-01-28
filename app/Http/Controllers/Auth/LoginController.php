<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            return match ($user->role) {
                'admin' => redirect()->intended(route('admin.dashboard')),
                'pegawai' => redirect()->intended(route('pegawai.barang')),
                'kepsek'  => redirect()->intended(route('kepsek.laporan')),
                default => redirect()->intended('/'),
            };
        }

        return back()->withErrors([
            'username' => 'Username atau password yang diberikan salah.',
        ])->onlyInput('username');
    }
}