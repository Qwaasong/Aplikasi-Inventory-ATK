<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UserListExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return User::query()->select('nama_user', 'username', 'role', 'created_at')->orderBy('nama_user', 'asc');
    }

    /**
     * @var User $user
     */
    public function map($user): array
    {
        return [
            $user->nama_user,
            $user->username,
            $user->role,
            $user->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function headings(): array
    {
        return [
            'Nama User',
            'Username',
            'Role (Admin/Role 02/03)',
            'Tanggal Dibuat',
        ];
    }
}
