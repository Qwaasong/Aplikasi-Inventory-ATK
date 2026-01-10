<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    protected $table = 'ledgers';

    protected $fillable = [
        'tipe',
        'tanggal',
        'nama_vendor',
        'no_faktur',
        'jumlah',
        'catatan',
    ];

public function purchase()
{
    return $this->belongsTo(Purchase::class, 'no_faktur', 'no_faktur');
}

}
