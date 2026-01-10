<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\FinancialTransaction;

class Purchase extends Model
{
    protected $fillable = ['tanggal', 'vendor_id', 'no_faktur'];

    protected static function booted()
    {
        static::created(function ($purchase) {
            // Pastikan relasi items dimuat
            $purchase->loadMissing('items');

            // Hitung total pengeluaran
            $total = $purchase->items->sum(function ($item) {
                return $item->jumlah_pack * $item->harga_beli;
            });

            // Cek jika total lebih dari 0, lalu buat transaksi
            if ($total > 0) {
                FinancialTransaction::create([
                    'tipe' => 'pengeluaran',
                    'jumlah' => $total,
                    'tanggal' => now(),
                    'keterangan' => 'Pembelian barang - No Faktur: ' . $purchase->no_faktur .
                    ($purchase->keterangan ? ' | Catatan: ' . $purchase->keterangan : ''),

                    'purchase_id' => $purchase->id,
                ]);
            }
        });
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(\App\Models\PurchaseItem::class);
    }

    public function financialTransaction()
    {
        return $this->hasOne(FinancialTransaction::class);
    }
}
