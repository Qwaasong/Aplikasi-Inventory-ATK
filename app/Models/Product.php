<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'nama',
        'kategori',
        'isi_per_pack',
        'satuan_pack',
      
    ];
    #enum satuan_pack harus tetap sesuai dengan yang ada di database
    public static array $satuanPackOptions = [
        'dus' => 'Dus',
        'kotak' => 'Kotak',
        'bal' => 'Bal',
        'renteng' => 'Renteng',
        'pak' => 'Pak',
        'bungkus' => 'Bungkus',
        'karung' => 'Karung',
    ];
    public static array $kategoriOptions = [
        'makanan_ringan' => 'Makanan Ringan',
        'minuman' => 'Minuman',
        'alat_tulis' => 'Alat Tulis ',
        'buku' => 'Buku',
        'seragam' => 'Seragam Sekolah',
        'kebersihan' => 'Alat Kebersihan',
        'aksesoris' => 'Aksesoris Sekolah',
        'makanan_berat' => 'Makanan Berat',
    ];

    

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function purchaseItems(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function stockOuts(): HasMany
    {
        return $this->hasMany(StockOut::class);
    }
   
}


