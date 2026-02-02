<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporanInventarisBarangExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize
{
    private $rowNumber = 0;

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return Barang::with('kategori')->orderBy('nama_barang', 'asc');
    }

    /**
     * @var Barang $barang
     */
    public function map($barang): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $barang->nama_barang,
            $barang->kategori->nama_kategori ?? 'Tanpa Kategori',
            $barang->nama_pack ?? '-',
            $barang->jumlah_pack,
            $barang->jumlah_pcs,
            $barang->total_pcs,
            $barang->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Barang',
            'Nama Kategori',
            'Jenis Kemasan (Pack)',
            'Jumlah Pack',
            'Isi per Pack (Pcs)',
            'Total Stok (Pcs)',
            'Tanggal Input',
        ];
    }
}
