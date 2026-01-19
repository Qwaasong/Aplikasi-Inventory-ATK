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
        // Catatan: Idealnya data ini diambil dari tabel 'transaksi' 
        // Jika belum ada tabel transaksi, ini adalah struktur data respons untuk Chart.js/ApexCharts
        
        if ($filter == 'mingguan') {
            return [
                'labels' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                'masuk'  => [12, 19, 8, 15, 22, 10, 5],
                'keluar' => [7, 14, 6, 8, 18, 9, 4],
            ];
        } elseif ($filter == 'bulanan') {
            return [
                'labels' => ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
                'masuk'  => [45, 52, 38, 65],
                'keluar' => [30, 48, 35, 50],
            ];
        } else { // Tahunan
            return [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                'masuk'  => [150, 230, 180, 210, 290, 250],
                'keluar' => [120, 200, 150, 190, 240, 210],
            ];
        }
    }
}