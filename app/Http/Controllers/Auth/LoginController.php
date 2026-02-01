<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Display the login view.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        // $request->authenticate();
        $credentials = $request->only('username', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            $request->session()->regenerate();

            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'staff' => redirect()->route('staff.barang'),
                'supervisor' => redirect()->route('supervisor.dashboard'),
                default => Auth::logout() || redirect()->route('login') -> with('error', 'Role pengguna tidak dikenali.'),
            };
        }

        return back()->withErrors([
            'username' => 'Username Tidak Cocok',
            'password' => 'Password Tidak Cocok',
        ]);

    }

    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

}
