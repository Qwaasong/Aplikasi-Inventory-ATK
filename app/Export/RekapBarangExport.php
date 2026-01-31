<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RekapBarangExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $tahun;
    protected $bulan;

    public function __construct($tahun, $bulan = null)
    {
        $this->tahun = $tahun;
        $this->bulan = $bulan;
    }

    public function collection()
    {
        $query = DB::table('riwayat_stok')
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%M %Y') as bulan_tahun"),
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as periode"),
                DB::raw("SUM(CASE WHEN jenis = 'masuk' THEN jumlah_pcs ELSE 0 END) as total_masuk"),
                DB::raw("SUM(CASE WHEN jenis = 'keluar' THEN jumlah_pcs ELSE 0 END) as total_keluar"),
                DB::raw("COUNT(DISTINCT id_barang) as jumlah_barang"),
                DB::raw("COUNT(CASE WHEN jenis = 'masuk' THEN 1 END) as transaksi_masuk"),
                DB::raw("COUNT(CASE WHEN jenis = 'keluar' THEN 1 END) as transaksi_keluar")
            )
            ->whereYear('created_at', $this->tahun);

        if ($this->bulan) {
            $query->whereMonth('created_at', $this->bulan);
        }

        return $query->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), DB::raw("DATE_FORMAT(created_at, '%M %Y')"))
            ->orderBy('periode')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Bulan/Tahun',
            'Barang Masuk (Pcs)',
            'Barang Keluar (Pcs)',
            'Selisih',
            'Jumlah Barang',
            'Transaksi Masuk',
            'Transaksi Keluar',
            'Status'
        ];
    }

    public function map($row): array
    {
        static $counter = 1;
        
        $selisih = $row->total_masuk - $row->total_keluar;
        $status = $selisih > 0 ? 'BERTAMBAH' : ($selisih < 0 ? 'BERKURANG' : 'STABIL');
        
        return [
            $counter++,
            $row->bulan_tahun,
            number_format($row->total_masuk, 0, ',', '.'),
            number_format($row->total_keluar, 0, ',', '.'),
            number_format($selisih, 0, ',', '.'),
            $row->jumlah_barang,
            $row->transaksi_masuk,
            $row->transaksi_keluar,
            $status
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2E75B6']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ]
            ],
            'C:E' => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
            ],
            'F:I' => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }
}