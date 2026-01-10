<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseItem extends Model
{
    protected $fillable = [
        'purchase_id',
        'product_id',
        'jumlah_pack',
        'harga_beli',
        'harga_jual',
    ];

    protected static function booted()
{
    static::created(fn($item) => $item->product->increment('stok', $item->jumlah_pack));
    static::deleted(fn($item) => $item->product->decrement('stok', $item->jumlah_pack));
}
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    
}


