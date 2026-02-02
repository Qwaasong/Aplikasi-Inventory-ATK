<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanInventarisBarangExport;
use App\Exports\RiwayatMutasiStokExport;
use App\Exports\RekapKategoriExport;
use App\Exports\UserListExport;

class LaporanController extends Controller
{
    public function export(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'type' => 'required|in:inventaris,mutasi,kategori,user',
            'file_name' => 'nullable|string',
        ]);

        $type = $request->type;
        $fileName = $request->file_name ?? 'laporan-export';

        // Pastikan ekstensi .xlsx ada
        if (!str_ends_with($fileName, '.xlsx')) {
            $fileName .= '.xlsx';
        }

        // 2. Switch Case Berdasarkan Tipe Laporan
        switch ($type) {
            case 'inventaris':
                return Excel::download(new LaporanInventarisBarangExport, $fileName);

            case 'kategori':
                return Excel::download(new RekapKategoriExport, $fileName);

            case 'user':
                return Excel::download(new UserListExport, $fileName);

            case 'mutasi':
                return $this->handleMutasiExport($request, $fileName);

            default:
                return back()->withErrors(['error' => 'Tipe laporan tidak dikenali']);
        }
    }

    private function handleMutasiExport(Request $request, $fileName)
    {
        // Logika khusus untuk filter tanggal pada Mutasi
        $startDate = null;
        $endDate = null;

        if ($request->period === 'custom') {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);
            $startDate = $request->start_date;
            $endDate = $request->end_date;

        } elseif ($request->period === 'bulanan') {
            $request->validate([
                'tahun' => 'required|numeric',
                'bulan' => 'nullable|numeric',
            ]);

            $tahun = $request->tahun;
            $bulan = $request->bulan;

            if ($bulan) {
                // Filter 1 Bulan penuh
                $startDate = "{$tahun}-{$bulan}-01";
                $endDate = date("Y-m-t", strtotime($startDate));
            } else {
                // Filter 1 Tahun penuh (jika bulan kosong dipilih 'Semua Bulan')
                $startDate = "{$tahun}-01-01";
                $endDate = "{$tahun}-12-31";
            }

        } elseif ($request->period === 'tahunan') {
            // Sama seperti tahunan di atas
            $tahun = $request->tahun ?? date('Y');
            $startDate = "{$tahun}-01-01";
            $endDate = "{$tahun}-12-31";
        }

        return Excel::download(new RiwayatMutasiStokExport($startDate, $endDate), $fileName);
    }
}