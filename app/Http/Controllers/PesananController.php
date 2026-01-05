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
        $pesanan = Transaksi::findOrFail($id);

        // Validasi
        $request->validate([
            'status' => 'required|string',
            'nomor_resi' => 'nullable|string', // Resi opsional (wajib jika Dikirim, dihandle logic bawah)
        ]);

        // Cek jika Admin pilih "Dikirim" tapi lupa isi resi (Khusus pengiriman Ekspedisi)
        if ($request->status == 'Dikirim' && empty($request->nomor_resi)) {
            // Cek dulu apakah ini metode pickup atau shipping
            if (!str_contains(strtoupper($pesanan->shipping_method), 'AMBIL')) {
                // Jika bukan Pickup, wajib isi resi/kurir
                return back()->with('error', 'Harap masukkan Nomor Resi atau Nama Kurir untuk status Dikirim.');
            }
        }

        // Update Data
        $pesanan->status = $request->status;
        
        // Simpan resi jika ada inputan
        if ($request->filled('nomor_resi')) {
            $pesanan->nomor_resi = $request->nomor_resi;
        }

        $pesanan->save();

        // Buat Notifikasi ke Customer
        $pesanNotif = "Status pesanan #{$pesanan->kode_transaksi} diperbarui menjadi: {$request->status}.";
        
        if ($request->status == 'Dikirim' && $pesanan->nomor_resi) {
            $pesanNotif .= " Info Pengiriman: {$pesanan->nomor_resi}";
        }

        Notification::create([
            'user_id'      => $pesanan->user_id,
            'transaksi_id' => $pesanan->id,
            'judul'        => 'Update Status Pesanan',
            'pesan'        => $pesanNotif,
        ]);

        return back()->with('success', 'Status berhasil diperbarui!');
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

    public function terimaPesanan($id)
    {
        // Pastikan hanya user pemilik pesanan yang bisa akses
        $pesanan = Transaksi::where('user_id', Auth::id())->findOrFail($id);

        // Update status jadi Selesai
        $pesanan->update(['status' => 'Selesai']);

        return back()->with('success', 'Terima kasih! Pesanan telah selesai. Silakan beri ulasan.');
    }
}
