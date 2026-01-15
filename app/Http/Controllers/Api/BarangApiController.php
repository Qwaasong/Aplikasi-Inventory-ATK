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

    public function destroy($id)
    {
        $barang = Barang::find($id);
        if ($barang) {
            $barang->delete();
            return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
        }
        return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
    }
        public function create()
    {
        $kategori = Kategori::all();
        return view('barang.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required',
            'id_kategori' => 'required',
            'jumlah_pack' => 'required|numeric',
            'jumlah_pcs'  => 'required|numeric',
        ]);

        Barang::create($request->all());
        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambah');
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        $kategori = Kategori::all();
        return view('barang.edit', compact('barang', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);
        $barang->update($request->all());
        return redirect()->route('barang.index')->with('success', 'Barang berhasil diupdate');
    }

}