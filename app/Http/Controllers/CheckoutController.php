<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Keranjang;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        // Ambil dari DB
        $cartItems = Keranjang::where('user_id', Auth::id())->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('keranjang.index')->with('error', 'Keranjang kosong.');
        }

        $total = $cartItems->sum(fn($item) => $item->product->harga * $item->jumlah);

        // Kirim $cartItems (bukan $cart)
        return view('customer.pages.checkout', compact('cartItems', 'total'));
    }
    // ✅ Proses checkout dan simpan transaksi
    public function proses(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'metode_pembayaran' => 'required|string',
            'bukti_pembayaran' => 'required_unless:metode_pembayaran,cod|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Ambil Data dari Database Keranjang
        $cartItems = Keranjang::where('user_id', Auth::id())->with('product')->get();
        
        // Cek jika kosong (mencegah error jika user inject URL)
        if ($cartItems->isEmpty()) {
            return redirect()->route('keranjang.index')->with('error', 'Keranjang kosong.');
        }

        $total = $cartItems->sum(fn($item) => $item->product->harga * $item->jumlah);

        try {
            DB::beginTransaction();
            
            // 3. Upload Bukti Pembayaran (Jika Ada)
            $buktiPath = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $buktiPath = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
            }

            // 4. Tentukan Status Awal
            $status = ($request->metode_pembayaran == 'cod') 
                        ? 'Akan Diproses' 
                        : 'Menunggu Konfirmasi';

            // 5. BUAT TRANSAKSI UTAMA (Ini yang hilang sebelumnya)
            $transaksi = Transaksi::create([
                'user_id' => Auth::id(),
                'metode_pembayaran' => $request->metode_pembayaran,
                'bukti_pembayaran' => $buktiPath,
                'total' => $total,
                'status' => $status,
            ]);

            // 6. Simpan Detail & Kurangi Stok
            foreach ($cartItems as $item) {
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id, // Sekarang ini tidak akan merah lagi
                    'produk_id' => $item->product_id,
                    'jumlah' => $item->jumlah,
                    'harga' => $item->product->harga,
                    'catatan' => $item->custom_deskripsi
                ]);

                // Kurangi Stok (Kecuali Custom Cake)
                if ($item->custom_deskripsi == null) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->stok -= $item->jumlah;
                        $product->save();
                    }
                }
            }

            // 7. HAPUS ISI KERANJANG DARI DB SETELAH SELESAI
            Keranjang::where('user_id', Auth::id())->delete();

            DB::commit(); // Simpan perubahan

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua jika error
            return redirect()->route('keranjang.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
        
        return redirect()->route('customer.pesanan.index')->with('success', 'Pesanan berhasil dibuat!');
    }
}

