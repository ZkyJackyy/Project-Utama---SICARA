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
    public function index(Request $request)
    {
        // Ambil ID dari URL (?selected_ids=1,2,3)
        $selectedIds = explode(',', $request->query('selected_ids', ''));

        // Validasi: Pastikan ID valid dan milik user yang sedang login
        $cartItems = Keranjang::where('user_id', Auth::id())
                              ->whereIn('id', $selectedIds) // Filter berdasarkan ID terpilih
                              ->with('product')
                              ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('keranjang.index')->with('error', 'Silakan pilih produk yang ingin dibeli.');
        }

        // Hitung Total (Cek apakah ada harga custom)
        $total = $cartItems->sum(function($item) {
            $harga = $item->custom_price ?? $item->product->harga;
            return $harga * $item->jumlah;
        });

        // Kirim string ID agar bisa diproses di form selanjutnya
        $selectedIdsString = implode(',', $selectedIds);

        return view('customer.pages.checkout', compact('cartItems', 'total', 'selectedIdsString'));
    }

    // 2. PROSES TRANSAKSI (DATABASE + WHATSAPP)
    public function proses(Request $request)
    {
        $request->validate([
            'metode_pembayaran' => 'required|string',
            'bukti_pembayaran' => 'required_unless:metode_pembayaran,cod|image|mimes:jpeg,png,jpg|max:2048',
            'selected_ids' => 'required|string', 
        ]);

        $selectedIds = explode(',', $request->selected_ids);

        $cartItems = Keranjang::where('user_id', Auth::id())
                              ->whereIn('id', $selectedIds)
                              ->with('product')
                              ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('keranjang.index')->with('error', 'Terjadi kesalahan data keranjang.');
        }

        $total = $cartItems->sum(function($item) {
            $harga = $item->custom_price ?? $item->product->harga;
            return $harga * $item->jumlah;
        });

        try {
            DB::beginTransaction();
            
            $buktiPath = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $buktiPath = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
            }

            $status = ($request->metode_pembayaran == 'cod') ? 'Akan Diproses' : 'Menunggu Konfirmasi';

            $transaksi = Transaksi::create([
                'user_id' => Auth::id(),
                'metode_pembayaran' => $request->metode_pembayaran,
                'bukti_pembayaran' => $buktiPath,
                'total' => $total,
                'status' => $status,
            ]);

            // === 1. SIAPKAN VARIABEL PENANDA ===
            $adaCustomCake = false; 
            
            // Siapkan string pesan WA (jaga-jaga kalau nanti dipakai)
            $waMessage = "Halo Admin DaraCake, saya baru saja membuat pesanan.%0A%0A";
            $waMessage .= "🧾 *ID Pesanan:* #{$transaksi->id}%0A";
            $waMessage .= "👤 *Nama:* " . Auth::user()->name . "%0A";
            $waMessage .= "💰 *Total:* Rp " . number_format($total, 0, ',', '.') . "%0A";
            $waMessage .= "💳 *Metode:* " . strtoupper($request->metode_pembayaran) . "%0A%0A";
            $waMessage .= "*Detail Pesanan:*%0A";

            foreach ($cartItems as $item) {
                $hargaFinal = $item->custom_price ?? $item->product->harga;

                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $item->product_id,
                    'jumlah' => $item->jumlah,
                    'harga' => $hargaFinal,
                    'catatan' => $item->custom_deskripsi 
                ]);

                if ($item->custom_deskripsi == null) {
                    $product = Product::find($item->product_id);
                    if($product) {
                        $product->stok -= $item->jumlah;
                        $product->save();
                    }
                } else {
                    // === 2. JIKA ADA DESKRIPSI, TANDAI SEBAGAI CUSTOM ===
                    $adaCustomCake = true;
                }

                // Tambahkan ke String WA
                $waMessage .= "- " . $item->product->nama_produk . " (x{$item->jumlah})%0A";
                if($item->custom_deskripsi) {
                    $waMessage .= "  _Detail: {$item->custom_deskripsi}_%0A";
                }
            }

            $waMessage .= "%0AMohon diproses ya kak, terima kasih! 🙏";

            Keranjang::where('user_id', Auth::id())
                     ->whereIn('id', $selectedIds)
                     ->delete();

            DB::commit(); 

            // === 3. LOGIKA REDIRECT ===
            if ($adaCustomCake) {
                // Jika ada kue custom, arahkan ke WhatsApp
                $adminNumber = '62895611194900'; 
                return redirect("https://wa.me/{$adminNumber}?text=" . urlencode($waMessage));
            } else {
                // Jika TIDAK ada kue custom (semua produk biasa), arahkan ke Pesanan Saya
                return redirect()->route('customer.pesanan.index')->with('success', 'Pesanan berhasil dibuat!');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('keranjang.index')->with('error', 'Error: ' . $e->getMessage());
        }
    }
}

