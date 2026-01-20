<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('riwayat_stok', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_barang');
            $table->enum('jenis', ['masuk', 'keluar']);
            $table->integer('jumlah_pcs'); // Satuan terkecil untuk statistik akurat
            $table->timestamps(); // Digunakan untuk filter waktu (created_at)

            $table->foreign('id_barang')->references('id_barang')->on('barang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_stok');
    }
};
