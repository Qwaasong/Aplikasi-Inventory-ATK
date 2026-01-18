<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BarangApiController extends Controller
{
    /**
     * Tampilkan daftar barang dengan fitur pencarian dan paginasi.
     */
    public function index(Request $request)
    {
        // 1. Ambil parameter dari request
        $perPage = $request->get('per_page', 10); // Default 10 data per halaman
        $search = $request->get('search', '');

        // 2. Query data barang dengan relasi kategori
        $barang = Barang::with('kategori')
            // Logika Pencarian
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    // Cari berdasarkan nama barang
                    $q->where('nama_barang', 'like', '%' . $search . '%')
                        // ATAU cari berdasarkan nama kategori (relasi)
                        ->orWhereHas('kategori', function ($qKategori) use ($search) {
                            $qKategori->where('nama_kategori', 'like', '%' . $search . '%');
                        });
                });
            })
            ->latest() // Urutkan dari yang terbaru
            ->paginate($perPage); // Gunakan paginate, bukan get()

        // 3. Kembalikan respon JSON
        return response()->json([
            'success' => true,
            'data'    => $barang
        ]);
    }

    public function show($id)
    {
        $barang = Barang::with('kategori')->find($id);
        if ($barang) {
            return response()->json(['success' => true, 'data' => $barang]);
        }
        return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required',
            'id_kategori' => 'required',
            'jumlah_pack' => 'required|numeric',
            'jumlah_pcs'  => 'required|numeric',
        ]);

        $barang = Barang::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil ditambah',
            'data'    => $barang
        ]);
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);
        $barang->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil diupdate'
        ]);
    }

    public function destroy($id)
    {
        $barang = Barang::find($id);
        if ($barang) {
            $barang->delete();
            return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
        }
        return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
    }

    /**
     * Kurangi stok barang (Barang Keluar)
     */
    public function keluar(Request $request)
    {
        $request->validate([
            'id_barang' => 'required|exists:barang,id_barang', // Pastikan ID valid
            'jumlah_pcs' => 'required|numeric|min:1',
        ]);

        $barang = Barang::find($request->id_barang);

        // Cek stok
        if ($barang->jumlah_pcs < $request->jumlah_pcs) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi! Sisa stok: ' . $barang->jumlah_pcs
            ], 400);
        }

        // Kurangi stok
        $barang->jumlah_pcs -= $request->jumlah_pcs;
        // Opsional: Sesuaikan jumlah_pack jika perlu, atau biarkan null/tidak berubah
        // $barang->jumlah_pack = floor($barang->jumlah_pcs / $barang->isi_per_pack); 

        $barang->save();

        return response()->json([
            'success' => true,
            'message' => 'Stok berhasil dikurangi',
            'data'    => $barang
        ]);
    }
}
