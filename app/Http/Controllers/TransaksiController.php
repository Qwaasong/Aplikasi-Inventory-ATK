<?php
namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\RiwayatStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function barangMasuk(Request $request, $id)
    {
        $request->validate([
            'tambah_pack' => 'required|numeric|min:1',
            'tambah_pcs'  => 'required|numeric|min:0',
        ]);

        try {
            return DB::transaction(function () use ($request, $id) {
                $barang = Barang::findOrFail($id);
                
                // Hitung total pcs yang akan ditambahkan
                $total_pcs_tambahan = ($request->tambah_pack * $barang->jumlah_pcs) + $request->tambah_pcs;
                
                // Update stok barang
                $barang->jumlah_pack += $request->tambah_pack;
                $barang->total_pcs += $total_pcs_tambahan;
                $barang->save();
                
                // Catat di riwayat stok
                RiwayatStok::create([
                    'id_barang' => $barang->id_barang,
                    'jenis' => 'masuk',
                    'jumlah_pcs' => $total_pcs_tambahan,
                    'keterangan' => 'Barang masuk melalui transaksi manual'
                ]);

                return redirect()->back()->with('success', 'Stok barang masuk berhasil dicatat');
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mencatat stok masuk: ' . $e->getMessage());
        }
    }

    public function barangKeluar(Request $request, $id)
    {
        $request->validate([
            'kurang_pack' => 'required|numeric|min:1',
        ]);

        try {
            return DB::transaction(function () use ($request, $id) {
                $barang = Barang::findOrFail($id);
                
                // Hitung total pcs yang akan dikurangi
                $total_pcs_kurang = $request->kurang_pack * $barang->jumlah_pcs;
                
                // Validasi stok cukup
                if ($barang->total_pcs < $total_pcs_kurang) {
                    return redirect()->back()->with('error', 
                        'Stok tidak cukup! Stok tersedia: ' . $barang->total_pcs . ' pcs, 
                         Dibutuhkan: ' . $total_pcs_kurang . ' pcs');
                }
                
                // Update stok barang
                $barang->jumlah_pack -= $request->kurang_pack;
                $barang->total_pcs -= $total_pcs_kurang;
                $barang->save();
                
                // Catat di riwayat stok
                RiwayatStok::create([
                    'id_barang' => $barang->id_barang,
                    'jenis' => 'keluar',
                    'jumlah_pcs' => $total_pcs_kurang,
                    'keterangan' => 'Barang keluar melalui transaksi manual'
                ]);

                return redirect()->back()->with('success', 'Stok barang keluar berhasil dicatat');
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mencatat stok keluar: ' . $e->getMessage());
        }
    }
}