<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Purchase; // Menggunakan model Purchase
use Illuminate\Http\Request;

class Barang_MasukController extends Controller
{
    /**
     * Tampilkan daftar Barang Masuk (Purchase) dengan paginasi dan pencarian.
     * Mirip dengan ProductController::index
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $filters = $request->get('filter', []);

        $purchases = Purchase::with('vendor') 
            ->when($search, function ($query, $search) {
                // Implementasi Pencarian Global
                $query->where(function ($q) use ($search) {
                    // 1. Cari berdasarkan No. Faktur (kolom Purchase)
                    $q->where('no_faktur', 'like', '%' . $search . '%')
                    
                    // 2. ATAU cari berdasarkan Nama Vendor (melalui relasi 'vendor')
                      ->orWhereHas('vendor', function ($qVendor) use ($search) {
                          $qVendor->where('nama_vendor', 'like', '%' . $search . '%'); // Asumsi kolom nama vendor adalah 'nama'
                      });
                });
            })
            // LOGIKA FILTER BARU BERDASARKAN TANGGAL/BULAN/TAHUN
            // 1. Filter berdasarkan Rentang Tanggal
            ->when(isset($filters['tanggal_awal']) && $filters['tanggal_awal'] && isset($filters['tanggal_akhir']) && $filters['tanggal_akhir'], function ($query) use ($filters) {
                // Pastikan kolom tanggal adalah 'tanggal' di tabel purchases
                $query->whereBetween('tanggal', [$filters['tanggal_awal'], $filters['tanggal_akhir']]);
            })
            // 2. Filter berdasarkan Bulan
            ->when(isset($filters['bulan']) && $filters['bulan'], function ($query) use ($filters) {
                // Asumsi kolom tanggal adalah 'tanggal'
                $query->whereMonth('tanggal', $filters['bulan']);
            })
            // 3. Filter berdasarkan Tahun
            ->when(isset($filters['tahun']) && $filters['tahun'], function ($query) use ($filters) {
                // Asumsi kolom tanggal adalah 'tanggal'
                $query->whereYear('tanggal', $filters['tahun']);
            })
            ->latest()
            ->paginate($perPage);

        return response()->json($purchases);
    }
    
    /**
     * Hapus satu Barang Masuk (Purchase) dari database.
     * Mirip dengan ProductController::destroy
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $purchase = Purchase::findOrFail($id);
        
        // Catatan: Logika kompleks (stok/transaksi) harus ditangani di sini.
        // Untuk saat ini, kita biarkan sederhana dengan hanya menghapus data Purchase utama.
        $purchase->delete();

        return response()->json([
            'message' => 'Barang Masuk berhasil dihapus.',
        ], 200);
    }
}