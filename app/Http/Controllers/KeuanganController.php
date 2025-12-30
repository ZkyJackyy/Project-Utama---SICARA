<?php

namespace App\Http\Controllers;

use App\Models\Modal;
use Barryvdh\DomPDF\facade\PDF;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil Filter
        $tahun = $request->tahun ?? date('Y');
        $bulanFilter = $request->bulan; // Bisa null (Semua Bulan) atau angka 1-12

        // 2. Ambil Data Modal
        $dataModal = Modal::where('tahun', $tahun)->get()->keyBy('bulan');

        // 3. Ambil Data Omset
        $dataOmset = Transaksi::select(
                        DB::raw('MONTH(created_at) as bulan'), 
                        DB::raw('SUM(total) as total_omset')
                    )
                    ->whereYear('created_at', $tahun)
                    ->where('status', 'Selesai')
                    ->groupBy('bulan')
                    ->get()
                    ->keyBy('bulan');

        // 4. Proses Data Laporan
        $laporan = [];

        // Jika filter bulan dipilih, loop hanya 1 kali. Jika tidak, loop 12 bulan.
        $startMonth = $bulanFilter ? $bulanFilter : 1;
        $endMonth   = $bulanFilter ? $bulanFilter : 12;

        for ($i = $startMonth; $i <= $endMonth; $i++) {
            $modal = $dataModal[$i]->jumlah_modal ?? 0;
            $omset = $dataOmset[$i]->total_omset ?? 0;
            $bersih = $omset - $modal;

            // Masukkan ke array hanya jika ada aktivitas (opsional) atau tampilkan semua
            // Disini kita tampilkan semua agar tabel rapi
            $laporan[] = [
                'nama_bulan' => date("F", mktime(0, 0, 0, $i, 10)),
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

        Modal::updateOrCreate(
            ['bulan' => $request->bulan, 'tahun' => $request->tahun],
            ['jumlah_modal' => $request->jumlah_modal]
        );

        return redirect()->back()->with('success', 'Modal berhasil disimpan!');
    }

    public function downloadPdf(Request $request)
{
    // 1. Ambil Filter (Sama persis dengan index)
    $tahun = $request->tahun ?? date('Y');
    $bulanFilter = $request->bulan; 

    // 2. Ambil Data Modal
    $dataModal = Modal::where('tahun', $tahun)->get()->keyBy('bulan');

    // 3. Ambil Data Omset
    $dataOmset = Transaksi::select(
                    DB::raw('MONTH(created_at) as bulan'), 
                    DB::raw('SUM(total) as total_omset')
                )
                ->whereYear('created_at', $tahun)
                ->where('status', 'Selesai')
                ->groupBy('bulan')
                ->get()
                ->keyBy('bulan');

    // 4. Proses Data Laporan
    $laporan = [];
    $startMonth = $bulanFilter ? $bulanFilter : 1;
    $endMonth   = $bulanFilter ? $bulanFilter : 12;

    for ($i = $startMonth; $i <= $endMonth; $i++) {
        $modal = $dataModal[$i]->jumlah_modal ?? 0;
        $omset = $dataOmset[$i]->total_omset ?? 0;
        $bersih = $omset - $modal;

        $laporan[] = [
            'nama_bulan' => date("F", mktime(0, 0, 0, $i, 10)),
            'modal' => $modal,
            'omset' => $omset,
            'bersih' => $bersih
        ];
    }

    // 5. Generate PDF
    // Kita buat view khusus untuk PDF agar tampilannya rapi (tanpa navbar/sidebar)
    $pdf = PDF::loadView('admin.laporan.pdf_keuangan', compact('laporan', 'tahun', 'bulanFilter'));
    
    // Set ukuran kertas A4 Portrait
    $pdf->setPaper('a4', 'portrait');

    return $pdf->download('Laporan-Keuangan-DaraCake-' . $tahun . '.pdf');
}
}
