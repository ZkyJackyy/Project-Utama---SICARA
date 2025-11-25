<?php

namespace App\Http\Controllers;

use App\Models\Modal;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        // 1. Tentukan Tahun (Default tahun ini)
        $tahun = $request->tahun ?? date('Y');

        // 2. Ambil Data Modal di tahun tersebut
        $dataModal = Modal::where('tahun', $tahun)->get()->keyBy('bulan');

        // 3. Ambil Data Penjualan (Omset) per bulan di tahun tersebut (Hanya yang Selesai)
        $dataOmset = Transaksi::select(
                        DB::raw('MONTH(created_at) as bulan'), 
                        DB::raw('SUM(total) as total_omset')
                    )
                    ->whereYear('created_at', $tahun)
                    ->where('status', 'Selesai')
                    ->groupBy('bulan')
                    ->get()
                    ->keyBy('bulan');

        // 4. Gabungkan Data (Looping bulan 1 sampai 12)
        $laporan = [];
        for ($i = 1; $i <= 12; $i++) {
            $modal = $dataModal[$i]->jumlah_modal ?? 0;
            $omset = $dataOmset[$i]->total_omset ?? 0;
            $bersih = $omset - $modal;

            $laporan[$i] = [
                'nama_bulan' => date("F", mktime(0, 0, 0, $i, 10)), // Jan, Feb, dll
                'modal' => $modal,
                'omset' => $omset,
                'bersih' => $bersih
            ];
        }

        return view('admin.laporan.keuangan', compact('laporan', 'tahun'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer',
            'jumlah_modal' => 'required|numeric|min:0'
        ]);

        // Simpan atau Update (Jika bulan itu sudah ada modalnya, ditimpa/update)
        Modal::updateOrCreate(
            ['bulan' => $request->bulan, 'tahun' => $request->tahun],
            ['jumlah_modal' => $request->jumlah_modal]
        );

        return redirect()->back()->with('success', 'Modal berhasil disimpan!');
    }
}
