<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->id();
            
            // 👤 Informasi Pihak
            $table->string('nama'); // Nama orang/lembaga
            $table->string('telepon')->nullable(); // No telepon (opsional)
            
            // 💰 Informasi Keuangan
            $table->enum('tipe', ['hutang', 'piutang']); // hutang = kita berhutang, piutang = kita meminjamkan
            $table->decimal('nominal', 15, 2); // Jumlah nominal
            $table->text('keterangan'); // Keterangan transaksi
            
            // 📅 Informasi Tanggal
            $table->date('tanggal_transaksi'); // Tanggal transaksi
            $table->date('jatuh_tempo')->nullable(); // Jatuh tempo (opsional)
            
            $table->timestamps();
            
            // Index untuk performa
            $table->index(['nama', 'tipe']);
            $table->index('jatuh_tempo');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ledger_entries');
    }
};