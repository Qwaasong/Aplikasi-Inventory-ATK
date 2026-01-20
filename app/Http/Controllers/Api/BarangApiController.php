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
        'jumlah_pcs'  => 'required|numeric', // Kapasitas per pack
    ]);

    try {
        return DB::transaction(function () use ($request) {
            // MEMBUAT VARIABEL TOTAL_PCS
            $total_pcs = $request->jumlah_pack * $request->jumlah_pcs;

            // Gabungkan input awal dengan total_pcs yang baru dihitung
            $data = $request->all();
            $data['total_pcs'] = $total_pcs;

            $barang = Barang::create($data);

            RiwayatStok::create([
                'id_barang'  => $barang->id_barang,
                'jenis'      => 'masuk',
                'jumlah_pcs' => $total_pcs, // Riwayat mencatat total butiran masuk
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil disimpan',
                'data'    => $barang
            ]);
        });
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

   public function update(Request $request, $id)
{
    $barang = Barang::findOrFail($id);
    
    // Hitung ulang variabel total_pcs berdasarkan input baru
    $total_pcs = $request->jumlah_pack * $request->jumlah_pcs;

    $data = $request->all();
    $data['total_pcs'] = $total_pcs;

    $barang->update($data);

    return response()->json([
        'success' => true,
        'message' => 'Barang berhasil diupdate',
        'data'    => $barang
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
        'id_barang' => 'required|exists:barang,id_barang',
        'jumlah_pcs_keluar' => 'required|integer|min:1',
    ]);

    return DB::transaction(function () use ($request) {
        $barang = Barang::findOrFail($request->id_barang);

        // 1. Kapasitas per pack (statis)
        $kapasitas = (int) $barang->jumlah_pcs;

        // 2. Cek stok pada kolom total_pcs
        if ($request->jumlah_pcs_keluar > $barang->total_pcs) {
            return response()->json([
                'success' => false,
                'message' => "Stok tidak cukup! Sisa: $barang->total_pcs Pcs"
            ], 400);
        }

        // 3. Hitung Sisa Total PCS
        $sisaTotalPcs = $barang->total_pcs - $request->jumlah_pcs_keluar;

        // 4. Hitung Sisa Pack (BULAT KE BAWAH)
        // Jika sisa 115 pcs dan kapasitas 12, maka 115/12 = 9.58 -> Jadi 9 Pack bulat.
        $sisaPackBulat = floor($sisaTotalPcs / $kapasitas);

        // 5. Update Database
        $barang->update([
            'total_pcs'   => $sisaTotalPcs,
            'jumlah_pack' => $sisaPackBulat, 
            // jumlah_pcs tetap statis
        ]);

        // 6. Simpan Riwayat
        RiwayatStok::create([
            'id_barang'  => $barang->id_barang,
            'jenis'      => 'keluar',
            'jumlah_pcs' => $request->jumlah_pcs_keluar,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Stok berhasil dikurangi',
            'data'    => [
                'sisa_total_pcs' => $sisaTotalPcs,
                'sisa_pack_utuh' => $sisaPackBulat
            ]
        ]);
    });
}

}
