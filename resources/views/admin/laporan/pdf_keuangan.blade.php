<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #700207; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #700207; font-size: 24px; }
        .header p { margin: 5px 0 0; font-size: 14px; color: #666; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #700207; color: white; text-transform: uppercase; font-size: 11px; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        .text-green { color: #166534; }
        .text-red { color: #991b1b; }
        
        .footer { margin-top: 30px; text-align: right; font-size: 12px; color: #666; }
    </style>
</head>
<body>

    <div class="header">
        <h1>DARA CAKE</h1>
        <p>Laporan Keuangan - Periode {{ $tahun }} {{ $bulanFilter ? '(' . date("F", mktime(0, 0, 0, $bulanFilter, 10)) . ')' : '' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Bulan</th>
                <th class="text-right">Modal Operasional</th>
                <th class="text-right">Omset Penjualan</th>
                <th class="text-right">Laba Bersih (Net Profit)</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalModal = 0; $totalOmset = 0; $totalBersih = 0; 
            @endphp
            @foreach($laporan as $data)
                @php
                    $totalModal += $data['modal'];
                    $totalOmset += $data['omset'];
                    $totalBersih += $data['bersih'];
                @endphp
                <tr>
                    <td>{{ $data['nama_bulan'] }}</td>
                    <td class="text-right">Rp {{ number_format($data['modal'], 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($data['omset'], 0, ',', '.') }}</td>
                    <td class="text-right bold {{ $data['bersih'] >= 0 ? 'text-green' : 'text-red' }}">
                        Rp {{ number_format($data['bersih'], 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #eee;">
                <td class="bold">TOTAL AKHIR</td>
                <td class="text-right bold">Rp {{ number_format($totalModal, 0, ',', '.') }}</td>
                <td class="text-right bold">Rp {{ number_format($totalOmset, 0, ',', '.') }}</td>
                <td class="text-right bold {{ $totalBersih >= 0 ? 'text-green' : 'text-red' }}" style="font-size: 14px;">
                    Rp {{ number_format($totalBersih, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d F Y, H:i') }} WIB</p>
        <p>Oleh: {{ Auth::user()->name ?? 'Admin' }}</p>
    </div>

</body>
</html>