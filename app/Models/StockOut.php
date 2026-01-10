<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\FinancialTransaction;
use App\Models\PurchaseItem;

class StockOut extends Model
{
    protected $fillable = [
        'tanggal',
        'product_id',
        'jumlah_pack',
        'keterangan',
    ];

    protected static function booted()
    {
        {
            static::created(fn($stock) => $stock->product->decrement('stok', $stock->jumlah_pack));
            static::deleted(fn($stock) => $stock->product->increment('stok', $stock->jumlah_pack));
        }
        static::created(function ($stockOut) {
            // Ambil purchase item terbaru untuk produk ini
            $purchaseItem = PurchaseItem::where('product_id', $stockOut->product_id)
                ->latest()
                ->first();

            // Jika tidak ada purchase item, hentikan proses
            if (!$purchaseItem) {
                return;
            }

            // Harga beli dan jual diambil langsung per pack
            $hargaBeliPerPack = $purchaseItem->harga_beli ?? 0;
            $hargaJualPerPack = $purchaseItem->harga_jual ?? 0;

            // Hitung total pemasukan & keuntungan berdasarkan pack
            $total = $stockOut->jumlah_pack * $hargaJualPerPack;
            $keuntungan = ($hargaJualPerPack - $hargaBeliPerPack) * $stockOut->jumlah_pack;

            // Simpan transaksi jika ada nilai total
            if ($total > 0) {
                FinancialTransaction::create([
                    'tipe' => 'pemasukan',
                    'jumlah' => $total,
                    'tanggal' => now()->toDateString(),
                    'keterangan' =>
                        'Penjualan produk: ' . ($purchaseItem->product->nama ?? 'Tidak diketahui') .
                        ' | Keuntungan: ' . number_format($keuntungan, 0, ',', '.') .
                        ($stockOut->keterangan ? ' | Catatan: ' . $stockOut->keterangan : ''),
                    'stock_out_id' => $stockOut->id,
                ]);
            }
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function financialTransaction()
    {
        return $this->hasOne(FinancialTransaction::class);
    }

}
