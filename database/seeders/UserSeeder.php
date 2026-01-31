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
            'username' => 'admin',
            'password' => Hash::make('admin123'), // Password disandikan
            'role' => 'admin',
        ]);

        // Data User Staff/Gudang
        User::create([
            'nama_user' => 'Staff',
            'username' => 'staff',
            'password' => Hash::make('staff123'),
            'role' => 'staff',
        ]);

        // Data User Supervisor
        User::create([
            'nama_user' => 'Supervisor',
            'username' => 'supervisor',
            'password' => Hash::make('supervisor123'),
            'role' => 'supervisor',
        ]);

    }
}