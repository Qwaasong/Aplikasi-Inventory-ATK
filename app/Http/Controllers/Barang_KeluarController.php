<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\FinancialTransaction;
use App\Models\StockOut;
use Illuminate\Http\Request;

class Barang_KeluarController extends Controller
{

    public function create()
    {
        // ambil semua produk
        $products = Product::select('id', 'nama')->orderBy('nama')->get();

        return view('barang_keluar.store', compact('products'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'product_id'      => 'required|exists:products,id',
            'jumlah_pack'     => 'required|integer|min:1',
            'tanggal'         => 'required|date',
            'keterangan'      => 'nullable|string|max:500',
        ]);

        // **LOGIKA BARU UNTUK TRANSAKSI KEUANGAN DIMULAI DI SINI**
        
        // 1. Ambil data produk untuk mendapatkan nama dan harga jual
        //    Asumsi: Field harga jual di model Product adalah 'harga_jual'.
        //    Anda harus memastikan nama field yang benar di model Product Anda.
        $product = Product::select('nama', 'harga_jual')->find($request->product_id);
        
        $hargaJual = $product->harga_jual ?? 0; // Pastikan menggunakan nama field yang benar
        $totalHarga = $request->jumlah_pack * $hargaJual;
        
        // 2. Catat data barang keluar
        StockOut::create($request->all());
        
        // 3. Catat data ke pembukuan transaksi sebagai Pemasukan
        //FinancialTransaction::create([
        //    'tanggal'    => $request->tanggal,
        //    'tipe'       => 'pemasukan',
        //    // Gunakan keterangan yang lebih informatif
        //    'keterangan' => 'Penjualan Produk: ' . $product->nama . '. Keterangan: ' . $request->keterangan,
        //    'jumlah'     => $totalHarga,
        //    // Opsional: Jika Anda memiliki foreign key 'stock_out_id' di tabel financial_transactions, 
        //    // Anda perlu mencatat StockOut terlebih dahulu, lalu mendapatkan ID-nya untuk field ini.
        //    // Saat ini, kita asumsikan tidak ada foreign key tersebut agar perubahan minimal.
        //]);

        // **LOGIKA BARU UNTUK TRANSAKSI KEUANGAN SELESAI DI SINI**

        return redirect()->route('barang_keluar.index')->with('success', 'Data barang keluar berhasil ditambahkan.');
    }

public function edit($id)
    {
        $stockOut = StockOut::findOrFail($id);
        $products = Product::select('id', 'nama')->orderBy('nama')->get();
        
        return view('barang_keluar.edit', compact('stockOut', 'products'));
    }

    public function update(Request $request, $id)
    {
        $stockOut = StockOut::findOrFail($id);

        $request->validate([
            'product_id'      => 'required|exists:products,id',
            'jumlah_pack'     => 'required|integer|min:1',
            'tanggal'         => 'required|date',
            'keterangan'      => 'nullable|string|max:500',
        ]);

        // 1. Perbarui data Barang Keluar (StockOut)
        $stockOut->update($request->all());

        // 2. Ambil data produk dan hitung total harga
        $product = Product::select('nama', 'harga_jual')->find($request->product_id);
        $hargaJual = $product->harga_jual ?? 0;
        $totalHarga = $request->jumlah_pack * $hargaJual;

        // --- Logika Update/Create Transaksi Keuangan ---
        
        // Data yang menjadi KUNCI untuk menemukan/menghubungkan transaksi
        $attributes = [
            'stock_out_id' => $stockOut->id,
            'tipe'         => 'pemasukan' // Tambahkan tipe sebagai atribut jika perlu
        ];

        // Data yang akan diperbarui/dibuat
        //$values = [
        //    'tanggal'      => $request->tanggal,
        //    'keterangan'   => 'Penjualan Produk: ' . ($product->nama ?? 'N/A') . '. Keterangan: ' . $request->keterangan,
        //    'jumlah'       => $totalHarga,
        //];
        
        // 3. Gunakan updateOrCreate: 
        // Mencari record dengan stock_out_id yang cocok. 
        // Jika ketemu, update dengan $values. Jika tidak, buat baru.
        //FinancialTransaction::updateOrCreate($attributes, $values);

        // --- Logika Pembaruan Transaksi Keuangan Selesai ---

        return redirect()->route('barang_keluar.index')->with('success', 'Data barang keluar berhasil diperbarui.');
    }

    
}
