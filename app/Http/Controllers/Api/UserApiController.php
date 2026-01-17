<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserApiController extends Controller
{
    /**
     * Tampilkan daftar user dengan pencarian dan paginasi.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');

        $users = User::when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_user', 'like', '%' . $search . '%')
                      ->orWhere('username', 'like', '%' . $search . '%')
                      ->orWhere('role', 'like', '%' . $search . '%');
                });
            })
            ->latest('id_user')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $users
        ]);
    }

    /**
     * Ambil satu data user untuk modal edit.
     */
    public function show($id)
    {
        $user = User::find($id);
        if ($user) {
            return response()->json(['success' => true, 'data' => $user]);
        }
        return response()->json(['success' => false, 'message' => 'User tidak ditemukan'], 404);
    }

    /**
     * Simpan user baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_user' => 'required|string|max:255',
            'username'  => 'required|unique:user,username',
            'password'  => 'required|min:6',
            'role'      => 'required'
        ]);

        $user = User::create([
            'nama_user' => $request->nama_user,
            'username'  => $request->username,
            'role'      => $request->role,
            'password'  => Hash::make($request->password), // Enkripsi Password
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil ditambahkan',
            'data'    => $user
        ]);
    }

    /**
     * Update data user.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama_user' => 'required',
            'username'  => 'required|unique:user,username,' . $id . ',id_user',
            'role'      => 'required'
        ]);

        $data = [
            'nama_user' => $request->nama_user,
            'username'  => $request->username,
            'role'      => $request->role,
        ];

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diperbarui'
        ]);
    }

    /**
     * Hapus user.
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json(['success' => true, 'message' => 'User berhasil dihapus']);
        }
        return response()->json(['success' => false, 'message' => 'User tidak ditemukan'], 404);
    }
}