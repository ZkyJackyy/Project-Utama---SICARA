<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // =========================================
        // A. QUERY UNTUK TABEL & LAPORAN (List)
        // =========================================
        
        // Filter dasar: Hanya ambil yang statusnya 'Selesai'
        $query = Transaksi::with(['user', 'detailTransaksi'])
                          ->where('status', 'Selesai');

        // 1. Filter Tanggal
        if ($request->tanggal) {
            $query->whereDate('created_at', $request->tanggal);
        }

        // 2. Filter Bulan
        if ($request->bulan) {
            $query->whereMonth('created_at', $request->bulan);
        }

        // 3. Filter Tahun
        if ($request->tahun) {
            $query->whereYear('created_at', $request->tahun);
        }

        // HITUNG TOTAL UNTUK FOOTER (Sebelum Pagination)
        // Ini penting agar saat di-print, totalnya adalah total seluruh filter, bukan cuma halaman 1
        $pemasukanTotalDariFilter = $query->sum('total');

        // AMBIL DATA TABEL (Paginate)
        $transaksi = $query->latest()->paginate(10);


        // =========================================
        // B. STATISTIK KARTU (Global / Dashboard)
        // =========================================
        // Catatan: Query di bawah ini dibuat baru agar tidak terpengaruh filter tabel
        // jika Anda ingin statistik kartu tetap global (seumur hidup toko).

        // 1. Total Pendapatan Bersih (Seumur Hidup)
        $totalPendapatan = Transaksi::where('status', 'Selesai')->sum('total');

        // 2. Jumlah Pesanan (Semua kecuali batal)
        $jumlahPesanan = Transaksi::where('status', '!=', 'Dibatalkan')->count();

        // 3. Pesanan Perlu Proses
        $pesananPending = Transaksi::where('status', 'Menunggu Konfirmasi')->count();

        // 4. Pendapatan Hari Ini (Realtime)
        $pendapatanHariIni = Transaksi::where('status', 'Selesai')
                                      ->whereDate('created_at', now())
                                      ->sum('total');

        return view('admin.laporan.index', compact(
            'totalPendapatan',
            'jumlahPesanan',
            'pendapatanHariIni',
            'pesananPending',
            'transaksi',
            'pemasukanTotalDariFilter' // <--- Variabel baru untuk footer
        ));
    }
}
