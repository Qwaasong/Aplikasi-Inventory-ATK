<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->enum('tipe', ['pemasukan', 'pengeluaran']);
            $table->text('keterangan');
            $table->decimal('jumlah', 15, 2);
            $table->unsignedBigInteger('purchase_id')->nullable();
            $table->unsignedBigInteger('stock_out_id')->nullable();
            $table->timestamps();
            
            // Foreign key constraints (commented out if tables don't exist yet)
            // $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');
            // $table->foreign('stock_out_id')->references('id')->on('stock_outs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_transactions');
    }
};
