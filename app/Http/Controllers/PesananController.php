<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    // ✅ Tampilkan semua pesanan
    public function index()
    {
        $pesanan = Transaksi::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.pesanan.index', compact('pesanan'));
    }

    // ✅ Detail pesanan
    public function show($id)
{
    $pesanan = Transaksi::with(['user', 'detailTransaksi.produk'])->findOrFail($id);
    return view('admin.pesanan.show', compact('pesanan'));
}


    // ✅ Update status pesanan
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $pesanan = Transaksi::findOrFail($id);

        // === LOGIKA BARU DITAMBAHKAN DI SINI ===
        // Jika statusnya sudah 'Dibatalkan', jangan biarkan admin mengubahnya.
        if ($pesanan->status == 'Dibatalkan') {
            return redirect()->route('admin.pesanan.show', $pesanan->id)
                             ->with('error', 'Status pesanan yang sudah Dibatalkan oleh customer tidak dapat diubah.');
        }
        // === AKHIR LOGIKA BARU ===

        $pesanan->status = $request->status;
        $pesanan->save();

        return redirect()->route('admin.pesanan.show', $pesanan->id)
                         ->with('success', 'Status pesanan berhasil diperbarui!');
    }

        // ✅ Tampilkan pesanan khusus customer yang sedang login
    public function pesananCustomer()
    {
        $pesanan = Transaksi::with('detailTransaksi')
                    ->where('user_id', Auth::id())   // hanya pesanan milik user saat ini
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('customer.pesanan.index', compact('pesanan'));
    }

    public function batalPesanan(Request $request, Transaksi $transaksi)
    {
        // 1. Cek Kepemilikan (Pastikan user hanya bisa batalkan pesanannya sendiri)
        if ($transaksi->user_id != Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        // 2. Cek Status (Hanya status tertentu yang boleh dibatalkan)
        $allowedStatus = ['Menunggu Konfirmasi'];
        if (!in_array($transaksi->status, $allowedStatus)) {
            return redirect()->back()->with('error', 'Pesanan ini tidak dapat dibatalkan lagi.');
        }

        try {
            DB::beginTransaction();

            // 3. Kembalikan Stok
            foreach ($transaksi->detailTransaksi as $detail) {
                // Gunakan lockForUpdate untuk mencegah race condition
                $product = Product::lockForUpdate()->find($detail->produk_id);
                if ($product) {
                    $product->stok += $detail->jumlah;
                    $product->save();
                }
            }

            // 4. Ubah Status Pesanan
            $transaksi->status = 'Dibatalkan';
            $transaksi->save();

            DB::commit(); // Simpan semua perubahan jika sukses

            return redirect()->back()->with('success', 'Pesanan #' . $transaksi->id . ' berhasil dibatalkan.');

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua jika ada error
            return redirect()->back()->with('error', 'Gagal membatalkan pesanan. Silakan coba lagi.');
        }
    }
}
