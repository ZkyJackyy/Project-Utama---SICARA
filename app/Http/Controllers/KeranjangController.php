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

          // Cek ulang stok saat melihat keranjang
          $stok_berubah = false;
          foreach ($cart as $id => $item) {
               
               // === TAMBAHAN UNTUK FIX BUG ===
               // Jika ini adalah produk kustom, lewati pengecekan stok
               if (isset($item['produk_id_dasar'])) {
                    continue; // Lanjut ke item berikutnya
               }
               // === AKHIR TAMBAHAN ===

               $product = Product::find($id); // Ini sekarang aman
               if (!$product) {
                    unset($cart[$id]); 
                    $stok_berubah = true;
               } elseif ($item['jumlah'] > $product->stok) {
                    $cart[$id]['jumlah'] = $product->stok; // Set ke stok maks
                    $stok_berubah = true;
               }
          }

          if ($stok_berubah) {
               session()->put('cart', $cart);
               // Hitung ulang total jika ada perubahan
               $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['jumlah']);
               return view('customer.pages.keranjang', compact('cart', 'total'))->with('warning', 'Stok beberapa produk telah disesuaikan.');
          }

          return view('customer.pages.keranjang', compact('cart', 'total'));
     }

    // ➕ Tambah produk ke keranjang (DENGAN VALIDASI STOK)
    public function tambah(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $jumlah_baru = max(1, (int) $request->jumlah);
        $action = $request->input('action');
        $cart = session()->get('cart', []);

        // Tentukan jumlah yang ada di keranjang sebelumnya
        $jumlah_di_keranjang = 0;
        if ($action == 'add_to_cart' && isset($cart[$id])) {
            $jumlah_di_keranjang = $cart[$id]['jumlah'];
        }

        // Jika 'Beli Sekarang', keranjang direset
        if ($action == 'buy_now') {
            session()->forget('cart');
            $cart = [];
            $jumlah_di_keranjang = 0;
        }

        // Hitung total yang diminta
        $total_diminta = $jumlah_di_keranjang + $jumlah_baru;

        // --- 🔒 VALIDASI STOK ---
        if ($total_diminta > $product->stok) {
            return redirect()->back()
                ->with('error', 'Stok tidak mencukupi! Sisa stok untuk ' . $product->nama_produk . ' hanya ' . $product->stok . ' unit.')
                ->withInput();
        }
        // --- AKHIR VALIDASI ---

        // Lolos validasi, tambahkan ke keranjang
        $cart[$id] = [
            'id' => $product->id,
            'nama_produk' => $product->nama_produk,
            'harga' => $product->harga,
            'image_url' => $product->gambar ? asset('storage/produk/' . $product->gambar) : '/images/default.jpg',
            'jumlah' => $total_diminta, // Set jumlah total yang sudah divalidasi
        ];

        session()->put('cart', $cart);

        // Redirect berdasarkan aksi
        if ($action == 'buy_now') {
            return redirect()->route('checkout');
        }

        return redirect()->route('keranjang.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }


    // 🔄 Update jumlah produk (DENGAN VALIDASI STOK)
    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        $jumlah_diminta = (int) $request->jumlah;

        // Validasi minimal 1
        if ($jumlah_diminta < 1) {
            return redirect()->route('keranjang.index')->with('error', 'Jumlah minimal adalah 1.');
        }

        // Cek produk dan stoknya
        $product = Product::find($id);

        if (isset($cart[$id]) && $product) {
            
            // --- 🔒 VALIDASI STOK ---
            if ($jumlah_diminta > $product->stok) {
                // Jangan perbarui, kembalikan dengan pesan error
                return redirect()->back()
                    ->with('error', 'Stok tidak mencukupi! Sisa stok untuk ' . $product->nama_produk . ' hanya ' . $product->stok . ' unit.');
            }
            // --- AKHIR VALIDASI ---

            // Lolos validasi, update keranjang
            $cart[$id]['jumlah'] = $jumlah_diminta;
            session()->put('cart', $cart);
            return redirect()->route('keranjang.index')->with('success', 'Jumlah produk diperbarui.');

        } elseif (!$product) {
            // Jika produk tiba-tiba dihapus oleh admin
            unset($cart[$id]);
            session()->put('cart', $cart);
            return redirect()->route('keranjang.index')->with('error', 'Produk tidak lagi tersedia dan telah dihapus dari keranjang.');
        }

        return redirect()->route('keranjang.index');
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


    public function tambahCustom(Request $request)
    {
        $cart = session()->get('cart', []);
        
        // Buat ID unik untuk item keranjang kustom ini
        $customId = 'custom_' . time(); 
        
        // Kumpulkan semua deskripsi
        $deskripsi = "Ukuran: " . $request->ukuran . ", Rasa: " . $request->rasa;
        
        if ($request->has('toppings')) {
            $deskripsi .= ", Topping: " . implode(', ', $request->toppings);
        }
        if ($request->tulisan) {
            $deskripsi .= ", Tulisan: '" . $request->tulisan . "'";
        }

        // Ambil gambar dari produk dasar (ID 50)
        // GANTI 50 DENGAN ID PRODUK "Kue Kustom" ANDA
        $baseProduct = Product::find(23);
        $imageUrl = $baseProduct ? asset('storage/produk/' . $baseProduct->gambar) : '/images/default.jpg';

        $cart[$customId] = [
            'id' => $customId, // Gunakan ID unik
            'produk_id_dasar' => $baseProduct->id, // Simpan ID produk asli
            'nama_produk' => 'Kue Kustom (' . $request->ukuran . ')',
            'harga' => $request->final_price, // Ambil harga final dari form
            'jumlah' => 1,
            'image_url' => $imageUrl,
            'custom_deskripsi' => $deskripsi // Simpan deskripsi kustom
        ];
        
        session()->put('cart', $cart);

        return redirect()->route('keranjang.index')->with('success', 'Kue kustom berhasil ditambahkan ke keranjang!');
    }
}
