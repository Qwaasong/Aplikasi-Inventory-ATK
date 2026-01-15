<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $kategori = [
            ['nama_kategori' => 'Kertas & Amplop', 'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Alat Tulis (Pena/Pensil)', 'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Pengarsipan (Map/Binder)', 'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Buku & Catatan', 'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Peralatan Meja (Stapler/Lem)', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('kategori')->insert($kategori);
    }
}