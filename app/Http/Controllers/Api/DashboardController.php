<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\User;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\RekapBarangExport;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil data ringkasan (Cards di atas)
        $totalPack = Barang::sum('jumlah_pack');
        $totalPcs = Barang::sum('jumlah_pcs');
        $totalUser = User::count();

        // 2. Logika Filter Statistik (Mingguan/Bulanan/Tahunan)
        $filter = $request->get('filter', 'mingguan');
        $statistik = $this->getStatistikData($filter);

        // 3. Data Distribusi Barang (Grafik lingkaran/batang kanan)
        $distribusiBarang = Kategori::withCount('barang')
            ->get()
            ->map(function ($kat) {
                return [
                    'label' => $kat->nama_kategori,
                    'jumlah' => $kat->barang_count
                ];
            });

        return response()->json([
            'success' => true,
            'summary' => [
                'total_pack' => $totalPack,
                'total_pcs'  => $totalPcs,
                'total_user' => $totalUser,
            ],
            'charts' => [
                'statistik_masuk_keluar' => $statistik,
                'distribusi_barang' => $distribusiBarang
            ]
        ]);
    }

    private function getStatistikData($filter)
    {
        $labels = [];
        $dataMasuk = [];
        $dataKeluar = [];

        \Carbon\Carbon::setLocale('id');

        if ($filter == 'mingguan') {
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $labels[] = $date->translatedFormat('l');
                
                $dataMasuk[] = (int) DB::table('riwayat_stok')
                    ->where('jenis', 'masuk')
                    ->whereDate('created_at', $date->toDateString())
                    ->sum('jumlah_pcs');

                $dataKeluar[] = (int) DB::table('riwayat_stok')
                    ->where('jenis', 'keluar')
                    ->whereDate('created_at', $date->toDateString())
                    ->sum('jumlah_pcs');
            }
        } elseif ($filter == 'bulanan') {
            for ($i = 3; $i >= 0; $i--) {
                $start = Carbon::now()->subWeeks($i)->startOfWeek();
                $end = Carbon::now()->subWeeks($i)->endOfWeek();
                $labels[] = "Minggu " . (4 - $i);

                $dataMasuk[] = (int) DB::table('riwayat_stok')
                    ->where('jenis', 'masuk')
                    ->whereBetween('created_at', [
                        $start->startOfDay()->toDateTimeString(), 
                        $end->endOfDay()->toDateTimeString()
                    ])
                    ->sum('jumlah_pcs');

                $dataKeluar[] = (int) DB::table('riwayat_stok')
                    ->where('jenis', 'keluar')
                    ->whereBetween('created_at', [
                        $start->startOfDay()->toDateTimeString(), 
                        $end->endOfDay()->toDateTimeString()
                    ])
                    ->sum('jumlah_pcs');
            }
        } else {
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $labels[] = $date->translatedFormat('M');

                $dataMasuk[] = (int) DB::table('riwayat_stok')
                    ->where('jenis', 'masuk')
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('jumlah_pcs');

                $dataKeluar[] = (int) DB::table('riwayat_stok')
                    ->where('jenis', 'keluar')
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('jumlah_pcs');
            }
        }

        return [
            'labels' => $labels,
            'masuk'  => $dataMasuk,
            'keluar' => $dataKeluar,
        ];
    }

    /**
     * Export rekap barang dengan filter
     */
    public function exportRekap(Request $request)
    {
        try {
            // Debug log
            \Log::info('Export request received:', $request->all());
            
            // Get parameters with defaults
            $tahun = $request->get('tahun', date('Y'));
            $bulan = $request->get('bulan', null);
            $startDate = $request->get('start_date', null);
            $endDate = $request->get('end_date', null);
            $fileName = $request->get('file_name', 'rekap-barang.xlsx');
            
            // Jika start_date dan end_date ada, gunakan custom export
            if ($startDate && $endDate) {
                // Validasi custom date range
                if ($startDate > $endDate) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir'
                    ], 400);
                }
                
                // Export data custom range
                $export = new \App\Exports\RekapCustomExport($startDate, $endDate);
            } else {
                // Export data bulanan/tahunan
                $export = new \App\Exports\RekapBarangExport($tahun, $bulan);
            }
            
            // Generate file name if not provided
            if ($fileName === 'rekap-barang.xlsx') {
                if ($startDate && $endDate) {
                    $start = Carbon::parse($startDate)->format('Ymd');
                    $end = Carbon::parse($endDate)->format('Ymd');
                    $fileName = "rekap-barang-{$start}-{$end}.xlsx";
                } elseif ($bulan) {
                    $monthName = Carbon::create($tahun, $bulan, 1)->translatedFormat('F');
                    $fileName = "rekap-barang-{$monthName}-{$tahun}.xlsx";
                } else {
                    $fileName = "rekap-barang-tahun-{$tahun}.xlsx";
                }
            }
            
            // Pastikan ekstensi .xlsx
            if (!str_ends_with($fileName, '.xlsx')) {
                $fileName .= '.xlsx';
            }
            
            \Log::info('Exporting file:', ['fileName' => $fileName]);
            
            return Excel::download($export, $fileName);
            
        } catch (\Exception $e) {
            \Log::error('Export error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengekspor data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Preview data sebelum export
     */
    public function previewExportData(Request $request)
    {
        try {
            $tahun = $request->get('tahun', date('Y'));
            $bulan = $request->get('bulan', null);
            $startDate = $request->get('start_date', null);
            $endDate = $request->get('end_date', null);
            
            // Query data berdasarkan filter
            $query = DB::table('riwayat_stok')
                ->select(
                    DB::raw("DATE_FORMAT(created_at, '%Y-%m') as periode"),
                    DB::raw("DATE_FORMAT(created_at, '%M %Y') as bulan_tahun"),
                    DB::raw("SUM(CASE WHEN jenis = 'masuk' THEN jumlah_pcs ELSE 0 END) as total_masuk"),
                    DB::raw("SUM(CASE WHEN jenis = 'keluar' THEN jumlah_pcs ELSE 0 END) as total_keluar"),
                    DB::raw("COUNT(DISTINCT id_barang) as jumlah_barang"),
                    DB::raw("COUNT(CASE WHEN jenis = 'masuk' THEN 1 END) as transaksi_masuk"),
                    DB::raw("COUNT(CASE WHEN jenis = 'keluar' THEN 1 END) as transaksi_keluar")
                );

            // Terapkan filter
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            } else {
                $query->whereYear('created_at', $tahun);
                if ($bulan) {
                    $query->whereMonth('created_at', $bulan);
                }
            }

            $data = $query->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), DB::raw("DATE_FORMAT(created_at, '%M %Y')"))
                ->orderBy('periode', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $data,
                'periode' => [
                    'tahun' => $tahun,
                    'bulan' => $bulan,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'jumlah_data' => $data->count()
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Preview export error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data preview'
            ], 500);
        }
    }
}