<?php

namespace App\Exports;

use App\Models\RiwayatStok;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RiwayatMutasiStokExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize
{
    private $rowNumber = 0;

    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = RiwayatStok::query()->with('barang')->orderBy('created_at', 'desc');

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59']);
        }

        return $query;
    }

    /**
     * @var RiwayatStok $riwayat
     */
    public function map($riwayat): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $riwayat->created_at->format('Y-m-d H:i:s'),
            $riwayat->barang->nama_barang ?? 'Barang Terhapus',
            ucfirst($riwayat->jenis),
            $riwayat->jumlah_pcs,
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Waktu (Created At)',
            'Nama Barang',
            'Jenis Transaksi (Masuk/Keluar)',
            'Jumlah (Pcs)',
        ];
    }
}
