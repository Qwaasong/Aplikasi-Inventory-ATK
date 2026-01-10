<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Mengambil data vendor dengan pagination dan pencarian.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Vendor::query();

        // Implementasi Pencarian Global
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_vendor', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%")
                    ->orWhere('no_telp', 'like', "%{$search}%");
            });
        }

        // Implementasi Filter Lanjutan dari Form
        if ($filters = $request->input('filter')) {
            $query->filter($filters); // Memanggil scopeFilter yang ada di model
        }

        // Menggunakan paginate() untuk mendapatkan data dengan struktur pagination
        $vendors = $query->latest()->paginate(10); // 10 data per halaman

        // Kembalikan data dalam format JSON
        return response()->json([
            'success' => true,
            // $vendors sudah berisi data dan meta pagination (current_page, last_page, dll)
            'data' => $vendors
        ]);
    }

    // Tambahkan method destroy() untuk AJAX Delete (Opsional)
    public function destroy($id)
    {
        try {
            $data = Vendor::findOrFail($id);
            $data->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data',
                'error' => $e->getMessage()
            ]);
        }
    }
}