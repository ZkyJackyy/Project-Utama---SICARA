<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Transaksi;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    // =======================
    // ADMIN: List Pesanan
    // =======================
    public function index()
    {
        $pesanan = Transaksi::with(['user', 'detailTransaksi.produk'])
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);

        return view('admin.pesanan.index', compact('pesanan'));
    }

    // =======================
    // ADMIN: Detail Pesanan
    // =======================
    public function show($id)
    {
        $pesanan = Transaksi::with(['user', 'detailTransaksi.produk'])->findOrFail($id);
        return view('admin.pesanan.show', compact('pesanan'));
    }

    // =======================
    // ADMIN: Update Status
    // =======================
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $pesanan = Transaksi::findOrFail($id);

        if ($pesanan->status == 'Dibatalkan') {
            return redirect()->route('admin.pesanan.show', $pesanan->id)
                             ->with('error', 'Status pesanan yang sudah dibatalkan tidak dapat diubah.');
        }

        $pesanan->status = $request->status;
        $pesanan->save();

        // =======================
        // NOTIFIKASI KE CUSTOMER
        // =======================
        Notification::create([
            'user_id'       => $pesanan->user_id,
            'transaksi_id'  => $pesanan->id,
            'judul'         => 'Status Pesanan Diperbarui',
            'pesan'         => 'Status pesanan #' . $pesanan->id . ' telah berubah menjadi: ' . $request->status,
        ]);

        return redirect()->route('admin.pesanan.show', $pesanan->id)
                         ->with('success', 'Status pesanan berhasil diperbarui!');
    }

    // =======================
    // CUSTOMER: List Pesanan Saya
    // =======================
    public function pesananCustomer()
    {
        $pesanan = Transaksi::with('detailTransaksi')
                            ->where('user_id', Auth::id())
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('customer.pesanan.index', compact('pesanan'));
    }

    // =======================
    // CUSTOMER: Batalkan Pesanan
    // =======================
    public function batalPesanan(Request $request, Transaksi $transaksi)
    {
        if ($transaksi->user_id != Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        if ($transaksi->status !== 'Menunggu Konfirmasi') {
            return redirect()->back()->with('error', 'Pesanan ini tidak dapat dibatalkan.');
        }

        try {
            DB::beginTransaction();

            // Kembalikan stok
            foreach ($transaksi->detailTransaksi as $detail) {

                $product = Product::lockForUpdate()->find($detail->produk_id);
                
                if ($product) {
                    $product->stok += $detail->jumlah;
                    $product->save();
                }
            }

            // Ubah status
            $transaksi->status = 'Dibatalkan';
            $transaksi->save();

            // =======================
            // NOTIFIKASI UNTUK ADMIN
            // (Misal admin id = 1)
            // =======================
            Notification::create([
                'user_id'       => 1, // admin
                'transaksi_id'  => $transaksi->id,
                'judul'         => 'Pesanan Dibatalkan Customer',
                'pesan'         => 'Customer telah membatalkan pesanan #' . $transaksi->id,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Pesanan #' . $transaksi->id . ' berhasil dibatalkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membatalkan pesanan.');
        }
    }

    public function cetak($id)
    {
        // Ambil data transaksi beserta detail produk dan user
        $transaksi = \App\Models\Transaksi::with(['user', 'detailTransaksi.produk'])->findOrFail($id);

        return view('admin.pesanan.cetak', compact('transaksi'));
    }
}
