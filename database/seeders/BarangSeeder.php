<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // 1. Ambil ID Kategori berdasarkan nama (agar aman jika ID acak)
        $catKertas = DB::table('kategori')->where('nama_kategori', 'Kertas & Amplop')->value('id_kategori');
        $catTulis  = DB::table('kategori')->where('nama_kategori', 'Alat Tulis (Pena/Pensil)')->value('id_kategori');
        $catArsip  = DB::table('kategori')->where('nama_kategori', 'Pengarsipan (Map/Binder)')->value('id_kategori');
        $catBuku   = DB::table('kategori')->where('nama_kategori', 'Buku & Catatan')->value('id_kategori');
        $catMeja   = DB::table('kategori')->where('nama_kategori', 'Peralatan Meja (Stapler/Lem)')->value('id_kategori');

        $barang = [
            // Kategori: Kertas
            [
                'nama_barang' => 'Kertas HVS A4 70gsm (Sidu)',
                'id_kategori' => $catKertas,
                'nama_pack'   => 'Rim',
                'jumlah_pack' => 50,  // Stok 50 Rim
                'jumlah_pcs'  => 500, // 1 Rim = 500 Lembar
                'created_at'  => $now, 'updated_at' => $now
            ],
            [
                'nama_barang' => 'Kertas HVS F4 75gsm (PaperOne)',
                'id_kategori' => $catKertas,
                'nama_pack'   => 'Rim',
                'jumlah_pack' => 30,
                'jumlah_pcs'  => 500,
                'created_at'  => $now, 'updated_at' => $now
            ],

            // Kategori: Alat Tulis
            [
                'nama_barang' => 'Bolpen Standard AE7 Hitam',
                'id_kategori' => $catTulis,
                'nama_pack'   => 'Box', // Atau Lusin
                'jumlah_pack' => 100, // Stok 100 Box
                'jumlah_pcs'  => 12,  // 1 Box isi 12 Pcs
                'created_at'  => $now, 'updated_at' => $now
            ],
            [
                'nama_barang' => 'Pensil 2B Faber Castell',
                'id_kategori' => $catTulis,
                'nama_pack'   => 'Box',
                'jumlah_pack' => 20,
                'jumlah_pcs'  => 12,
                'created_at'  => $now, 'updated_at' => $now
            ],
            [
                'nama_barang' => 'Spidol Boardmarker Snowman Hitam',
                'id_kategori' => $catTulis,
                'nama_pack'   => 'Box',
                'jumlah_pack' => 25,
                'jumlah_pcs'  => 12, 
                'created_at'  => $now, 'updated_at' => $now
            ],

            // Kategori: Buku
            [
                'nama_barang' => 'Buku Tulis Sidu 38 Lembar',
                'id_kategori' => $catBuku,
                'nama_pack'   => 'Pak',
                'jumlah_pack' => 50,
                'jumlah_pcs'  => 10, // 1 Pak isi 10 Buku
                'created_at'  => $now, 'updated_at' => $now
            ],
            [
                'nama_barang' => 'Buku Hardcover Folio 100 Lembar',
                'id_kategori' => $catBuku,
                'nama_pack'   => 'Pcs',
                'jumlah_pack' => 0, // Dijual satuan, tidak ada pack
                'jumlah_pcs'  => 1,
                'created_at'  => $now, 'updated_at' => $now
            ],

            // Kategori: Pengarsipan
            [
                'nama_barang' => 'Map Snelhecter Plastik (Business File)',
                'id_kategori' => $catArsip,
                'nama_pack'   => 'Pak',
                'jumlah_pack' => 40,
                'jumlah_pcs'  => 12,
                'created_at'  => $now, 'updated_at' => $now
            ],
            [
                'nama_barang' => 'Ordner Bantex Folio',
                'id_kategori' => $catArsip,
                'nama_pack'   => 'Pcs',
                'jumlah_pack' => 0,
                'jumlah_pcs'  => 1, // Satuan
                'created_at'  => $now, 'updated_at' => $now
            ],

            // Kategori: Peralatan Meja
            [
                'nama_barang' => 'Isi Staples (Staples) No. 10',
                'id_kategori' => $catMeja,
                'nama_pack'   => 'Kotak Kecil',
                'jumlah_pack' => 200,
                'jumlah_pcs'  => 1, 
                'created_at'  => $now, 'updated_at' => $now
            ],
            [
                'nama_barang' => 'Lakban Bening 2 Inch',
                'id_kategori' => $catMeja,
                'nama_pack'   => 'Slop', // 1 Slop biasanya 6 rol
                'jumlah_pack' => 10,
                'jumlah_pcs'  => 6,
                'created_at'  => $now, 'updated_at' => $now
            ],
        ];

        DB::table('barang')->insert($barang);
    }
}