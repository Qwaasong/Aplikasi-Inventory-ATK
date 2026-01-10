<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    public function index()
{
    return view('produk.index');
}


    public function create()
    {
        $kategoriOptions = Product::$kategoriOptions;
        $satuanPackOptions = Product::$satuanPackOptions;

        return view('produk.store', compact('kategoriOptions', 'satuanPackOptions'));
    }
    /**
     * Simpan produk baru ke database.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'nama' => 'required|string|max:255',
        'satuan_pack' => 'required|string',
        'kategori' => 'required|string',
        'isi_per_pack' => 'nullable|integer',
    ]);

    Product::create($validated);

    if ($request->has('save_and_create')) {
        return redirect()->route('produk.create')->with('success', 'Produk berhasil disimpan!');
    }

    return redirect()->route('produk.index')->with('success', 'Produk berhasil disimpan!');
}


    /**
     * Tampilkan halaman edit produk.
     */
    public function edit($id)
{
    $product = Product::findOrFail($id);
    $kategoriOptions = Product::$kategoriOptions;
    $satuanPackOptions = Product::$satuanPackOptions;

    return view('produk.edit', compact('product', 'kategoriOptions', 'satuanPackOptions'));
}


    /**
     * Update data produk yang sudah ada.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:1000',
            'isi_per_pack' => 'required|integer|min:1',
            'satuan_pack' => 'required|string|max:50',
        ]);

        $product->update($request->all());

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui.');
    }
}