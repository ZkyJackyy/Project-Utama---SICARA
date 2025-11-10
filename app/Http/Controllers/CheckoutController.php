<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('keranjang.index')->with('error', 'Keranjang kamu masih kosong.');
        }

        $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['jumlah']);

        return view('customer.pages.checkout', compact('cart', 'total'));
    }

    // ✅ Proses checkout dan simpan transaksi
    public function proses(Request $request)
    {
        $request->validate([
            'metode_pembayaran' => 'required|string',
            'bukti_pembayaran' => 'required_unless:metode_pembayaran,cod|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $cart = session()->get('cart', []);
        $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['jumlah']);

        // --- 🔒 TAHAP 1: VALIDASI STOK FINAL ---
        foreach ($cart as $item) {
            
            // === TAMBAHAN UNTUK FIX BUG ===
            // Jika ini adalah produk kustom, lewati pengecekan stok
            if (isset($item['produk_id_dasar'])) {
                continue; // Lanjut ke item berikutnya
            }
            // === AKHIR TAMBAHAN ===

            $product = Product::find($item['id']); // Ini sekarang aman
            
            if (!$product) {
                return redirect()->route('keranjang.index')
                    ->with('error', 'Produk "' . $item['nama_produk'] . '" tidak lagi tersedia. Pesanan dibatalkan.');
            }
            
            if ($item['jumlah'] > $product->stok) {
                return redirect()->route('keranjang.index')
                    ->with('error', 'Stok untuk "' . $item['nama_produk'] . '" tidak mencukupi (sisa ' . $product->stok . '). Harap update keranjang Anda.');
            }
        }
        // --- Lolos Validasi Stok ---


        // --- TAHAP 2: PROSES TRANSAKSI DENGAN AMAN ---
        try {
            DB::beginTransaction();

            $buktiPath = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $buktiPath = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
            }

            $status = ($request->metode_pembayaran == 'cod') 
                        ? 'Akan Diproses' 
                        : 'Menunggu Konfirmasi';

            // Simpan transaksi utama
            $transaksi = Transaksi::create([
                'user_id' => Auth::id(),
                'metode_pembayaran' => $request->metode_pembayaran,
                'bukti_pembayaran' => $buktiPath,
                'total' => $total,
                'status' => $status,
            ]);

            // Simpan detail transaksi DAN kurangi stok
            foreach ($cart as $item) {
            
                // Tentukan ID produk dan catatan
                $produkId = isset($item['produk_id_dasar']) ? $item['produk_id_dasar'] : $item['id'];
                $catatan = $item['custom_deskripsi'] ?? null;

                 DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $produkId, // Gunakan ID produk yang benar
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga'],
                    'catatan' => $catatan // Simpan deskripsi kustom di sini
                 ]);

                 // Kurangi stok HANYA jika bukan produk kustom
                if (!isset($item['produk_id_dasar'])) {
                     $product = Product::find($produkId);
                     if ($product) {
                         $product->stok -= $item['jumlah']; 
                         $product->save();
                     }
                }
            }

            DB::commit(); // Semua berhasil, simpan perubahan

        } catch (\Exception $e) {
            DB::rollBack(); // Ada error (misal database mati), batalkan semua
            return redirect()->route('keranjang.index')
                ->with('error', 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage());
        }
        
        // Kosongkan keranjang HANYA JIKA berhasil
        session()->forget('cart');

        return redirect()->route('customer.pesanan.index')->with('success', 'Pesanan berhasil dibuat!');
    }
}

