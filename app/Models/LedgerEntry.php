<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; // Tambahkan ini jika ingin logging

class LedgerEntry extends Model
{
    protected $fillable = [
        'nama',
        'telepon', 
        'tipe',
        'nominal',
        'keterangan',
        'tanggal_transaksi',
        'jatuh_tempo'
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'jatuh_tempo' => 'date',
        'nominal' => 'decimal:2',
    ];

    /**
     * Daftarkan model event listeners.
     * Ini adalah inti solusinya.
     */
    protected static function booted(): void
    {
        // Gunakan event 'deleted' yang berjalan SETELAH model dihapus dari database.
        static::deleted(function (LedgerEntry $ledgerEntry) {
            
            // Cek apakah ledgerEntry ini memiliki financialTransaction yang terkait
            // Kita menggunakan relasi `financialTransaction()` yang sudah Anda definisikan.
            if ($ledgerEntry->financialTransaction) {
                try {
                    // Hapus data financial transaction yang terkait
                    $ledgerEntry->financialTransaction->delete();

                } catch (\Exception $e) {
                    // Opsional: Catat error jika gagal menghapus transaksi terkait
                    Log::error("Gagal menghapus financial transaction terkait LedgerEntry ID: {$ledgerEntry->id}. Error: " . $e->getMessage());
                }
            }
        });
    }

    public function financialTransaction()
    {
        return $this->hasOne(FinancialTransaction::class, 'ledger_entry_id'); // Pastikan foreign key benar
    }

    // Scope untuk hutang
    public function scopeHutang($query)
    {
        return $query->where('tipe', 'hutang');
    }

    // Scope untuk piutang
    public function scopePiutang($query)
    {
        return $query->where('tipe', 'piutang');
    }

    // Scope berdasarkan nama
    public function scopeByNama($query, $nama)
    {
        return $query->where('nama', 'like', "%{$nama}%");
    }

    // Cek apakah telat bayar - PERBAIKAN
    public function isTerlambat(): bool
    {
        return $this->jatuh_tempo && Carbon::parse($this->jatuh_tempo)->isPast();
    }

    // Accessor untuk format tanggal
    public function getJatuhTempoFormattedAttribute()
    {
        return $this->jatuh_tempo ? Carbon::parse($this->jatuh_tempo)->format('d/m/Y') : '-';
    }

    public function getTanggalTransaksiFormattedAttribute()
    {
        return Carbon::parse($this->tanggal_transaksi)->format('d/m/Y');
    }
    
}