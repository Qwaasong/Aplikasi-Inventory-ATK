<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Fungsi index dan destroy diletakkan di API (serupa dengan BarangApiController)
    
    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        User::create([
            'nama_user' => $request->nama_user,
            'role'      => $request->role,
            'username'  => $request->username,
            'password'  => Hash::make($request->password),
        ]);
        return redirect()->back()->with('success', 'User berhasil dibuat');
    }
}