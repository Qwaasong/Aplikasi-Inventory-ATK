<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FinancialTransaction;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class FinancialTransactionController extends Controller
{
    /**
     * Menampilkan semua transaksi (masuk & keluar).
     */
    public function index(Request $request)
    {
        // 1. Tentukan jumlah item per halaman
        $perPage = 10;

        // 2. Ambil filter dari request
        $filterTipe = $request->input('filter.tipe');
        $startDate = $request->input('filter.start_date');
        $endDate = $request->input('filter.end_date');

        // 3. Buat query dasar
        $query = FinancialTransaction::query();

        // 4. Terapkan Filter
        if ($filterTipe) {
            $query->where('tipe', $filterTipe);
        }

        if ($startDate && $endDate) {
            $query->whereDate('tanggal', '>=', $startDate)
                ->whereDate('tanggal', '<=', $endDate);
        }

        // 5. Ambil data dan urutkan dari YANG PALING LAMA (ASC)
        // Ini PENTING untuk perhitungan Saldo (Running Balance) yang benar
        $transactions = $query->orderBy('created_at', 'asc')->get();

        // 6. Hitung saldo kumulatif (secara kronologis/maju)
        $runningBalance = 0;
        $formattedTransactions = [];

        foreach ($transactions as $transaction) {
            // Update running balance
            if ($transaction->tipe === 'pemasukan') {
                $runningBalance += $transaction->jumlah;
            } else {
                $runningBalance -= $transaction->jumlah;
            }

            // Ambil nilai mentah (raw) untuk Pemasukan/Pengeluaran
            $pemasukanRaw = $transaction->tipe === 'pemasukan' ? $transaction->jumlah : 0;
            $pengeluaranRaw = $transaction->tipe === 'pengeluaran' ? $transaction->jumlah : 0;

            // Format data per transaksi
            $formattedTransactions[] = [
                'id' => $transaction->id,
                'tanggal' => $transaction->tanggal, // Ini tetap tanggal transaksi
                'keterangan' => $transaction->keterangan,
                
                // --- PERUBAHAN DI SINI ---
                // Terapkan number_format untuk format Rupiah
                'pemasukan' => 'Rp ' . number_format($pemasukanRaw, 0, ',', '.'),
                'pengeluaran' => 'Rp ' . number_format($pengeluaranRaw, 0, ',', '.'),
                'saldo' => 'Rp ' . number_format($runningBalance, 0, ',', '.')
                // --- AKHIR PERUBAHAN ---
            ];
        }

        // 7. BALIK URUTAN ARRAY
        // Setelah saldo dihitung dengan benar (dari lama ke baru),
        // kita balik array-nya agar yang terbaru tampil di atas untuk display.
        $formattedTransactions = array_reverse($formattedTransactions);

        // 8. Buat paginasi manual dari array yang sudah dibalik
        $page = $request->input('page', 1);
        $offset = ($page - 1) * $perPage;
        $pagedItems = array_slice($formattedTransactions, $offset, $perPage);
        
        $paginator = new LengthAwarePaginator(
            $pagedItems,
            count($formattedTransactions),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // 9. Kembalikan data dalam format tabel yang kompatibel
        return response()->json([
            'data' => $paginator->items(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem()
        ]);
    }

    /**
     * Menghapus transaksi (opsional).
     * Jika dihapus, stok dikembalikan seperti semula.
     */
    // DENGAN FUNGSI INI
    public function destroy($id) // Terima $id (string/int)
    {
        try {
            // Cari transaksinya secara manual
            $financialTransaction = FinancialTransaction::findOrFail($id);

            // Hapus
            $financialTransaction->delete();

            return response()->json(['success' => true, 'message' => 'Transaksi berhasil dihapus.']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Ini jika ID-nya tidak ditemukan
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus transaksi.'], 500);
        }
    }

    // Ringkasan jumlah transaksi dalam minggu, bulan, dan tahun ini untuk chart
    public function summary()
    {
        $data = [
            'minggu' => FinancialTransaction::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'bulan' => FinancialTransaction::whereMonth('created_at', now()->month)->count(),
            'tahun' => FinancialTransaction::whereYear('created_at', now()->year)->count(),
        ];

        return response()->json(['success' => true, 'data' => $data]);
    }
}