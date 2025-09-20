<?php

namespace App\Http\Controllers;

use App\Models\Jenis;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('jenis')->latest()->paginate(5);
        return view('admin.daftar_produk', compact('products'));
    }

    public function create()
    {
        return view('admin.produk.tambah');
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $validatedData = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'jenis_id'    => 'required|exists:jenis,id',
            'harga'       => 'required|integer|min:0',
            'stok'        => 'required|integer|min:0',
            'gambar'      => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // max 2MB
            'deskripsi'   => 'nullable|string',
        ]);

        // 2. Handle Upload Gambar
        $path = $request->file('gambar')->store('public/produk');
        $validatedData['gambar'] = basename($path);

        Product::create($validatedData);

        // 4. Redirect dengan Pesan Sukses
        return redirect()->route('produk.index')->with('success', 'Produk baru berhasil ditambahkan!');
    }

    public function edit(Product $product) {
        $jenisProduk= Jenis::all();
        return view('admin.produk.edit', compact('product', 'jenisProduk'));

    }

    public function update(Request $request, Product $product)
{
    $validatedData = $request->validate([
        'nama_produk' => 'required|string|max:255',
        'jenis_id'    => 'required|exists:jenis,id',
        'harga'       => 'required|integer|min:0',
        'stok'        => 'required|integer|min:0',
        'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'deskripsi'   => 'nullable|string',
    ]);

    // ✅ Update gambar kalau ada file baru
    if ($request->hasFile('gambar')) {
        // Hapus gambar lama kalau masih ada
        if ($product->gambar && Storage::exists('public/produk/' . $product->gambar)) {
            Storage::delete('public/produk/' . $product->gambar);
        }

        // Simpan gambar baru
        $path = $request->file('gambar')->store('public/produk');
        $validatedData['gambar'] = basename($path);
    }

    // ✅ Update data ke database
    $product->update($validatedData);

    return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui!');
}

    public function destroy( Product $product) {
        try {
        // Hapus gambar jika ada
        if ($product->gambar) {
            Storage::delete('public/produk/' . $product->gambar);
        }

        // Hapus data produk dari database
        $product->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus!');
    } catch (\Exception $e) {
        return redirect()->route('produk.index')->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
    }
    }

    public function indexHome()
    {
        $products = Product::latest()->take(4)->get();
        return view('dashboard', compact('products'));
    }


}
