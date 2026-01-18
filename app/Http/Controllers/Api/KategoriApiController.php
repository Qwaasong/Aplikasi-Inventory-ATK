<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriApiController extends Controller
{
    /**
     * Ambil semua data kategori untuk dropdown.
     */
    public function index()
    {
        $kategori = Kategori::all();

        return response()->json([
            'success' => true,
            'data'    => $kategori
        ]);
    }
}
