<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FinancialTransaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua data tanpa filter
        $transactions = FinancialTransaction::all();

        // Total pemasukan, pengeluaran, saldo
        $pemasukan = $transactions->where('tipe', 'pemasukan')->sum('jumlah');
        $pengeluaran = $transactions->where('tipe', 'pengeluaran')->sum('jumlah');
        $saldo = $pemasukan - $pengeluaran;

        // Data chart per tanggal (semua data)
        $dataChart = FinancialTransaction::selectRaw('tanggal,
                SUM(CASE WHEN tipe="pemasukan" THEN jumlah ELSE 0 END) as total_pemasukan,
                SUM(CASE WHEN tipe="pengeluaran" THEN jumlah ELSE 0 END) as total_pengeluaran')
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        // Parameter filter (default: 'bulan') - but we'll handle filtering in JS
        $filter = $request->get('filter', 'bulan');

        return view('welcome', [
            'filter' => $filter,
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'saldo' => $saldo,
            'dataChart' => $dataChart
        ]);
    }
}
