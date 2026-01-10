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
        Schema::table('financial_transactions', function (Blueprint $table) {
            // Tambahkan kolom foreign key baru
            $table->foreignId('ledger_entry_id')
                  ->nullable() // Gunakan nullable() agar data lama tidak error
                  ->after('stock_out_id') // Opsional: menempatkannya setelah kolom lain
                  ->constrained('ledger_entries') // Menghubungkan ke tabel 'ledger_entries'
                  ->onDelete('set null'); // Jika 'LedgerEntry' terhapus, set kolom ini ke NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('financial_transactions', function (Blueprint $table) {
            // Ini untuk membatalkan (rollback) migrasi
            $table->dropForeign(['ledger_entry_id']);
            $table->dropColumn('ledger_entry_id');
        });
    }
};