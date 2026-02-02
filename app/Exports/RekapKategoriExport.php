<?php

namespace App\Exports;

use App\Models\Kategori;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\DB;

class RekapKategoriExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Kategori::leftJoin('barang', 'kategori.id_kategori', '=', 'barang.id_kategori')
            ->select(
                'kategori.nama_kategori',
                DB::raw('COUNT(barang.id_barang) as total_variasi'),
                DB::raw('SUM(IFNULL(barang.total_pcs, 0)) as total_pcs')
            )
            ->groupBy('kategori.id_kategori', 'kategori.nama_kategori')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nama Kategori',
            'Total Variasi Barang',
            'Total Seluruh Pcs',
        ];
    }
}
