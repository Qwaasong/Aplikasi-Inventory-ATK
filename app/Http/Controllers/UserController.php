<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class USERController extends Controller
{
    // Fungsi index dan destroy diletakkan di API (serupa dengan BarangApiController)
    
    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'nama_user' => 'required|string|max:255',
        'username'  => 'required|string|unique:user,username', // Pastikan tabelnya 'user' atau 'users'
        'password'  => 'required|string|min:6',
        'role'      => 'required'
    ]);

    User::create([
        'nama_user' => $request->nama_user,
        'role'      => $request->role,
        'username'  => $request->username,
        'password'  => Hash::make($request->password),
    ]);
    
    return redirect()->route('user.index')->with('success', 'User berhasil dibuat');
}
}