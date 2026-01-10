<?php

namespace App\Http\Controllers;

use App\Models\FinancialTransaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class FinancialTransactionController extends Controller
{
    

    public function create()
    {
        return view('pembukuan_transaksi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'tipe' => 'required|in:pemasukan,pengeluaran',
            'keterangan' => 'required|string|max:500',
            'jumlah' => 'required|numeric|min:0',
            // BARU: Tambahkan validasi untuk no_faktur (diasumsikan opsional atau string)
            'no_faktur' => 'nullable|string|max:100', 
        ]);

        FinancialTransaction::create($request->all());

        return redirect()->route('pembukuan_transaksi.index')
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $transaction = FinancialTransaction::findOrFail($id);
        return view('pembukuan_transaksi.edit', compact('transaction'));
    }

    public function update(Request $request, $id)
    {
        $transaction = FinancialTransaction::findOrFail($id);

        $request->validate([
            'tanggal' => 'required|date',
            'tipe' => 'required|in:pemasukan,pengeluaran',
            'keterangan' => 'required|string|max:500',
            'jumlah' => 'required|numeric|min:0',
            // BARU: Tambahkan validasi untuk no_faktur (diasumsikan opsional atau string)
            'no_faktur' => 'nullable|string|max:100', 
        ]);

        $transaction->update($request->all());

        return redirect()->route('pembukuan_transaksi.index')
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function generatePDF(Request $request)
    {

        // Ambil data transaksi keuangan
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        $transactions = FinancialTransaction::whereBetween('tanggal', [$startDate, $endDate])
        // Tetap menggunakan eager loading untuk relasi yang mungkin berisi no_faktur
        ->with('purchase', 'stockOut') 
        ->get();

        if (!$startDate || !$endDate) {
            return redirect()->back()->with('error', 'Tanggal awal dan akhir harus diisi.');
        }
        
        // Ambil transaksi, termasuk relasi
        $transactions = FinancialTransaction::whereBetween('tanggal', [$startDate, $endDate])
            // Tetap menggunakan eager loading untuk relasi yang mungkin berisi no_faktur
            ->with('purchase', 'stockOut') 
            ->get();
            
        // LOGIKA PENAMBAHAN NO. FAKTUR YANG DIREVISI:
    $transactions = $transactions->map(function ($transaction) {
        // 1. Cek jika no_faktur sudah ada di FinancialTransaction (jika kolom ini ada)
        if (!empty($transaction->no_faktur)) {
            // Jika sudah ada, gunakan yang ini
        } 
        // 2. Cek dari relasi Purchase
        elseif ($transaction->purchase && !empty($transaction->purchase->no_faktur)) {
            // **PENTING**: Asumsi kolom di tabel purchases adalah 'no_faktur'
            $transaction->no_faktur = $transaction->purchase->no_faktur; 
        } 
        // 3. Cek dari relasi Stock Out
        elseif ($transaction->stockOut && !empty($transaction->stockOut->no_faktur)) {
            // **PENTING**: Asumsi kolom di tabel stock_outs adalah 'no_faktur'
            $transaction->no_faktur = $transaction->stockOut->no_faktur;
        } else {
            // Default jika tidak ada
            $transaction->no_faktur = '-';
        }
        return $transaction;
    });
            
        $totalPemasukan = FinancialTransaction::where('tipe', 'pemasukan')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->sum('jumlah');
            
        $totalPengeluaran = FinancialTransaction::where('tipe', 'pengeluaran')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->sum('jumlah');
            
        $saldo = $totalPemasukan - $totalPengeluaran;

        // Data yang akan dikirim ke view
        $data = [
            'transactions' => $transactions,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldo' => $saldo,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        // Generate PDF
        $pdf = Pdf::loadView('pembukuan_transaksi.laporan_keuangan', $data);
        return $pdf->download('laporan-keuangan-' . date('Y-m-d') . '.pdf');
    }
}