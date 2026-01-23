<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator; // Tambahkan ini

class UserApiController extends Controller
{
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
            ->latest('id_user') // Pastikan primary key di model User adalah 'id_user'
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $users
        ]);
    }

    public function store(Request $request)
{
    // 1. Definisikan Rule Validasi
    $rules = [
        'nama_user' => 'required|string|max:255',
        'username'  => 'required|unique:user,username',
        'password'  => 'required|string|min:6', // <--- Minimal 6 Karakter
        'role'      => 'required'
    ];

    // 2. Definisikan Pesan Error Kustom (Opsional tapi disarankan)
    $messages = [
        'password.min'      => 'Password harus memiliki minimal 6 karakter.',
        'password.required' => 'Password wajib diisi.',
        'username.unique'   => 'Username sudah terdaftar, silakan gunakan yang lain.',
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validasi Gagal',
            'errors'  => $validator->errors()
        ], 422);
    }

    try {
        $user = User::create([
            'nama_user' => $request->nama_user,
            'username'  => $request->username,
            'role'      => $request->role,
            'password'  => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil ditambahkan',
        ], 201);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal menyimpan data ke database.'
        ], 500);
    }
}


    public function show($id)
    {
        // Menggunakan id_user karena biasanya itu primary key kustom Anda
        $user = User::where('id_user', $id)->first();
        
        if ($user) {
            return response()->json([
                'success' => true, 
                'data' => $user
            ]);
        }
        
        return response()->json([
            'success' => false, 
            'message' => 'User tidak ditemukan'
        ], 404);
    }

    public function update(Request $request, $id)
    {
        // Cari user berdasarkan id_user (Primary Key)
        $user = User::find($id);
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        // Ambil semua input
        $data = $request->all();

        // Logika Password: Hanya update jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']); // Hapus dari array agar tidak terupdate jadi null
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diperbarui',
            'data'    => $user
        ]);
    }
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