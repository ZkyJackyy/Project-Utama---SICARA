<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
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
        // --- PERUBAHAN 1: Validasi ---
        // Buat 'bukti_pembayaran' hanya wajib JIKA metode_pembayaran BUKAN 'cod'
        $request->validate([
            'metode_pembayaran' => 'required|string',
            'bukti_pembayaran' => 'required_unless:metode_pembayaran,cod|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $cart = session()->get('cart', []);
        $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['jumlah']);

        $buktiPath = null;
        
        // --- PERUBAHAN 2: Upload Bukti Pembayaran (Hanya jika ada) ---
        // Cek apakah user mengupload file (pasti bukan COD jika ada)
        if ($request->hasFile('bukti_pembayaran')) {
            $buktiPath = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
        }

        // --- PERUBAHAN 3: Tentukan Status ---
        // Jika COD, status bisa langsung 'Akan Diproses'
        // Jika bukan COD, status 'Menunggu Konfirmasi'
        $status = ($request->metode_pembayaran == 'cod') 
                    ? 'Akan Diproses' // Status untuk COD
                    : 'Menunggu Konfirmasi'; // Status untuk Transfer/QRIS

        // Simpan transaksi utama
        $transaksi = Transaksi::create([
            'user_id' => Auth::id(),
            'metode_pembayaran' => $request->metode_pembayaran,
            'bukti_pembayaran' => $buktiPath, // Akan 'null' jika COD
            'total' => $total,
            'status' => $status, // Gunakan status yang dinamis
        ]);

        // Simpan detail transaksi (Tidak ada perubahan di sini)
        foreach ($cart as $item) {
            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'produk_id' => $item['id'],
                'jumlah' => $item['jumlah'],
                'harga' => $item['harga'],
            ]);
        }

        // Kosongkan keranjang
        session()->forget('cart');

        return redirect()->route('dashboard')->with('success', 'Pesanan berhasil dibuat!');
    }
}

