<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem; 
use App\Models\Product; 
use App\Models\FinancialTransaction; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 

class Barang_MasukController extends Controller
{
    /**
     * Tampilkan halaman index Barang Masuk
     */
    public function index()
    {
        return view('barang_masuk.index');
    }

    /**
     * Tampilkan halaman create Barang Masuk
     */
    public function create()
    {
        // Dalam implementasi nyata, perlu memuat daftar Vendor dan Product untuk form
        return view('barang_masuk.store');
    }

    /**
     * Simpan Barang Masuk baru ke database, termasuk item-itemnya, dan update stok.
     */
    public function store(Request $request)
    {
        // 1. Validasi Data Utama dan Item Pembelian
        $request->validate([
            'tanggal' => 'required|date',
            'vendor_id' => 'required|integer|exists:vendors,id', 
            'no_faktur' => 'nullable|string|max:255|unique:purchases,no_faktur',
            'keterangan' => 'nullable|string|max:1000',
            
            // Validasi array item
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.jumlah_pack' => 'required|integer|min:1',
            'items.*.harga_beli' => 'required|numeric|min:0',
            'items.*.harga_jual' => 'required|numeric|min:0', 
        ]);
        
        // Memastikan semua operasi database berjalan sukses (atomik)
        DB::beginTransaction();

        try {
            // 2. Hitung Total Biaya Pembelian
            $totalBiaya = collect($request->items)->sum(function ($item) {
                return $item['jumlah_pack'] * $item['harga_beli']; 
            });

            // 3. Simpan Header Pembelian (Purchase)
            $purchase = Purchase::create(array_merge(
                $request->only(['tanggal', 'vendor_id', 'no_faktur', 'keterangan']),
                ['total_biaya' => $totalBiaya] // Tambahkan total biaya ke Purchase
            ));

            // 4. Simpan Detail Item Pembelian (PurchaseItem)
            // Lakukan pemanggilan createMany HANYA SEKALI!
            // Model Event di PurchaseItem akan otomatis mengupdate stok (increment)
            $purchase->items()->createMany($request->items);

            // 5. Buat entri di Pembukuan Transaksi (FinancialTransaction) sebagai PENGELUARAN
            //FinancialTransaction::create([
            //    'tanggal' => $purchase->tanggal,
            //    'tipe' => 'pengeluaran',
            //    'keterangan' => 'Biaya Pembelian Barang Masuk: ' . $purchase->no_faktur ?? $purchase->id,
            //    'jumlah' => $totalBiaya, // Gunakan total biaya yang sudah dihitung
            //    'purchase_id' => $purchase->id,
            //    'stock_out_id' => null,
            //]);

            // 6. Commit Transaksi setelah semua langkah berhasil
            DB::commit();

            $message = 'Barang Masuk berhasil disimpan.';
            
            // Logika Simpan dan Buat Lagi
            if ($request->has('save_and_create')) {
                return redirect()->route('barang_masuk.create')->with('success', $message);
            }

            return redirect()->route('barang_masuk.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            // Anda bisa log $e->getMessage() untuk debugging
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan Barang Masuk: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan halaman edit Barang Masuk.
     */
    public function edit($id)
    {
        // Memuat detail Purchase dan PurchaseItem terkait
        $purchase = Purchase::with('items')->findOrFail($id); 
        return view('barang_masuk.edit', compact('purchase'));
    }

    /**
     * Update data Barang Masuk yang sudah ada.
     */
    public function update(Request $request, $id)
    {
        // Muat Purchase beserta item-itemnya yang lama
        $purchase = Purchase::with('items')->findOrFail($id); 

        // 1. Validasi Data Utama dan Item Pembelian
        $request->validate([
            'tanggal' => 'required|date',
            'vendor_id' => 'required|integer|exists:vendors,id', 
            'no_faktur' => 'nullable|string|max:255|unique:purchases,no_faktur,' . $id,
            'keterangan' => 'nullable|string|max:1000',
            
            // Tambahkan validasi untuk item baru/yang diubah
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.jumlah_pack' => 'required|integer|min:1',
            'items.*.harga_beli' => 'required|numeric|min:0',
            'items.*.harga_jual' => 'required|numeric|min:0',
        ]);
        
        //DB::beginTransaction();

        try {
            // 2. Hitung Total Biaya Baru
            $newTotalBiaya = collect($request->items)->sum(function ($item) {
                return $item['jumlah_pack'] * $item['harga_beli']; 
            });

            // 3. Reversal Stok Lama dan Hapus Item Lama
            
            // Reversal Stok Lama (Kurangi stok produk berdasarkan jumlah item lama)
            foreach ($purchase->items as $item) {
                // Kurangi stok produk
                Product::where('id', $item->product_id)->decrement('stok', $item->jumlah_pack);
            }

            // Hapus semua item pembelian lama yang terkait
            $purchase->items()->delete(); 

            // 4. Update Header Pembelian (Purchase)
            $purchase->update(array_merge(
                $request->only(['tanggal', 'vendor_id', 'no_faktur', 'keterangan']),
                ['total_biaya' => $newTotalBiaya] // Update total biaya baru
            ));

            // 5. Simpan Detail Item Pembelian Baru (PurchaseItem) dan Update Stok Baru
            // Asumsi: Model Event di PurchaseItem akan otomatis meng-increment stok saat createMany() dipanggil.
            $purchase->items()->createMany($request->items);
            
            // 6. Update Entri di Pembukuan Transaksi (FinancialTransaction)
            // Cari FinancialTransaction yang terkait dengan Purchase ini
            $transaction = FinancialTransaction::where('purchase_id', $purchase->id)->first();
            
            if ($transaction) {
                // Update data transaksi keuangan dengan data baru
                $transaction->update([
                    'tanggal' => $purchase->tanggal,
                    'keterangan' => 'Biaya Pembelian Barang Masuk: ' . $purchase->no_faktur ?? $purchase->id,
                    'jumlah' => $newTotalBiaya, // Gunakan total biaya yang baru
                ]);
            } else {
                // Jika entri transaksi tidak ditemukan, buat yang baru
                FinancialTransaction::create([
                    'tanggal' => $purchase->tanggal,
                    'tipe' => 'pengeluaran',
                    'keterangan' => 'Biaya Pembelian Barang Masuk: ' . $purchase->no_faktur ?? $purchase->id,
                    'jumlah' => $newTotalBiaya,
                    'purchase_id' => $purchase->id,
                    'stock_out_id' => null,
                ]);
            }

            //DB::commit();

            return redirect()->route('barang_masuk.index')->with('success', 'Barang Masuk berhasil diperbarui, termasuk item dan transaksi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Barang Masuk: ' . $e->getMessage());
        }
    }
    
    /**
     * Hapus data Barang Masuk yang sudah ada.
     */
    public function destroy($id)
    {
        $purchase = Purchase::findOrFail($id);
        
        // PENTING: Jika menggunakan Model Event, hapus item secara manual
        // untuk memicu Model Event yang mengurangi stok.
        
        DB::beginTransaction();
        try {
            // Reversal stok (seperti di method update)
            foreach ($purchase->items as $item) {
                Product::where('id', $item->product_id)->decrement('stok', $item->jumlah_pack);
            }
            
            // Hapus Financial Transaction terkait
            FinancialTransaction::where('purchase_id', $purchase->id)->delete();
            
            // Hapus Purchase (ini akan menghapus PurchaseItem karena cascade delete di database, 
            // atau Anda harus melakukannya secara eksplisit jika tidak ada cascade)
            $purchase->delete();

            DB::commit();
            return redirect()->route('barang_masuk.index')->with('success', 'Barang Masuk berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus Barang Masuk: ' . $e->getMessage());
        }
    }
}