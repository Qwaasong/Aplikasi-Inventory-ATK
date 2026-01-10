<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = ['nama_vendor', 'alamat', 'no_telp'];

    /**
     * Menerapkan filter dinamis ke query.
     *
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        // Filter berdasarkan nama vendor (pencocokan parsial)
        if (isset($filters['nama_vendor']) && $filters['nama_vendor']) {
            $query->where('nama_vendor', 'like', '%' . $filters['nama_vendor'] . '%');
        }

        // Filter berdasarkan alamat (pencocokan parsial)
        if (isset($filters['alamat']) && $filters['alamat']) {
            $query->where('alamat', 'like', '%' . $filters['alamat'] . '%');
        }

        // Filter berdasarkan rentang tanggal (created_at)
        if (isset($filters['start_date']) && $filters['start_date']) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date']) && $filters['end_date']) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        return $query;
    }
}
