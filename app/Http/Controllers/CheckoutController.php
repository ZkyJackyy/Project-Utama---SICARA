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
        // 1. VALIDASI INPUT (Dilakukan Paling Awal)
        $request->validate([
            'delivery_type'     => 'required|in:shipping,pickup', // Pastikan isinya hanya shipping atau pickup
            'metode_pembayaran' => 'required|string',
            'selected_ids'      => 'required|string',
            'bukti_pembayaran'  => 'required_unless:metode_pembayaran,cod|image|mimes:jpeg,png,jpg|max:2048',
            
            // Validasi Kondisional
            'shipping_address'  => 'required_if:delivery_type,shipping',
            'shipping_cost'     => 'nullable|numeric', 
            'courier'           => 'nullable|string',
            'shipping_service'  => 'nullable|string',
        ]);

        // Baru proses explode setelah validasi sukses
        $selectedIds = explode(',', $request->selected_ids);

        // 2. AMBIL ITEM KERANJANG
        $cartItems = Keranjang::where('user_id', Auth::id())
            ->whereIn('id', $selectedIds)
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('keranjang.index')->with('error', 'Terjadi kesalahan data keranjang (Kosong).');
        }

        // 3. HITUNG SUBTOTAL PRODUK
        $subtotal = $cartItems->sum(function ($item) {
            $harga = $item->custom_price ?? $item->product->harga;
            return $harga * $item->jumlah;
        });

        // 4. LOGIKA PENGIRIMAN VS PICKUP
        if ($request->delivery_type == 'pickup') {
            // SETTING UNTUK AMBIL DI TOKO
            $ongkir = 0;
            $shippingMethodName = 'AMBIL DI TOKO (Pickup)';
            $shippingAddress = 'Customer akan mengambil pesanan di Toko.';
        } else {
            // SETTING UNTUK EKSPEDISI
            // Pastikan ongkir tidak null, jika null set 0 (default)
            $ongkir = $request->shipping_cost ?? 0;
            
            // Pastikan kurir dan service tidak null
            $kurir = $request->courier ? strtoupper($request->courier) : 'EKSPEDISI';
            $service = $request->shipping_service ?? 'REGULAR';
            
            $shippingMethodName = "$kurir - $service";
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

            // 5. SIMPAN TRANSAKSI KE DATABASE
            $transaksi = Transaksi::create([
                'user_id'           => Auth::id(),
                'metode_pembayaran' => $request->metode_pembayaran,
                'bukti_pembayaran'  => $buktiPath,
                'total'             => $grandTotal,
                'status'            => $status,
                'shipping_method'   => $shippingMethodName,
                'shipping_cost'     => $ongkir,
                'shipping_address'  => $shippingAddress,
            ]);

            // Ambil Kode Transaksi (Refresh model untuk dapat data terbaru dari trigger/boot)
            $transaksi->refresh();
            $kodeOrder = $transaksi->kode_transaksi ?? $transaksi->id;

            $adaCustomCake = false;

            // 6. PERSIAPAN PESAN WHATSAPP
            $userName = Auth::user()->name;
            $paymentMethod = strtoupper(str_replace('_', ' ', $request->metode_pembayaran));
            
            $waMessage = "Halo Admin DaraCake, saya baru saja membuat pesanan.\n\n";
            $waMessage .= "🧾 *Kode Pesanan:* {$kodeOrder}\n";
            $waMessage .= "👤 *Nama:* {$userName}\n";
            $waMessage .= "💳 *Pembayaran:* {$paymentMethod}\n";

            if ($request->delivery_type == 'pickup') {
                $waMessage .= "🏃 *Metode:* AMBIL SENDIRI (Pickup)\n";
            } else {
                $waMessage .= "🚚 *Ekspedisi:* {$shippingMethodName}\n";
                $waMessage .= "📍 *Tujuan:* {$shippingAddress}\n";
            }

            $formattedSubtotal = number_format($subtotal, 0, ',', '.');
            $formattedOngkir = number_format($ongkir, 0, ',', '.');
            $formattedGrandTotal = number_format($grandTotal, 0, ',', '.');

            $waMessage .= "\n*Rincian Biaya:*\n";
            $waMessage .= "Subtotal: Rp {$formattedSubtotal}\n";
            $waMessage .= "Ongkir: Rp {$formattedOngkir}\n";
            $waMessage .= "*TOTAL: Rp {$formattedGrandTotal}*\n\n";
            $waMessage .= "*Detail Produk:*\n";

            // 7. LOOP ITEM, SIMPAN DETAIL & KURANGI STOK
            foreach ($cartItems as $item) {
                $hargaFinal = $item->custom_price ?? $item->product->harga;

                // Simpan Detail
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id'    => $item->product_id,
                    'jumlah'       => $item->jumlah,
                    'harga'        => $hargaFinal,
                    'catatan'      => $item->custom_deskripsi
                ]);

                // LOGIKA STOK (PENTING!)
                if ($item->custom_deskripsi == null) {
                    // Kunci baris produk untuk mencegah Race Condition (rebutan stok)
                    $product = Product::lockForUpdate()->find($item->product_id);
                    
                    if (!$product) {
                        throw new \Exception("Produk '{$item->product->nama_produk}' tidak ditemukan.");
                    }

                    // Cek Apakah Stok Cukup?
                    if ($product->stok < $item->jumlah) {
                        throw new \Exception("Stok '{$product->nama_produk}' tidak mencukupi. Sisa: {$product->stok}");
                    }

                    // Kurangi Stok
                    $product->stok -= $item->jumlah;
                    $product->save();
                } else {
                    $adaCustomCake = true;
                }

                // Tambah ke Text WA
                $waMessage .= "- {$item->product->nama_produk} (x{$item->jumlah})\n";
                if ($item->custom_deskripsi) {
                    $waMessage .= "  _Note: {$item->custom_deskripsi}_\n";
                }
            }

            $waMessage .= "\nMohon diproses ya kak, terima kasih! 🙏";

            // 8. HAPUS ITEM DARI KERANJANG
            Keranjang::where('user_id', Auth::id())
                ->whereIn('id', $selectedIds)
                ->delete();

            // COMMIT TRANSAKSI DB
            DB::commit();

            // 9. REDIRECT
            if ($adaCustomCake) {
                $adminNumber = '62895611194900'; 
                // Encode pesan untuk URL WA
                $encodedMessage = urlencode($waMessage);
                return redirect("https://wa.me/{$adminNumber}?text={$encodedMessage}"); 
            } else {
                return redirect()->route('customer.pesanan.index')->with('success', 'Pesanan berhasil dibuat!');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            // Redirect kembali dengan pesan error yang spesifik (misal: Stok Habis)
            return redirect()->route('keranjang.index')->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }
}
