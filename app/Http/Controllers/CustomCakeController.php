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
        // --- PERBAIKAN: Cari ID secara dinamis (Anti Error) ---
        // Jika produk "Kue Kustom" terhapus, sistem otomatis buat baru sekarang juga.
        $product = Product::firstOrCreate(
            ['nama_produk' => 'Kue Kustom'], // Cari nama ini
            [
                'harga' => 65000,      // Default jika dibuat baru
                'stok' => 9999,
                'berat' => 1000,
                'deskripsi' => 'Base product for custom cake',
                'jenis_id' => 10,       // Pastikan ada kategori ID 1
                'gambar' => 'gambar/uaw.jpg' 
            ]
        );

        // Kirim data produk ke view
        return view('customer.produk.custom', compact('product'));
    }

    
}
