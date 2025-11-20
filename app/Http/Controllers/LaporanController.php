<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Query dasar
        $query = Transaksi::with('user');

        // ====== FILTER TANGGAL ======
        if ($request->tanggal) {
            $query->whereDate('created_at', $request->tanggal);
        }

        // ====== FILTER BULAN ======
        if ($request->bulan) {
            $query->whereMonth('created_at', $request->bulan);
        }

        // ====== FILTER TAHUN ======
        if ($request->tahun) {
            $query->whereYear('created_at', $request->tahun);
        }

        // Ambil transaksi hasil filter
        $transaksi = $query->latest()->paginate(10);

        // Hitung statistik sesuai filter
        $totalPendapatan = $query->sum('total');
        $jumlahPesanan = $query->count();
        $pendapatanHariIni = $query->whereDate('created_at', today())->sum('total');

        return view('admin.laporan.index', compact(
            'totalPendapatan',
            'jumlahPesanan',
            'pendapatanHariIni',
            'transaksi'
        ));
    }
}
