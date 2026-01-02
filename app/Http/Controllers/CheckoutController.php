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
        
        // Cek Parameter: Apakah dari Keranjang (selected_ids) atau Langsung (product_id)?
        
        // KASUS 1: BELI LANGSUNG (Dari Session/Input Langsung)
        // Kita pakai trik: Jika ada session 'direct_checkout_item', pakai itu.
        if (session()->has('direct_checkout_item')) {
            $directItem = session('direct_checkout_item');
            
            // Buat objek dummy biar view tidak error
            $fakeItem = new \stdClass();
            $fakeItem->id = null; // Tidak ada ID keranjang
            $fakeItem->product_id = $directItem['product_id'];
            $fakeItem->jumlah = $directItem['jumlah'];
            $fakeItem->product = Product::find($directItem['product_id']);
            $fakeItem->custom_price = null;
            $fakeItem->custom_deskripsi = null;

            $cartItems = collect([$fakeItem]);
            $selectedIdsString = 'DIRECT_' . $directItem['product_id'] . '_' . $directItem['jumlah']; // Kode Unik
        } 
        // KASUS 2: DARI KERANJANG (Normal)
        else {
            $selectedIds = explode(',', $request->query('selected_ids', ''));
            $cartItems = Keranjang::where('user_id', Auth::id())
                ->whereIn('id', $selectedIds)
                ->with('product')
                ->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('keranjang.index')->with('error', 'Pilih produk dulu.');
            }
            $selectedIdsString = implode(',', $selectedIds);
        }

        // Hitung Total
        $totalWeight = $cartItems->sum(fn($item) => ($item->product->berat ?? 500) * $item->jumlah);
        $total = $cartItems->sum(fn($item) => ($item->custom_price ?? $item->product->harga) * $item->jumlah);

        return view('customer.pages.checkout', compact('cartItems', 'total', 'totalWeight', 'selectedIdsString', 'user'));
    }

    // B. HANDLE FORM "BELI SEKARANG" (Jembatan ke Halaman Checkout)
    public function directCheckout(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'jumlah' => 'required|integer|min:1'
        ]);

        $product = Product::find($request->product_id);
        
        // Cek Stok Awal
        if ($product->stok < $request->jumlah) {
            return back()->with('error', 'Stok barang tidak cukup.');
        }

        // Simpan data sementara ke Session
        session()->flash('direct_checkout_item', [
            'product_id' => $request->product_id,
            'jumlah' => $request->jumlah
        ]);

        // Redirect ke Halaman Checkout (tanpa parameter selected_ids)
        return redirect()->route('checkout');
    }

    // C. PROSES TRANSAKSI (EKSEKUSI AKHIR)
