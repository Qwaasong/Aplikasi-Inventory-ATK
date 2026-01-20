<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\User;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        if ($filter == 'mingguan') {
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $labels[] = $date->translatedFormat('l'); // Nama hari (Senin, Selasa...)
                
                $dataMasuk[] = DB::table('riwayat_stok')
                    ->where('jenis', 'masuk')
                    ->whereDate('created_at', $date)
                    ->sum('jumlah_pcs');

                $dataKeluar[] = DB::table('riwayat_stok')
                    ->where('jenis', 'keluar')
                    ->whereDate('created_at', $date)
                    ->sum('jumlah_pcs');
            }
        } elseif ($filter == 'bulanan') {
            // Logika untuk 4 minggu terakhir
            for ($i = 3; $i >= 0; $i--) {
                $start = Carbon::now()->subWeeks($i)->startOfWeek();
                $end = Carbon::now()->subWeeks($i)->endOfWeek();
                $labels[] = "Minggu " . (4 - $i);

                $dataMasuk[] = DB::table('riwayat_stok')
                    ->where('jenis', 'masuk')
                    ->whereBetween('created_at', [$start, $end])
                    ->sum('jumlah_pcs');

                $dataKeluar[] = DB::table('riwayat_stok')
                    ->where('jenis', 'keluar')
                    ->whereBetween('created_at', [$start, $end])
                    ->sum('jumlah_pcs');
            }
        } else { // Tahunan
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $labels[] = $date->translatedFormat('M'); // Jan, Feb, Mar...

                $dataMasuk[] = DB::table('riwayat_stok')
                    ->where('jenis', 'masuk')
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('jumlah_pcs');

                $dataKeluar[] = DB::table('riwayat_stok')
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
}