<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Tampilkan form edit profil pengguna.
     */
    public function edit($id): View
{
    $user = User::findOrFail($id); // Cari pengguna berdasarkan ID
    return view('pengguna.edit', [ // Arahkan ke view 'pengguna.edit' (asumsi)
        'user' => $user,
    ]);
}

    /**
     * Simpan data profil baru.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input dari form
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Simpan ke database
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Redirect ke halaman daftar profil (atau ke edit)
        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Update informasi profil pengguna yang sudah ada.
     */
    public function update(ProfileUpdateRequest $request, $id): RedirectResponse
{
    $user = User::findOrFail($id); // Cari pengguna berdasarkan ID

    $user->fill($request->validated());

    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    $user->save();

    // Redirect kembali ke halaman index pengguna
    return Redirect::route('pengguna.index')->with('status', 'profile-updated');
}

   
}
