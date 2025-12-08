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

    
}
