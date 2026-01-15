<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    // Digunakan oleh ROLE 02
    public function barangMasuk(Request $request, $id)
    {
        $request->validate([
            'tambah_pack' => 'required|numeric|min:1',
            'tambah_pcs'  => 'required|numeric|min:0',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->increment('jumlah_pack', $request->tambah_pack);
        $barang->increment('jumlah_pcs', $request->tambah_pcs);

        return redirect()->back()->with('success', 'Stok barang masuk berhasil dicatat');
    }

    // Digunakan oleh ROLE 03
    public function barangKeluar(Request $request, $id)
    {
        $request->validate([
            'kurang_pack' => 'required|numeric|min:1',
        ]);

        $barang = Barang::findOrFail($id);
        
        // Cek apakah stok cukup sebelum dikurangi
        if ($barang->jumlah_pack < $request->kurang_pack) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi');
        }

        $barang->decrement('jumlah_pack', $request->kurang_pack);
        return redirect()->back()->with('success', 'Stok barang keluar berhasil dicatat');
    }
}