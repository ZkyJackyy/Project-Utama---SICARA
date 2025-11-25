<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomCakeController extends Controller
{

    public function showCustomCakeForm()
    {
        // GANTI 50 DENGAN ID PRODUK "Kue Kustom" ANDA DARI LANGKAH 1
        $product = Product::find(23);

        if (!$product) {
            // Jika produk "Kue Kustom" tidak ditemukan
            return redirect()->route('customer.produk.list')->with('error', 'Halaman kustomisasi tidak tersedia.');
        }

        // Kirim data produk (untuk harga dasar) ke view
        return view('customer.produk.custom', compact('product'));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'nama_produk' => 'required|string',
    //         'ukuran' => 'nullable|string',
    //         'rasa' => 'nullable|string',
    //         'toppings' => 'nullable|string',
    //         'tulisan' => 'nullable|string',
    //         'gambar_referensi' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    //     ]);

    //     // ✅ Simpan ke database
    //     $transaksi = new Transaksi();
    //     $transaksi->user_id = Auth::id() ?? null;
    //     $transaksi->metode_pembayaran = 'Belum Dipilih';
    //     $transaksi->bukti_pembayaran = null;
    //     $transaksi->total = 0;
    //     $transaksi->status = 'Menunggu Konfirmasi';
    //     $transaksi->is_custom = true;
    //     $transaksi->save();

    //     // ✅ Simpan file (kalau diupload)
    //     if ($request->hasFile('gambar_referensi')) {
    //         $path = $request->file('gambar_referensi')->store('referensi', 'public');
    //     }

    //     // ✅ Pesan WhatsApp otomatis
    //     $adminNumber = '6282384522629'; // ganti dengan nomor admin
    //     $message = "*Pesanan Custom Cake DaraCake*%0A"
    //         ."🍰 *Nama Produk:* {$request->nama_produk}%0A"
    //         ."📏 *Ukuran:* {$request->ukuran}%0A"
    //         ."🍫 *Rasa:* {$request->rasa}%0A"
    //         ."🍒 *Topping:* {$request->toppings}%0A"
    //         ."🎂 *Tulisan:* {$request->tulisan}%0A"
    //         ."%0A🧾 *ID Transaksi:* {$transaksi->id}%0A"
    //         ."%0A_Mohon konfirmasi pesanan custom saya, terima kasih!_ 💕";

    //     // ✅ Redirect ke WhatsApp
    //     return redirect("https://wa.me/{$adminNumber}?text={$message}");
    // }
}
