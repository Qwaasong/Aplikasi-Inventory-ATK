<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;


class FinancialTransaction extends Model
{
    protected $fillable = [
        'tanggal',
        'tipe',
        'keterangan',
        'jumlah',
        'purchase_id',
        'stock_out_id',
        'ledger_entry_id',
    ];
#    protected static function booted()
#{
#    static::deleted(function ($transaction) {
#        Notification::make()
#            ->title('Refund Biaya')
#            ->body("Transaksi sebesar Rp " . number_format($transaction->jumlah, 0, ',', '.') . " telah dibatalkan.")
#            ->success()
#            ->send();
#    });
#}
    public static function getCurrentBalance(): float
    {
        $pemasukan = static::where('tipe', 'pemasukan')->sum('jumlah');
        $pengeluaran = static::where('tipe', 'pengeluaran')->sum('jumlah');
    
        return $pemasukan - $pengeluaran;
    }
    
    public static function getTotalPemasukan(): float
    {
        return static::where('tipe', 'pemasukan')->sum('jumlah');
    }
    
    public static function getTotalPengeluaran(): float
    {
        return static::where('tipe', 'pengeluaran')->sum('jumlah');
    }
    public function purchase()
    {
    return $this->belongsTo(Purchase::class);
    }

public function stockOut()
    {
    return $this->belongsTo(StockOut::class);
    }

    public function ledgerEntry()
    {
        return $this->belongsTo(LedgerEntry::class);
    }

}