public function proses(Request $request)
    {
        $request->validate([
            'delivery_type'     => 'required|in:shipping,pickup',
            'metode_pembayaran' => 'required|string',
            'selected_ids'      => 'required|string',
            'bukti_pembayaran'  => 'required_unless:metode_pembayaran,cod|image|mimes:jpeg,png,jpg|max:2048',
            'shipping_address'  => 'required_if:delivery_type,shipping',
        ]);

        // --- 1. DETEKSI SUMBER DATA ---
        $cartItems = collect([]);
        $isDirectBuy = false;

        if (str_starts_with($request->selected_ids, 'DIRECT_')) {
            // Ini Beli Langsung
            $parts = explode('_', $request->selected_ids);
            $prodId = $parts[1];
            $qty = $parts[2];

            $product = Product::find($prodId);
            
            $fakeItem = new \stdClass();
            $fakeItem->product_id = $product->id;
            $fakeItem->jumlah = $qty;
            $fakeItem->product = $product;
            $fakeItem->custom_price = null;
            $fakeItem->custom_deskripsi = null;

            $cartItems = collect([$fakeItem]);
            $isDirectBuy = true;
        } else {
            // Ini Dari Keranjang
            $ids = explode(',', $request->selected_ids);
            $cartItems = Keranjang::where('user_id', Auth::id())
                ->whereIn('id', $ids)
                ->with('product')
                ->get();
        }

        // --- 2. HITUNG TOTAL ---
        $subtotal = $cartItems->sum(fn($item) => ($item->custom_price ?? $item->product->harga) * $item->jumlah);
        $ongkir = $request->shipping_cost ?? 0;
        $grandTotal = $subtotal + $ongkir;

        try {
            DB::beginTransaction();

            // --- 3. SIMPAN TRANSAKSI ---
            $buktiPath = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $buktiPath = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
            }

            $transaksi = Transaksi::create([
                'user_id' => Auth::id(),
                'metode_pembayaran' => $request->metode_pembayaran,
                'bukti_pembayaran' => $buktiPath,
                'total' => $grandTotal,
                'status' => ($request->metode_pembayaran == 'cod') ? 'Menunggu Konfirmasi' : 'Akan Diproses',
                'shipping_method' => ($request->delivery_type == 'pickup') ? 'AMBIL DI TOKO' : ($request->courier . ' - ' . $request->shipping_service),
                'shipping_cost' => $ongkir,
                'shipping_address' => ($request->delivery_type == 'pickup') ? 'Pickup' : $request->shipping_address,
            ]);

            $transaksi->refresh();
            $kodeOrder = $transaksi->kode_transaksi ?? $transaksi->id;
            
            // --- 4. DETAIL ITEM & STOK ---
            foreach ($cartItems as $item) {
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id'    => $item->product_id,
                    'jumlah'       => $item->jumlah,
                    'harga'        => $item->custom_price ?? $item->product->harga,
                    'catatan'      => $item->custom_deskripsi
                ]);

                if ($item->custom_deskripsi == null) {
                    $product = Product::lockForUpdate()->find($item->product_id);
                    if (!$product || $product->stok < $item->jumlah) {
                        throw new \Exception("Stok '{$product->nama_produk}' habis saat Anda proses bayar.");
                    }
                    $product->stok -= $item->jumlah;
                    $product->save();
                }
            }

            // Hapus Keranjang jika bukan beli langsung
            if (!$isDirectBuy) {
                Keranjang::where('user_id', Auth::id())
                    ->whereIn('id', explode(',', $request->selected_ids))
                    ->delete();
            }

            DB::commit();

            // ==========================================================
            // 5. LOGIKA REDIRECT KE WHATSAPP (FORMAT BARU & RAPI)
            // ==========================================================
            
            $userName = Auth::user()->name;
            $paymentMethod = strtoupper(str_replace('_', ' ', $request->metode_pembayaran));
            
            // Format Angka
            $fmtSubtotal = number_format($subtotal, 0, ',', '.');
            $fmtOngkir   = number_format($ongkir, 0, ',', '.');
            $fmtTotal    = number_format($grandTotal, 0, ',', '.');

            // Header Pesan
            $waMessage  = "Halo Admin DaraCake, saya baru saja membuat pesanan.\n\n";
            $waMessage .= "*ID Pesanan:* #{$kodeOrder}\n";
            $waMessage .= "*Nama:* {$userName}\n";
            $waMessage .= "*Pembayaran:* {$paymentMethod}\n";

            // Info Pengiriman
            if ($request->delivery_type == 'pickup') {
                $waMessage .= "*Metode:* AMBIL SENDIRI (Pickup)\n";
            } else {
                // Ambil info kurir dari input hidden atau default text
                $kurirInfo = $request->courier ? strtoupper($request->courier) : 'EKSPEDISI';
                $waMessage .= "*Ekspedisi:* {$kurirInfo}\n";
                $waMessage .= "*Tujuan:* {$request->shipping_address}\n";
            }

            // Rincian Biaya
            $waMessage .= "\n*Rincian Biaya:*\n";
            $waMessage .= "Subtotal: Rp {$fmtSubtotal}\n";
            $waMessage .= "Ongkir: Rp {$fmtOngkir}\n";
            $waMessage .= "*TOTAL: Rp {$fmtTotal}*\n";

            // Detail Produk
            $waMessage .= "\n*Detail Produk:*\n";
            foreach ($cartItems as $item) {
                $namaProduk = $item->product->nama_produk;
                $waMessage .= "- {$namaProduk} (x{$item->jumlah})\n";
                if ($item->custom_deskripsi) {
                    $waMessage .= " ↳ Note: {$item->custom_deskripsi}\n";
                }
            }

            // Penutup
            $waMessage .= "\nMohon diproses ya kak, terima kasih! 🙏";

            // --- REDIRECT ---
            $adminNumber = '62895611194900'; 
            return redirect("https://wa.me/{$adminNumber}?text=" . urlencode($waMessage));

        } catch (\Exception $e) {
            DB::rollBack();
            if ($isDirectBuy) {
                return redirect()->back()->with('error', $e->getMessage());
            }
            return redirect()->route('keranjang.index')->with('error', $e->getMessage());
        }
    }
}
