<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Mengambil data pengguna dengan pagination dan pencarian.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Implementasi Pencarian Global
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Implementasi Filter berdasarkan status verifikasi email
        if ($request->has('email_verified')) {
            $emailVerified = $request->input('email_verified');
            if ($emailVerified === 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($emailVerified === 'not_verified') {
                $query->whereNull('email_verified_at');
            }
        }

        // Menggunakan paginate() untuk mendapatkan data dengan struktur pagination
        $users = $query->latest()->paginate(10); // 10 data per halaman

        // Format data untuk response
        $users->getCollection()->transform(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at 
                    ? $user->email_verified_at->format('d-m-Y H:i')
                    : 'Belum diverifikasi',
                'created_at' => $user->created_at->format('d-m-Y H:i'),
                // 'actions' => view('components.user-actions', ['user' => $user])->render()
            ];
        });

        // Kembalikan data dalam format JSON
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Hapus data pengguna
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            // Cari user berdasarkan ID
            $user = User::findOrFail($id);

            // Prevent deleting yourself
            if ($user->id === Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus akun sendiri'
                ], 422);
            }

            // Prevent deleting if this is the only admin
            $adminCount = User::count();
            if ($adminCount <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus satu-satunya admin'
                ], 422);
            }

            $user->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus pengguna',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}