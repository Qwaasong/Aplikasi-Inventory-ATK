<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    // Nama tabel di database
    protected $table = 'kategori';
    
    // Primary Key
    protected $primaryKey = 'id_kategori';

    // Kolom yang boleh diisi (Mass Assignment)
    protected $fillable = ['nama_kategori'];

    // Relasi: Satu kategori memiliki banyak barang
    public function barang(): HasMany
    {
        return $this->hasMany(Barang::class, 'id_kategori', 'id_kategori');
    }
}