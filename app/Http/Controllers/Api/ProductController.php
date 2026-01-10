<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar produk dengan pencarian dan pagination.
     */
    public function index(Request $request)
{
    $query = Product::query();

    // 🔍 Search global
    if ($search = $request->search) {
        $query->where(function ($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('kategori', 'like', "%{$search}%");
        });
    }

    // 🎚️ Filter tambahan
    if ($request->filled('filter.kategori')) {
        $query->where('kategori', $request->filter['kategori']);
    }

    if ($request->filled('filter.satuan_pack')) {
        $query->where('satuan_pack', $request->filter['satuan_pack']);
    }

    // 🧾 Ambil semua kolom supaya aman dipakai di mana saja
    $products = $query->orderBy('id', 'DESC')->paginate(10);

    return response()->json([
        'success' => true,
        'data' => $products
    ]);
}

    /**
     * Mendapatkan informasi stok untuk produk tertentu.
     */
    public function getStock($id)
    {
        $product = Product::with(['purchaseItems', 'stockOuts'])->findOrFail($id);
        
        // Hitung stok tersedia
        $masuk = $product->purchaseItems->sum('jumlah_pack');
        $keluar = $product->stockOuts->sum('jumlah_pack');
        $stok_tersedia = max(0, $masuk - $keluar);
        
        return response()->json([
            'success' => true,
            'data' => [
                'product_id' => $product->id,
                'product_name' => $product->nama,
                'available_stock' => $stok_tersedia
            ]
        ]);
    }
 
    /**
     * Menghapus produk (soft/hard delete sesuai kebutuhan).
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus'
        ]);
    }
}