<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Data User Admin
        User::create([
            'nama_user' => 'Administrator',
            'username'  => 'admin',
            'password'  => Hash::make('admin123'), // Password disandikan
            'role'      => 'admin',
        ]);

        // Data User Staff/Gudang
        User::create([
            'nama_user' => 'Pegawai',
            'username'  => 'pegawai',
            'password'  => Hash::make('pegawai123'),
            'role'      => 'pegawai',
        ]);

        // Data User Kepala Sekolah
        User::create([
            'nama_user' => 'Kepala Sekolah',
            'username'  => 'kepsek',
            'password'  => Hash::make('kepsek123'),
            'role'      => 'kepsek',
        ]);
    }
}