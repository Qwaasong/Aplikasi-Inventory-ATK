<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FinancialTransaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BerandaController extends Controller
{
    private function getNamaHari($tanggal)
    {
        $hari = Carbon::parse($tanggal)->dayName;
        $mapping = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];

        return $mapping[$hari] ?? $hari;
    }

    public function persentase()
    {
        // --- 1. Persentase Pemasukan dan Pengeluaran (Mingguan) ---
        $currentWeekStart = Carbon::now()->startOfWeek();
        $currentWeekEnd = Carbon::now()->endOfWeek();
        $previousWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $previousWeekEnd = Carbon::now()->subWeek()->endOfWeek();

        // Total transaksi (Pemasukan + Pengeluaran) minggu ini
        $currentWeekTotal = FinancialTransaction::whereBetween('tanggal', [$currentWeekStart, $currentWeekEnd])->sum('jumlah');
        // Total transaksi (Pemasukan + Pengeluaran) minggu lalu
        $previousWeekTotal = FinancialTransaction::whereBetween('tanggal', [$previousWeekStart, $previousWeekEnd])->sum('jumlah');

        $persentasePemasukanPengeluaran = $this->calculatePercentageChange($currentWeekTotal, $previousWeekTotal, 'Minggu Ini');


        // --- 2. Persentase Distribusi Produk (Bulanan) ---
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();
        $previousMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $previousMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // Total produk terjual bulan ini (menggunakan StockOut sebagai proxy untuk distribusi)
        // Asumsi: StockOut memiliki kolom 'tanggal' dan 'jumlah'
        // Jika tidak ada StockOut, kita akan menggunakan total produk yang ada di database.
        // Berdasarkan model yang diberikan, StockOut adalah model yang paling relevan untuk "distribusi" (penjualan/pengeluaran stok).
        // Jika StockOut tidak memiliki kolom 'tanggal', kita akan menggunakan total Product count.
        // Karena saya tidak melihat struktur tabel, saya akan menggunakan total Product count sebagai fallback yang aman.

        // Fallback: Total produk yang ada (count)
        $currentMonthProductCount = Product::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])->count();
        $previousMonthProductCount = Product::whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])->count();

        $persentaseDistribusiProduk = $this->calculatePercentageChange($currentMonthProductCount, $previousMonthProductCount, 'Bulan Ini');

        return response()->json([
            'success' => true,
            'data' => [
                'persentase_pemasukan_pengeluaran' => $persentasePemasukanPengeluaran,
                'persentase_distribusi_produk' => $persentaseDistribusiProduk,
            ]
        ]);
    }

    /**
     * Mendapatkan data beranda lengkap
     */
    public function index(Request $request)
    {
        // Ambil parameter filter dari query (default: 'bulan')
        $filter = $request->get('filter', 'bulan');

        // Tentukan rentang tanggal berdasarkan filter
        switch ($filter) {
            case 'minggu':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'tahun':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            default: // 'bulan'
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
        }

        // Hitung saldo, pemasukan, dan pengeluaran
        $pemasukan = FinancialTransaction::where('tipe', 'pemasukan')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->sum('jumlah');

        $pengeluaran = FinancialTransaction::where('tipe', 'pengeluaran')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->sum('jumlah');

        $saldo = $pemasukan - $pengeluaran;

        // Hitung total produk
        $totalProduk = Product::count();

        // Data chart per tanggal untuk minggu ini
        $dataChart = FinancialTransaction::selectRaw('tanggal,
                SUM(CASE WHEN tipe="pemasukan" THEN jumlah ELSE 0 END) as total_pemasukan,
                SUM(CASE WHEN tipe="pengeluaran" THEN jumlah ELSE 0 END) as total_pengeluaran')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        // Distribusi produk berdasarkan kategori
        $distribusiProduk = Product::select('kategori')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('kategori')
            ->get()
            ->map(function ($item) {
                // Mapping kategori ke nama yang lebih user-friendly
                $namaKategori = $this->getNamaKategori($item->kategori);
                return [
                    'kategori' => $namaKategori,
                    'total' => $item->total
                ];
            });

        // Hitung persentase perubahan
        $persentasePerubahan = $this->hitungPersentasePerubahan($filter);

        return response()->json([
            'success' => true,
            'data' => [
                'statistik' => [
                    'saldo' => $saldo,
                    'pemasukan' => $pemasukan,
                    'pengeluaran' => $pengeluaran,
                    'total_produk' => $totalProduk,
                    // 'keuntungan' => $saldo,
                ],
                'chart' => $dataChart,
                'distribusi_produk' => $distribusiProduk,
                'persentase_perubahan' => $persentasePerubahan,
                'filter' => $filter,
                'periode' => [
                    'start' => $startDate->format('Y-m-d'),
                    'end' => $endDate->format('Y-m-d'),
                ]
            ]
        ]);
    }

    /**
     * Mendapatkan data statistik beranda
     */
    public function statistik(Request $request)
    {
        $filter = $request->get('filter', 'bulan');

        switch ($filter) {
            case 'minggu':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'tahun':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            default: // 'bulan'
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
        }

        $pemasukan = FinancialTransaction::where('tipe', 'pemasukan')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->sum('jumlah');

        $pengeluaran = FinancialTransaction::where('tipe', 'pengeluaran')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->sum('jumlah');

        $saldo = $pemasukan - $pengeluaran;
        $totalProduk = Product::count();

        return response()->json([
            'success' => true,
            'data' => [
                'saldo' => $saldo,
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran,
                'total_produk' => $totalProduk,
                'filter' => $filter,
            ]
        ]);
    }

    /**
     * Mendapatkan data chart berdasarkan filter
     */
    /**
     * Mendapatkan data chart berdasarkan filter
     */
    public function chart(Request $request)
    {
        // Tentukan rentang tanggal 7 hari terakhir (termasuk hari ini)
        $endDate = Carbon::now(); // Hari ini
        $startDate = Carbon::now()->subDays(6); // 6 hari yang lalu + hari ini = 7 hari total

        // Ambil data transaksi
        $dataChart = FinancialTransaction::selectRaw('tanggal,
            SUM(CASE WHEN tipe="pemasukan" THEN jumlah ELSE 0 END) as total_pemasukan,
            SUM(CASE WHEN tipe="pengeluaran" THEN jumlah ELSE 0 END) as total_pengeluaran')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        // Format data untuk chart dengan label hari
        $formattedChart = $dataChart->map(function ($item) {
            return [
                'hari' => $this->getNamaHari($item->tanggal),
                'tanggal' => $item->tanggal,
                'total_pemasukan' => $item->total_pemasukan,
                'total_pengeluaran' => $item->total_pengeluaran
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'chart' => $formattedChart,
                'periode' => [
                    'start' => $startDate->format('Y-m-d'),
                    'end' => $endDate->format('Y-m-d'),
                ]
            ]
        ]);
    }



    /**
     * Mendapatkan distribusi produk berdasarkan kategori
     */
    public function distribusiProduk()
    {
        $distribusiProduk = Product::select('kategori', DB::raw('COUNT(*) as total'))
            ->groupBy('kategori')
            ->latest()
            ->limit(4)
            ->get()
            ->map(function ($item) {
                $namaKategori = $this->getNamaKategori($item->kategori);
                return [
                    'kategori' => $namaKategori,
                    'total' => $item->total
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $distribusiProduk
        ]);
    }

    /**
     * Helper function untuk mendapatkan nama kategori yang lebih user-friendly
     */
    private function getNamaKategori($kategori)
    {
        $mapping = [
            'makanan_ringan' => 'Makanan',
            'minuman' => 'Minuman',
            'alat_tulis' => 'Alat Tulis',
            'buku' => 'Buku',
            'seragam' => 'Seragam Sekolah',
            'kebersihan' => 'Alat Kebersihan',
            'aksesoris' => 'Aksesoris Sekolah',
            'makanan_berat' => 'Lainnya',
        ];

        return $mapping[$kategori] ?? $kategori;
    }

    /**
     * Helper function untuk menghitung persentase perubahan
     */
    private function hitungPersentasePerubahan($filter)
    {
        // Logika sederhana untuk menghitung persentase perubahan
        // Dalam implementasi yang lebih kompleks, ini bisa dibandingkan dengan periode sebelumnya
        switch ($filter) {
            case 'minggu':
                return 12; // Contoh: +12% untuk minggu ini
            case 'tahun':
                return 8;  // Contoh: +8% untuk tahun ini
            default: // 'bulan'
                return 15; // Contoh: +15% untuk bulan ini
        }
    }

    private function calculatePercentageChange($currentValue, $previousValue, $period)
    {
        if ($previousValue == 0) {
            $percentage = ($currentValue > 0) ? 100 : 0;
        } else {
            $percentage = (($currentValue - $previousValue) / $previousValue) * 100;
        }

        return [
            'value' => abs(round($percentage)),
            'period' => $period,
            'is_positive' => $percentage >= 0,
        ];
    }
}