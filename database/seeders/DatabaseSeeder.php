<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// use App\Models\User; // <-- Baris ini boleh dikomentari/dihapus

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- HAPUS ATAU KOMENTARI BAGIAN INI ---
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // ----------------------------------------

        // --- GANTI DENGAN INI ---
        $this->call([
            KategoriSeeder::class,
            BarangSeeder::class,
            UserSeeder::class,
        ]);
    }
}