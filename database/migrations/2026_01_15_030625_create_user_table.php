<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->id('id_user');
            // Role akan menyimpan 'ADMIN', 'ROLE 02', atau 'ROLE 03' sesuai diagram
            $table->string('role');
            $table->string('nama_user');
            $table->string('username')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
