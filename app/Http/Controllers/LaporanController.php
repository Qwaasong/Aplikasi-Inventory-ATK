<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\RekapBarangExport;
use App\Exports\RekapCustomExport;  // IMPORT INI!
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function export(Request $request)
    {
        \Log::info('Export Request Data:', $request->all());
        
        // Cek jika request adalah AJAX (untuk debug)
        if ($request->ajax()) {
            \Log::info('AJAX Request detected');
        }
        
        // LOGIC: Deteksi jenis export berdasarkan parameter
        if ($request->has('start_date') && $request->has('end_date')) {
            // 1. EXPORT CUSTOM DATE RANGE
            \Log::info('Processing Custom Range Export');
            return $this->handleCustomExport($request);
            
        } elseif ($request->has('tahun')) {
            // 2. EXPORT BULANAN/TAHUNAN  
            \Log::info('Processing Period Export');
            return $this->handlePeriodExport($request);
            
        } else {
            // 3. PARAMETER TIDAK LENGKAP
            \Log::error('Invalid export parameters', $request->all());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parameter tidak valid',
                    'received' => $request->all()
                ], 400);
            }
            
            return back()->withErrors(['error' => 'Parameter tidak valid']);
        }
    }
    
    private function handleCustomExport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        // Nama file
        $fileName = 'rekap-barang-' . $startDate . '-sd-' . $endDate . '.xlsx';
        if ($request->has('file_name') && !empty($request->file_name)) {
            $fileName = $request->file_name;
            if (!str_ends_with($fileName, '.xlsx')) {
                $fileName .= '.xlsx';
            }
        }
        
        \Log::info('Custom Export - File: ' . $fileName);
        
        return Excel::download(
            new RekapCustomExport($startDate, $endDate),
            $fileName
        );
    }
    
    private function handlePeriodExport(Request $request)
    {
        $request->validate([
            'tahun' => 'required|numeric|min:2020|max:2030',
            'bulan' => 'nullable|numeric|min:1|max:12',
        ]);
        
        $tahun = $request->tahun;
        $bulan = $request->bulan;
        
        // Nama file
        if ($request->has('file_name') && !empty($request->file_name)) {
            $fileName = $request->file_name;
            if (!str_ends_with($fileName, '.xlsx')) {
                $fileName .= '.xlsx';
            }
        } else {
            $bulanNama = $bulan ? date('F', mktime(0, 0, 0, $bulan, 1)) : '';
            $fileName = 'rekap-barang-' . 
                       ($bulan ? strtolower($bulanNama) . '-' : '') . 
                       $tahun . '.xlsx';
        }
        
        \Log::info('Period Export - Tahun: ' . $tahun . ', Bulan: ' . ($bulan ?? 'null'));
        
        return Excel::download(
            new RekapBarangExport($tahun, $bulan),
            $fileName
        );
    }
    
    /**
     * Untuk debugging: Tampilkan parameter yang diterima
     */
    public function debugExport(Request $request)
    {
        return response()->json([
            'parameters' => $request->all(),
            'headers' => $request->headers->all(),
            'is_ajax' => $request->ajax(),
            'server' => $_SERVER
        ]);
    }
}