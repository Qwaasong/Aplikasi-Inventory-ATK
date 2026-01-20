<?php
namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\RiwayatStok; // Gunakan model
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function barangMasuk(Request $request, $id)
    {
        $request->validate([
            'tambah_pack' => 'required|numeric|min:1',
            'tambah_pcs'  => 'required|numeric|min:0',
        ]);

       

        return redirect()->back()->with('success', 'Stok barang masuk berhasil dicatat');
    }

    public function barangKeluar(Request $request, $id)
    {
        $request->validate([
            'kurang_pack' => 'required|numeric|min:1',
        ]);

       

        return redirect()->back()->with('success', 'Stok barang keluar berhasil dicatat');
    }
}