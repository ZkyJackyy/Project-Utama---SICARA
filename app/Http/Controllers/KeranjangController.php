<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class KeranjangController extends Controller
{
    // 🛒 Menampilkan isi keranjang
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = collect($cart)->sum(function ($item) {
            return $item['harga'] * $item['jumlah'];
        });

        return view('customer.pages.keranjang', compact('cart', 'total'));
    }

    // ➕ Tambah produk ke keranjang
    public function tambah(Request $request, $id)
{
    $product = Product::findOrFail($id);
    $cart = session()->get('cart', []);

    $jumlah = max(1, (int) $request->jumlah);

    if (isset($cart[$id])) {
        $cart[$id]['jumlah'] += $jumlah;
    } else {
        $cart[$id] = [
            'id' => $product->id,
            'nama_produk' => $product->nama_produk,
            'harga' => $product->harga,
            'image_url' => $product->gambar ? asset('storage/produk/' . $product->gambar) : '/images/default.jpg',
            'jumlah' => $jumlah,
        ];
    }

    session()->put('cart', $cart);

    return redirect()->route('keranjang.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
}


    // 🔄 Update jumlah produk di keranjang
    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['jumlah'] = (int) $request->jumlah;
            session()->put('cart', $cart);
        }

        return redirect()->route('keranjang.index')->with('success', 'Jumlah produk diperbarui.');
    }

    // ❌ Hapus item dari keranjang
    public function hapus($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('keranjang.index')->with('success', 'Produk dihapus dari keranjang.');
    }
}
