<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\RiwayatStok;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'nama_pack'   => 'string|nullable',
            'jumlah_pcs'  => 'required|numeric',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                // 2. Simpan Barang
                $barang = Barang::create($request->all());

                // 3. Simpan ke Riwayat
                RiwayatStok::create([
                    'id_barang'  => $barang->id_barang, // Pastikan PK-nya benar
                    'jenis'      => 'masuk',
                    'jumlah_pcs' => $request->jumlah_pcs,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Barang dan Riwayat berhasil disimpan',
                    'data'    => $barang
                ]);
            });
        } catch (\Exception $e) {
            // Jika ada error, kirimkan pesan errornya agar kita tahu penyebabnya
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan: ' . $e->getMessage()
            ], 500);
        }
    }

   public function update(Request $request, $id)
    {
        // Catatan: Jika update mengubah 'jumlah_pcs', idealnya catat riwayat juga.
        // Namun untuk dashboard, fungsi 'store' dan 'keluar' adalah yang paling utama.
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
            $barang->delete(); // Riwayat_stok akan ikut terhapus jika di migrasi ada 'onDelete cascade'
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
            'id_barang'  => 'required|exists:barang,id_barang',
            'jumlah_pcs' => 'required|numeric|min:1',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $barang = Barang::findOrFail($request->id_barang);

                // Cek stok
                if ($barang->jumlah_pcs < $request->jumlah_pcs) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak mencukupi! Sisa: ' . $barang->jumlah_pcs
                    ], 400);
                }

                // 1. Kurangi stok di tabel barang
                $barang->decrement('jumlah_pcs', $request->jumlah_pcs);

                // 2. CATAT RIWAYAT KELUAR
                RiwayatStok::create([
                    'id_barang'  => $barang->id_barang,
                    'jenis'      => 'keluar',
                    'jumlah_pcs' => $request->jumlah_pcs,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Stok berhasil dikurangi dan dicatat di riwayat',
                    'data'    => $barang
                ]);
            });
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


}
