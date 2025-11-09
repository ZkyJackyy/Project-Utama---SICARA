<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\User;
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
        $pesanan->status = $request->status;
        $pesanan->save();

        // --- PERUBAHAN DI SINI ---
        // Redirect kembali ke halaman detail yang sama, bukan ke index.
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
}
