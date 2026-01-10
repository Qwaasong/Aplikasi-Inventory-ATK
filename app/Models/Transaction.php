<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'tipe',
        'keterangan',
        'jumlah',
        'purchase_id',
        'stock_out_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
