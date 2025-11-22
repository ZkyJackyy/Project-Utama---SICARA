<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    // 🛒 Menampilkan isi keranjang
    public function index()
    {
        $userId = Auth::id();
        
        // Ambil data keranjang milik user dari database
        $cartItems = Keranjang::where('user_id', $userId)->with('product')->get();

        // Hitung Total
        $total = $cartItems->sum(function ($item) {
            return $item->product->harga * $item->jumlah;
        });

        // Validasi Stok Otomatis (Hapus item jika produk dihapus admin / stok habis)
        foreach ($cartItems as $item) {
            if (!$item->product) {
                $item->delete(); // Hapus jika produk induk hilang
                return redirect()->route('keranjang.index')->with('warning', 'Produk tidak tersedia dan dihapus.');
            }
            // Cek stok (kecuali custom cake yg diasumsikan stoknya selalu ada/banyak)
            if ($item->custom_deskripsi == null && $item->jumlah > $item->product->stok) {
                $item->jumlah = $item->product->stok;
                $item->save();
                return redirect()->route('keranjang.index')->with('warning', 'Jumlah disesuaikan dengan stok tersedia.');
            }
        }

        return view('customer.pages.keranjang', compact('cartItems', 'total'));
    }

    // ➕ Tambah Produk Biasa
    public function tambah(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $jumlah = max(1, (int) $request->jumlah);
        $userId = Auth::id();

        // Cek Stok
        if ($jumlah > $product->stok) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        // Cek apakah produk sudah ada di keranjang user ini
        $existingItem = Keranjang::where('user_id', $userId)
                                 ->where('product_id', $id)
                                 ->whereNull('custom_deskripsi') // Pastikan bukan produk custom
                                 ->first();

        if ($existingItem) {
            // Jika ada, update jumlahnya
            if (($existingItem->jumlah + $jumlah) > $product->stok) {
                return back()->with('error', 'Stok tidak cukup untuk menambah lagi.');
            }
            $existingItem->jumlah += $jumlah;
            $existingItem->save();
        } else {
            // Jika belum ada, buat baru
            Keranjang::create([
                'user_id' => $userId,
                'product_id' => $id,
                'jumlah' => $jumlah
            ]);
        }

        if ($request->action == 'buy_now') {
            return redirect()->route('checkout');
        }

        return redirect()->route('keranjang.index')->with('success', 'Produk masuk keranjang!');
    }

    // 🍰 Tambah Produk Custom
    public function tambahCustom(Request $request)
    {
        $idProdukDasar = 23; // ID Kue Kustom Anda
        $baseProduct = Product::find($idProdukDasar);

        if (!$baseProduct) {
             return back()->with('error', 'Produk dasar tidak ditemukan.');
        }

        // Buat Deskripsi
        $deskripsi = "Ukuran: " . $request->ukuran . ", Rasa: " . $request->rasa;
        if ($request->has('toppings')) {
            $deskripsi .= ", Topping: " . implode(', ', $request->toppings);
        }
        if ($request->tulisan) {
            $deskripsi .= ", Tulisan: '" . $request->tulisan . "'";
        }

        // Simpan ke Database
        // Untuk custom, kita selalu buat baris baru (Create) jangan di-merge
        Keranjang::create([
            'user_id' => Auth::id(),
            'product_id' => $idProdukDasar,
            'jumlah' => 1,
            'custom_deskripsi' => $deskripsi
        ]);

        return redirect()->route('keranjang.index')->with('success', 'Kue kustom berhasil ditambahkan!');
    }

    // 🔄 Update Jumlah (AJAX Support)
    public function update(Request $request, $id)
    {
        // Cari item berdasarkan ID tabel keranjang
        $cartItem = Keranjang::where('user_id', Auth::id())->where('id', $id)->first();

        if (!$cartItem) {
            return response()->json(['status' => 'error', 'message' => 'Item tidak ditemukan'], 404);
        }

        $jumlah_baru = (int) $request->jumlah;

        // Validasi Stok (Jika bukan custom)
        if ($cartItem->custom_deskripsi == null) {
            if ($jumlah_baru > $cartItem->product->stok) {
                return response()->json(['status' => 'error', 'message' => 'Stok mentok!'], 400);
            }
        }

        $cartItem->jumlah = $jumlah_baru;
        $cartItem->save();

        // Hitung Total Baru untuk respon AJAX
        $allCart = Keranjang::where('user_id', Auth::id())->with('product')->get();
        $newTotal = $allCart->sum(fn($item) => $item->product->harga * $item->jumlah);

        return response()->json([
            'status' => 'success',
            'formatted_total' => number_format($newTotal, 0, ',', '.')
        ]);
    }

    // ❌ Hapus Item (AJAX Support)
    public function hapus(Request $request, $id)
    {
        $cartItem = Keranjang::where('user_id', Auth::id())->where('id', $id)->first();
        
        if ($cartItem) {
            $cartItem->delete();
        }

        // Hitung ulang total
        $allCart = Keranjang::where('user_id', Auth::id())->with('product')->get();
        $newTotal = $allCart->sum(fn($item) => $item->product->harga * $item->jumlah);
        $count = $allCart->count();

        return response()->json([
            'status' => 'success',
            'formatted_total' => number_format($newTotal, 0, ',', '.'),
            'cart_count' => $count
        ]);
    }
}
