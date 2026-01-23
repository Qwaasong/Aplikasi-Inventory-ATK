<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'id_user';

    // 1. Tambahkan ini agar 'id' muncul di JSON response
    protected $appends = ['id'];

    protected $fillable = [
        'role',
        'nama_user',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    // 2. Tambahkan fungsi ini (Accessor)
    // Fungsi ini membuat alias: saat JS minta 'id', laravel kasih 'id_user'
    public function getIdAttribute()
    {
        return $this->id_user;
    }
}