<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #4a0105; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #4a0105; font-size: 20px; }
        .header p { margin: 5px 0 0; color: #666; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4a0105; color: white; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge { padding: 3px 6px; border-radius: 4px; font-size: 10px; font-weight: bold; }
        .bg-custom { background-color: #eee; color: #333; }
        
        .footer { margin-top: 30px; text-align: right; font-size: 10px; color: #888; }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN PENJUALAN</h1>
        <p>
            Periode: 
            {{ $filter['tanggal'] ?? '-' }} / 
            {{-- Tambahkan ( ... ?? false) agar tidak error jika bulan kosong --}}
            {{ ($filter['bulan'] ?? false) ? date('F', mktime(0, 0, 0, $filter['bulan'], 10)) : '-' }} / 
            {{ $filter['tahun'] ?? 'Semua Tahun' }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 20%">Pelanggan</th>
                <th style="width: 15%">Metode</th>
                <th style="width: 15%">Status</th>
                <th style="width: 20%">Waktu Selesai</th>
                <th style="width: 25%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $index => $t)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    {{ $t->user->name ?? 'Guest' }}
                    @if($t->is_custom) <br><small>(Custom Order)</small> @endif
                </td>
                <td>{{ strtoupper($t->metode_pembayaran) }}</td>
                <td>{{ ucfirst($t->status) }}</td>
                <td>{{ $t->updated_at->format('d/m/Y H:i') }}</td>
                <td class="text-right">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data transaksi.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right"><strong>TOTAL PENDAPATAN</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dicetak otomatis oleh sistem pada {{ date('d F Y H:i') }}
    </div>

</body>
</html>