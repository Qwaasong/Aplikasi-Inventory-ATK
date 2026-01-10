<?php

namespace App\Http\Controllers;

use App\Models\LedgerEntry;
use App\Models\FinancialTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LedgerEntryController extends Controller
{
    public function create()
    {
        return view('ledger_entries.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'tipe' => 'required|in:hutang,piutang',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'required|string|max:500',
            'tanggal_transaksi' => 'required|date',
            'jatuh_tempo' => 'nullable|date|after_or_equal:tanggal_transaksi',
        ]);

        // Create Ledger Entry
        $ledgerEntry = LedgerEntry::create([
            'nama' => $request->nama,
            'telepon' => $request->telepon,
            'tipe' => $request->tipe,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'tanggal_transaksi' => $request->tanggal_transaksi,
            'jatuh_tempo' => $request->jatuh_tempo,
        ]);

        // AUTO CREATE FINANCIAL TRANSACTION
        $transactionType = $ledgerEntry->tipe === 'hutang' ? 'pengeluaran' : 'pemasukan';
        $description = ($ledgerEntry->tipe === 'hutang' ? 'Hutang' : 'Piutang') . " - {$ledgerEntry->nama}: {$ledgerEntry->keterangan}";

        FinancialTransaction::create([
            'tanggal' => $request->tanggal_transaksi,
            'tipe' => $transactionType,
            'keterangan' => $description,
            'jumlah' => $request->nominal,
            'ledger_entry_id' => $ledgerEntry->id,
        ]);

        $jenis = $request->tipe === 'hutang' ? 'hutang' : 'piutang';
        
        return redirect()->route('ledger_entries.index')
            ->with('success', "Catatan $jenis berhasil ditambahkan.");
    }

    public function edit($id)
    {
        $entry = LedgerEntry::findOrFail($id);
        return view('ledger_entries.edit', compact('entry'));
    }

    public function update(Request $request, $id)
    {
        $entry = LedgerEntry::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'tipe' => 'required|in:hutang,piutang',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'required|string|max:500',
            'tanggal_transaksi' => 'required|date',
            'jatuh_tempo' => 'nullable|date|after_or_equal:tanggal_transaksi',
        ]);

        $oldNominal = $entry->nominal;
        $oldType = $entry->tipe;

        $entry->update($request->all());

        // UPDATE FINANCIAL TRANSACTION
        $transactionType = $entry->tipe === 'hutang' ? 'pengeluaran' : 'pemasukan';
        $description = ($entry->tipe === 'hutang' ? 'Hutang' : 'Piutang') . " - {$entry->nama}: {$entry->keterangan}";

        // Cari atau buat financial transaction
        $financialTransaction = FinancialTransaction::where('ledger_entry_id', $entry->id)->first();

        if ($financialTransaction) {
            // Update existing transaction
            $financialTransaction->update([
                'tanggal' => $request->tanggal_transaksi,
                'tipe' => $transactionType,
                'keterangan' => $description,
                'jumlah' => $request->nominal,
            ]);
        } else {
            // Create new transaction jika tidak ada
            FinancialTransaction::create([
                'tanggal' => $request->tanggal_transaksi,
                'tipe' => $transactionType,
                'keterangan' => $description,
                'jumlah' => $request->nominal,
                'ledger_entry_id' => $entry->id,
            ]);
        }

        return redirect()->route('ledger_entries.index')
            ->with('success', 'Catatan hutang/piutang berhasil diperbarui.');
    }

    // Update jatuh tempo
    public function updateJatuhTempo(Request $request, $id)
    {
        try {
            $entry = LedgerEntry::findOrFail($id);
            
            $request->validate([
                'jatuh_tempo' => 'required|date'
            ]);

            $entry->update([
                'jatuh_tempo' => $request->jatuh_tempo
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jatuh tempo berhasil diupdate'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal update jatuh tempo: ' . $e->getMessage()
            ], 500);
        }
    }

    // Tambah hutang/piutang
    public function tambahUtang(Request $request, $id)
    {
        try {
            $entry = LedgerEntry::findOrFail($id);
            
            $request->validate([
                'nominal' => 'required|numeric|min:0'
            ]);

            $oldNominal = $entry->nominal;
            $newNominal = $entry->nominal + $request->nominal;

            // Update ledger entry
            $entry->update([
                'nominal' => $newNominal
            ]);

            // Update financial transaction
            $financialTransaction = FinancialTransaction::where('ledger_entry_id', $entry->id)->first();
            if ($financialTransaction) {
                $financialTransaction->update([
                    'jumlah' => $newNominal
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambah nominal'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambah: ' . $e->getMessage()
            ], 500);
        }
    }

    // Bayar hutang/piutang
    public function bayarUtang(Request $request, $id)
    {
        try {
            $entry = LedgerEntry::findOrFail($id);
            
            $request->validate([
                'nominal' => 'required|numeric|min:0|max:' . $entry->nominal
            ]);

            $oldNominal = $entry->nominal;
            $newNominal = $entry->nominal - $request->nominal;

            // Update ledger entry
            $entry->update([
                'nominal' => $newNominal
            ]);

            // Update financial transaction
            $financialTransaction = FinancialTransaction::where('ledger_entry_id', $entry->id)->first();
            if ($financialTransaction) {
                $financialTransaction->update([
                    'jumlah' => $newNominal
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    // Lunaskan (hapus entry)
    public function lunaskan($id)
    {
        try {
            $entry = LedgerEntry::findOrFail($id);
            
            // Hapus financial transaction terkait jika ada
            $financialTransaction = FinancialTransaction::where('ledger_entry_id', $entry->id)->first();
            if ($financialTransaction) {
                $financialTransaction->delete();
            }

            $entry->delete();

            return response()->json([
                'success' => true,
                'message' => ($entry->tipe == 'hutang' ? 'Hutang' : 'Piutang') . ' berhasil dilunasi'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal melunasi: ' . $e->getMessage()
            ], 500);
        }
    }
}