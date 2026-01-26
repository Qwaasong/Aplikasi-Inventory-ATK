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
                'total_pcs' => $totalPcs,
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
        
        // Tambahkan baris ini agar nama hari/bulan otomatis Bahasa Indonesia
        \Carbon\Carbon::setLocale('id');

        if ($filter == 'mingguan') {
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                // 'l' akan menghasilkan Senin, Selasa, dst karena locale sudah 'id'
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
            // Loop 12 bulan dari Januari sampai Desember tahun ini
            for ($m = 1; $m <= 12; $m++) {
                $date = Carbon::create(Carbon::now()->year, $m, 1);
                $labels[] = $date->translatedFormat('M'); // Jan, Feb, Mar...

                $dataMasuk[] = (int) DB::table('riwayat_stok')
                    ->where('jenis', 'masuk')
                    ->whereMonth('created_at', $m)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->sum('jumlah_pcs');

                $dataKeluar[] = (int) DB::table('riwayat_stok')
                    ->where('jenis', 'keluar')
                    ->whereMonth('created_at', $m)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->sum('jumlah_pcs');
            }
        } elseif ($filter == 'tahunan') {
            // PERBAIKAN: Loop 5 Tahun (Contoh: 2024 sampai 2028)
            $tahunSekarang = Carbon::now()->year;
            for ($i = 0; $i < 5; $i++) {
                $tahun = $tahunSekarang + $i;
                $labels[] = (string) $tahun;

                $dataMasuk[] = (int) DB::table('riwayat_stok')
                    ->where('jenis', 'masuk')
                    ->whereYear('created_at', $tahun)
                    ->sum('jumlah_pcs');

                $dataKeluar[] = (int) DB::table('riwayat_stok')
                    ->where('jenis', 'keluar')
                    ->whereYear('created_at', $tahun)
                    ->sum('jumlah_pcs');
            }
        }

        return [
            'labels' => !empty($labels) ? $labels : ['No Data'],
            'masuk' => !empty($dataMasuk) ? $dataMasuk : [0],
            'keluar' => !empty($dataKeluar) ? $dataKeluar : [0],
        ];
    }
}