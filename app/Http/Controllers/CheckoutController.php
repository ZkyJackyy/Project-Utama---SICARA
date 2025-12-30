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
        $user = Auth::user();
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

        // 1. Hitung Total Berat (Default 500g jika null)
        $totalWeight = $cartItems->sum(function ($item) {
            return ($item->product->berat ?? 500) * $item->jumlah;
        });

        // Hitung Total (Cek apakah ada harga custom)
        $total = $cartItems->sum(function ($item) {
            $harga = $item->custom_price ?? $item->product->harga;
            return $harga * $item->jumlah;
        });

        // Kirim string ID agar bisa diproses di form selanjutnya
        $selectedIdsString = implode(',', $selectedIds);

        return view('customer.pages.checkout', compact('cartItems', 'total', 'totalWeight', 'selectedIdsString', 'user'));
    }

    // 2. PROSES TRANSAKSI (DATABASE + WHATSAPP)
    public function proses(Request $request)
    {
        // 1. VALIDASI INPUT
        $request->validate([
            'delivery_type'     => 'required|string', // shipping atau pickup
            'metode_pembayaran' => 'required|string',
            'selected_ids'      => 'required|string',
            'bukti_pembayaran'  => 'required_unless:metode_pembayaran,cod|image|mimes:jpeg,png,jpg|max:2048',

            // Validasi Kondisional (Hanya wajib jika shipping)
            'shipping_address'  => 'required_if:delivery_type,shipping',
            'shipping_cost'     => 'numeric', // Bisa 0
            'courier'           => 'nullable|string',
            'shipping_service'  => 'nullable|string',
        ]);

        $selectedIds = explode(',', $request->selected_ids);

        // 2. AMBIL ITEM KERANJANG
        $cartItems = Keranjang::where('user_id', Auth::id())
            ->whereIn('id', $selectedIds)
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('keranjang.index')->with('error', 'Terjadi kesalahan data keranjang.');
        }

        // 3. HITUNG SUBTOTAL PRODUK
        $subtotal = $cartItems->sum(function ($item) {
            $harga = $item->custom_price ?? $item->product->harga;
            return $harga * $item->jumlah;
        });

        // 4. LOGIKA PENGIRIMAN VS PICKUP (PENTING DISINI)
        if ($request->delivery_type == 'pickup') {
            // SETTING UNTUK AMBIL DI TOKO
            $ongkir = 0;
            $shippingMethodName = 'AMBIL DI TOKO (Pickup)';
            // Kita set alamat statis agar database tidak error dan Admin tau
            $shippingAddress = 'Customer akan mengambil pesanan di Toko.';
        } else {
            // SETTING UNTUK EKSPEDISI
            $ongkir = $request->shipping_cost;
            $shippingMethodName = strtoupper($request->courier) . ' - ' . $request->shipping_service;
            $shippingAddress = $request->shipping_address;
        }

        // Hitung Total Akhir
        $grandTotal = $subtotal + $ongkir;

        try {
            DB::beginTransaction();

            // Upload Bukti Pembayaran
            $buktiPath = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $buktiPath = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
            }

            $status = ($request->metode_pembayaran == 'cod') ? 'Menunggu Konfirmasi' : 'Akan Diproses';

            // dd($request->all());
            // 5. SIMPAN TRANSAKSI KE DATABASE
            $transaksi = Transaksi::create([
                'user_id'           => Auth::id(),
                'metode_pembayaran' => $request->metode_pembayaran,
                'bukti_pembayaran'  => $buktiPath,
                'total'             => $grandTotal,
                'status'            => $status,
                // Simpan data hasil logika di atas
                'shipping_method'   => $shippingMethodName,
                'shipping_cost'     => $ongkir,
                'shipping_address'  => $shippingAddress,
            ]);

            $adaCustomCake = false;

            // 6. SUSUN PESAN WHATSAPP
            $waMessage = "Halo Admin DaraCake, saya baru saja membuat pesanan.%0A%0A";
            $waMessage .= "🧾 *ID Pesanan:* #{$transaksi->id}%0A";
            $waMessage .= "👤 *Nama:* " . Auth::user()->name . "%0A";
            $waMessage .= "💳 *Pembayaran:* " . strtoupper($request->metode_pembayaran) . "%0A";

            // Info Pengiriman di WA
            if ($request->delivery_type == 'pickup') {
                $waMessage .= "🏃 *Metode:* AMBIL SENDIRI (Pickup)%0A";
            } else {
                $waMessage .= "🚚 *Ekspedisi:* " . $shippingMethodName . "%0A";
                $waMessage .= "📍 *Tujuan:* " . $shippingAddress . "%0A";
            }

            $waMessage .= "%0A*Rincian Biaya:*%0A";
            $waMessage .= "Subtotal: Rp " . number_format($subtotal, 0, ',', '.') . "%0A";
            $waMessage .= "Ongkir: Rp " . number_format($ongkir, 0, ',', '.') . "%0A";
            $waMessage .= "*TOTAL: Rp " . number_format($grandTotal, 0, ',', '.') . "*%0A%0A";

            $waMessage .= "*Detail Produk:*%0A";

            foreach ($cartItems as $item) {
                $hargaFinal = $item->custom_price ?? $item->product->harga;

                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id'    => $item->product_id,
                    'jumlah'       => $item->jumlah,
                    'harga'        => $hargaFinal,
                    'catatan'      => $item->custom_deskripsi
                ]);

                // Kurangi Stok (Hanya produk non-custom)
                if ($item->custom_deskripsi == null) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->stok -= $item->jumlah;
                        $product->save();
                    }
                } else {
                    $adaCustomCake = true;
                }

                // Tambahkan item ke pesan WA
                $waMessage .= "- " . $item->product->nama_produk . " (x{$item->jumlah})%0A";
                if ($item->custom_deskripsi) {
                    $waMessage .= "  _Note: {$item->custom_deskripsi}_%0A";
                }
            }

            $waMessage .= "%0AMohon diproses ya kak, terima kasih! 🙏";

            // Hapus Keranjang
            Keranjang::where('user_id', Auth::id())
                ->whereIn('id', $selectedIds)
                ->delete();

            DB::commit();

            // 7. LOGIKA REDIRECT
            if ($adaCustomCake) {
                // Ganti dengan nomor Admin asli
                $adminNumber = '62895611194900';
                return redirect("https://wa.me/{$adminNumber}?text=" . $waMessage); // urlencode sudah otomatis di browser modern, tapi pakai urlencode($waMessage) lebih aman
            } else {
                return redirect()->route('customer.pesanan.index')->with('success', 'Pesanan berhasil dibuat!');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('keranjang.index')->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
