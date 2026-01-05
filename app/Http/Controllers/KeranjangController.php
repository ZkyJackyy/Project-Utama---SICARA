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

        // 1. Cek Ketersediaan Stok (JANGAN KURANGI DB)
        if ($jumlah > $product->stok) {
            return back()->with('error', 'Stok produk tidak mencukupi.');
        }

        // 2. Logika Simpan/Update Keranjang
        $existingItem = Keranjang::where('user_id', $userId)
            ->where('product_id', $id)
            ->whereNull('custom_deskripsi')
            ->first();

        if ($existingItem) {
            // Cek stok akumulasi
            if (($existingItem->jumlah + $jumlah) > $product->stok) {
                return back()->with('error', 'Stok toko tidak cukup jika ditambah dengan yang ada di keranjangmu.');
            }
            $existingItem->jumlah += $jumlah;
            $existingItem->save();
        } else {
            Keranjang::create([
                'user_id' => $userId,
                'product_id' => $id,
                'jumlah' => $jumlah
            ]);
        }

        // 3. Redirect Balik (Bukan ke Checkout)
        return redirect()->route('keranjang.index')->with('success', 'Produk berhasil masuk keranjang!');
    }

    // 🍰 Tambah Produk Custom
public function tambahCustom(Request $request)
    {
        $request->validate([
            'ukuran' => 'required',
            'rasa' => 'required',
            'final_price' => 'required|numeric'
        ]);

        // --- LOGIKA SELF-HEALING ---
        // Cari produk "Kue Kustom", kalau tidak ada buat baru.
        // Jadi kita tidak peduli ID-nya 23, 24, atau 100.
        $baseProduct = Product::firstOrCreate(
            ['nama_produk' => 'Kue Kustom'],
            [
                'harga' => 10000,
                'stok' => 9999,
                'berat' => 1000,
                'deskripsi' => 'Base product for custom cake',
                'jenis_id' => 10,
                'gambar' => 'gambar/uaw.jpg'
            ]
        );

        $idProdukDasar = $baseProduct->id; // Ambil ID dinamis

        // Susun Deskripsi
        $deskripsi = "Ukuran: " . $request->ukuran . ", Rasa: " . $request->rasa;
        if ($request->has('toppings')) {
             $toppings = is_array($request->toppings) ? implode(', ', $request->toppings) : $request->toppings;
             $deskripsi .= ", Topping: " . $toppings;
        }
        if ($request->tulisan) {
            $deskripsi .= ", Tulisan: '" . $request->tulisan . "'";
        }

        // Simpan
        $newItem = Keranjang::create([
            'user_id' => Auth::id(),
            'product_id' => $idProdukDasar,
            'jumlah' => 1,
            'custom_deskripsi' => $deskripsi,
            'custom_price' => $request->final_price
        ]);

        return redirect()->route('checkout', ['selected_ids' => $newItem->id]);
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
