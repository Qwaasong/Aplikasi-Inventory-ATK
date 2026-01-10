<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StockOut;
use Illuminate\Http\Request;

class Barang_KeluarController extends Controller
{
    /**
     * Menampilkan data barang keluar dengan pagination dan pencarian.
     */
    public function index(Request $request)
    {
        $query = StockOut::with('product'); // relasi ke product jika ada
        $filters = $request->get('filter', []);

        // Pencarian global
        if ($search = $request->input('search')) {
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            })
            ->orWhere('keterangan', 'like', "%{$search}%");
        }

        // Filter tambahan (opsional)
        //if ($filters = $request->input('filter')) {
        //    $query->filter($filters); // hanya jika kamu punya scopeFilter di model
        //}

        // START: PENAMBAHAN FILTER TANGGAL, BULAN, TAHUN (Kode Baru)
        
        // 1. Filter berdasarkan Rentang Tanggal (Tanggal Awal dan Akhir)
        if (isset($filters['tanggal_awal']) && $filters['tanggal_awal'] && isset($filters['tanggal_akhir']) && $filters['tanggal_akhir']) {
            // Menggunakan kolom 'created_at' untuk rentang tanggal. Ubah jika nama kolom berbeda.
            $query->whereBetween('created_at', [$filters['tanggal_awal'], $filters['tanggal_akhir']]);
        }
        
        // 2. Filter berdasarkan Bulan
        if (isset($filters['bulan']) && $filters['bulan']) {
            $query->whereMonth('created_at', $filters['bulan']);
        }
        
        // 3. Filter berdasarkan Tahun
        if (isset($filters['tahun']) && $filters['tahun']) {
            $query->whereYear('created_at', $filters['tahun']);
        }
        // END: PENAMBAHAN FILTER TANGGAL, BULAN, TAHUN

        // Pagination
        $stockOuts = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $stockOuts,
        ]);
    }

    

    /**
     * Menghapus data barang keluar (opsional untuk AJAX Delete)
     */
    public function destroy($id)
    {
        try {
            $data = StockOut::findOrFail($id);
            $data->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data barang keluar berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data barang keluar',
                'error' => $e->getMessage()
            ]);
        }
    }
}
