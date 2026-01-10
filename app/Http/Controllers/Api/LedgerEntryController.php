<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LedgerEntry;
use App\Models\FinancialTransaction; // <-- 1. Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;   // <-- 2. Tambahkan ini
use Illuminate\Support\Facades\Log;  // <-- 3. Tambahkan ini (opsional, untuk error)

class LedgerEntryController extends Controller
{
    public function index(Request $request)
    {
        $query = LedgerEntry::query();

        // 🔍 Pencarian berdasarkan nama atau keterangan
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('keterangan', 'like', '%' . $search . '%')
                    ->orWhere('telepon', 'like', '%' . $search . '%');
            });
        }

        // 🏷️ Filter berdasarkan tipe
        if ($request->filled('filter.tipe')) {
            $query->where('tipe', $request->filter['tipe']);
        }

        // 🏷️ Filter berdasarkan periode (minggu/bulan/tahun)
        if ($request->filled('filter.periode')) {
            $periode = $request->filter['periode'];
            $now = now();
            switch ($periode) {
                case 'minggu':
                    $startDate = $now->startOfWeek()->format('Y-m-d');
                    $endDate = $now->endOfWeek()->format('Y-m-d');
                    $query->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
                    break;
                case 'bulan':
                    $startDate = $now->startOfMonth()->format('Y-m-d');
                    $endDate = $now->endOfMonth()->format('Y-m-d');
                    $query->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
                    break;
                case 'tahun':
                    $startDate = $now->startOfYear()->format('Y-m-d');
                    $endDate = $now->endOfYear()->format('Y-m-d');
                    $query->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
                    break;
            }
        }

        // 📅 Filter berdasarkan status jatuh tempo (terlambat/mendatang)
        if ($request->filled('filter.status')) {
            $status = $request->filter['status'];
            if ($status === 'terlambat') {
                $query->where('jatuh_tempo', '<', now())->whereNotNull('jatuh_tempo');
            } elseif ($status === 'mendatang') {
                $query->where('jatuh_tempo', '>', now())->whereNotNull('jatuh_tempo');
            }
        }
        

        $entries = $query->latest()->paginate(10);

        // Transformasi data untuk frontend
        $entries->getCollection()->transform(function ($entry) {
            return [
                'id'            => $entry->id,
                'nama'          => $entry->nama, 
                'tipe'          => $entry->tipe,
                'nominal'       => 'Rp ' . number_format($entry->nominal, 0, ',', '.'),
                'keterangan'    => $entry->keterangan,
                'telepon'       => $entry->telepon,
                // Tambahkan data lain jika perlu
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $entries
        ]);
    }

    /**
     * Hapus data Hutang (LedgerEntry) dan FinancialTransaction terkait.
     * Logika ini sekarang disamakan dengan controller non-API Anda.
     */
    public function destroy($id)
    {
        // 1. Cari data Hutang (LedgerEntry)
        $entry = LedgerEntry::findOrFail($id);

        // 2. Mulai Database Transaction untuk keamanan data
        DB::beginTransaction();

        try {
            // 3. Hapus Financial Transaction terkait SECARA MANUAL
            //    Ini menggunakan kolom 'ledger_entry_id' yang sudah Anda migrasikan
            FinancialTransaction::where('ledger_entry_id', $entry->id)->delete();

            // 4. Hapus data Hutang (LedgerEntry) itu sendiri
            $entry->delete();

            // 5. Commit jika semua berhasil
            DB::commit();

            // 6. Kirim respon sukses (JSON)
            return response()->json([
                'success' => true,
                'message' => 'Catatan hutang/piutang dan transaksi keuangannya berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
            // 7. Rollback jika terjadi error
            DB::rollBack();

            // Catat error untuk debugging
            Log::error("Gagal menghapus hutang via API (ID: {$id}). Error: " . $e->getMessage());

            // 8. Kirim respon error (JSON)
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500); // 500 = Internal Server Error
        }
    }


    // 📈 Method untuk dashboard summary
    public function summary()
    {
        $totalHutang = LedgerEntry::hutang()->sum('nominal');
        $totalPiutang = LedgerEntry::piutang()->sum('nominal');
        $hutangTerlambat = LedgerEntry::hutang()
            ->where('jatuh_tempo', '<', now())
            ->whereNotNull('jatuh_tempo')
            ->sum('nominal');
        $piutangTerlambat = LedgerEntry::piutang()
            ->where('jatuh_tempo', '<', now())
            ->whereNotNull('jatuh_tempo')
            ->sum('nominal');

        return response()->json([
            'success' => true,
            'data' => [
                'total_hutang' => $totalHutang,
                'total_piutang' => $totalPiutang,
                'hutang_terlambat' => $hutangTerlambat,
                'piutang_terlambat' => $piutangTerlambat,
            ]
        ]);
    }
}