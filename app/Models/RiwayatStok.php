<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatStok extends Model
{
    use HasFactory;

    // Nama tabel secara eksplisit
    protected $table = 'riwayat_stok';

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'id_barang',
        'jenis',      // 'masuk' atau 'keluar'
        'jumlah_pcs',
        'created_at'  // Kita izinkan pengisian manual jika ingin input data lampau
    ];

    /**
     * Relasi: Setiap catatan riwayat merujuk pada satu Barang.
     */
    public function barang(): BelongsTo
    {
        // Parameter: (NamaModel, foreign_key_di_tabel_ini, owner_key_di_tabel_barang)
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }
}