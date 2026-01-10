<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class StockController extends Controller
{
    /**
     * Menampilkan daftar stok terkini semua produk.
     * Hitungan stok = total masuk - total keluar
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // 🔍 Search global
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        // 🎚️ Filter tambahan
        if ($request->filled('filter.kategori')) {
            $query->where('kategori', $request->filter['kategori']);
        }

        // For stock filtering, we'll add a simple approach that works with the data structure
        // Note: This is a simplified approach since stock calculation is complex in SQL
        // The client-side filtering will handle actual stock value filtering
        // Just make sure the API accepts the filter parameters

        // Ambil semua produk dengan relasi pembelian & stok keluar
        $products = $query->with(['purchaseItems', 'stockOuts'])->get();

        // Hitung stok terkini untuk setiap produk
        $data = $products->map(function ($product) {
            $masuk = $product->purchaseItems->sum('jumlah_pack');
            $keluar = $product->stockOuts->sum('jumlah_pack');
            $stok_terkini = max(0, $masuk - $keluar);

            return [
                'id' => $product->id,
                'nama' => $product->nama,
                'kategori' => $product->kategori,
                'stok_masuk' => $masuk,
                'stok_keluar' => $keluar,
                'stok_tersedia' => $stok_terkini,
                'satuan' => $product->satuan_pack,
            ];
        });

        // Apply client-side filtering if needed (this is handled by the table component)
        // For now, we'll just pass all data and let the frontend handle filtering
        // But we should add a simple stock range filter for basic functionality

        // Simple stock range filtering (minimum stock threshold)
        if ($request->filled('filter.stock')) {
            $minStock = intval($request->filter['stock']);
            $data = $data->filter(function ($item) use ($minStock) {
                return $item['stok_tersedia'] >= $minStock;
            });
        }

        $page = Paginator::resolveCurrentPage('page');
        $perPage = 10;
        $total = $data->count();
        $results = $data->sortByDesc('id')->forPage($page, $perPage);

        $data = new LengthAwarePaginator($results, $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);

        // Konversi paginator ke array yang sesuai format
        $responseData = $data->toArray();
        
        // Ensure the data is properly formatted as an array with numeric keys
        $dataArray = is_array($responseData['data']) ? array_values($responseData['data']) : [];
        
        return response()->json([
            'success' => true,
            'data' => [
                'data' => $dataArray, // Ensure it's a numerically indexed array
                'current_page' => $responseData['current_page'],
                'last_page' => $responseData['last_page'],
                'per_page' => $responseData['per_page'],
                'from' => $responseData['from'],
                'to' => $responseData['to'],
                'total' => $responseData['total'],
            ],
        ]);
    }
}
