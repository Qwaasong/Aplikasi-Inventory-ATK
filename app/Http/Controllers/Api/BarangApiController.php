<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
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