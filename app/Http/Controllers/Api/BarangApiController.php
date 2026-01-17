<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BarangApiController extends Controller
{
    public function index()
    {
        // Mengambil semua barang beserta data kategorinya
        $barang = Barang::with('kategori')->get();
        return response()->json([
            'success' => true,
            'data'    => $barang
        ]);
    }

    // Method baru untuk ambil 1 data (digunakan saat klik tombol Edit di modal)
    public function show($id)
    {
        $barang = Barang::find($id);
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
}