<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            // 1. Hapus foreign key yang lama (cascade)
            $table->dropForeign(['id_kategori']);

            // 2. Ubah kolom id_kategori agar boleh kosong (nullable)
            $table->unsignedBigInteger('id_kategori')->nullable()->change();

            // 3. Tambahkan kembali foreign key dengan aturan ON DELETE SET NULL
            $table->foreign('id_kategori')
                ->references('id_kategori')
                ->on('kategori')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropForeign(['id_kategori']);

            // Kembalikan ke pengaturan awal jika rollback
            $table->unsignedBigInteger('id_kategori')->nullable(false)->change();
            $table->foreign('id_kategori')
                ->references('id_kategori')
                ->on('kategori')
                ->onDelete('cascade');
        });
    }
};