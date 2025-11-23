@extends('layouts.navbar_admin')

@section('content')
<div class="space-y-8 font-sans text-gray-800">
    
    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#4a0105] tracking-tight">
                <i class="fas fa-chart-line mr-2"></i>Laporan Penjualan
            </h1>
            <p class="text-gray-500 mt-1 text-sm">Laporan riwayat transaksi yang telah selesai.</p>
        </div>
        
        <button onclick="window.print()" 
            class="bg-[#4a0105] text-white px-5 py-2.5 rounded-xl shadow-md hover:bg-[#6a0208] transition flex items-center gap-2 no-print">
            <i class="fas fa-print"></i> Cetak Laporan
        </button>
    </div>

    {{-- KARTU STATISTIK (Dashboard Mini) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 no-print">

        {{-- 1. Total Pendapatan (Seumur Hidup) --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-green-100 hover:shadow-md transition relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-24 h-24 bg-green-50 rounded-bl-full -mr-4 -mt-4 transition group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-green-100 text-green-700 rounded-lg">
                        <i class="fas fa-coins text-xl"></i>
                    </div>
                    <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Pendapatan Bersih</h2>
                </div>
                <p class="text-2xl font-extrabold text-green-700">
                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </p>
                <p class="text-xs text-gray-400 mt-1">*Total akumulasi toko</p>
            </div>
        </div>

        {{-- 2. Jumlah Pesanan --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-blue-100 hover:shadow-md transition relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-24 h-24 bg-blue-50 rounded-bl-full -mr-4 -mt-4 transition group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-blue-100 text-blue-700 rounded-lg">
                        <i class="fas fa-shopping-bag text-xl"></i>
                    </div>
                    <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total Pesanan</h2>
                </div>
                <p class="text-3xl font-extrabold text-blue-800">
                    {{ $jumlahPesanan }}
                </p>
                <p class="text-xs text-gray-400 mt-1">*Transaksi berhasil</p>
            </div>
        </div>

        {{-- 3. Menunggu Konfirmasi --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-yellow-100 hover:shadow-md transition relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-24 h-24 bg-yellow-50 rounded-bl-full -mr-4 -mt-4 transition group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-yellow-100 text-yellow-700 rounded-lg">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                    <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Perlu Proses</h2>
                </div>
                <p class="text-3xl font-extrabold text-yellow-700">
                    {{ $pesananPending }}
                </p>
                <p class="text-xs text-gray-400 mt-1">Menunggu konfirmasi admin</p>
            </div>
        </div>

        {{-- 4. Pendapatan Hari Ini --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-purple-100 hover:shadow-md transition relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-24 h-24 bg-purple-50 rounded-bl-full -mr-4 -mt-4 transition group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-purple-100 text-purple-700 rounded-lg">
                        <i class="fas fa-calendar-day text-xl"></i>
                    </div>
                    <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Omset Hari Ini</h2>
                </div>
                <p class="text-2xl font-extrabold text-purple-800">
                    Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}
                </p>
                <p class="text-xs text-gray-400 mt-1">{{ now()->format('d M Y') }}</p>
            </div>
        </div>

    </div>

    {{-- AREA FILTER & TABEL --}}
    <div class="bg-white rounded-2xl shadow-lg border border-[#ECCFC3] overflow-hidden print-area">
        
        {{-- Header Tabel --}}
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-lg text-[#4a0105]">Rincian Transaksi Selesai</h3>
            {{-- Info Filter saat Print --}}
            <div class="hidden print-block text-sm text-gray-500 italic">
                Dicetak pada: {{ now()->format('d M Y H:i') }}
            </div>
        </div>

        {{-- FORM FILTER --}}
        <form method="GET" action="{{ route('laporan.index') }}" class="p-6 border-b border-gray-100 bg-white no-print">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                
                {{-- Tanggal --}}
                <div class="md:col-span-3">
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Tanggal Spesifik</label>
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-[#4a0105] focus:border-[#4a0105] text-sm">
                </div>

                {{-- Bulan --}}
                <div class="md:col-span-3">
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Bulan</label>
                    <select name="bulan" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-[#4a0105] focus:border-[#4a0105] text-sm">
                        <option value="">- Semua Bulan -</option>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                {{-- Tahun --}}
                <div class="md:col-span-3">
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Tahun</label>
                    <select name="tahun" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-[#4a0105] focus:border-[#4a0105] text-sm">
                        <option value="">- Semua Tahun -</option>
                        @for ($y = 2023; $y <= date('Y'); $y++)
                            <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="md:col-span-3 flex gap-2">
                    <button type="submit" class="bg-[#4a0105] text-white px-4 py-2 rounded-lg hover:bg-[#6a0208] transition text-sm font-semibold flex-1">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                    <a href="{{ route('laporan.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-sm font-semibold flex-1 text-center">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        {{-- TABEL DATA --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-[#4a0105] text-white text-xs uppercase">
                    <tr>
                        <th class="px-6 py-4 font-semibold tracking-wide">ID Transaksi</th>
                        <th class="px-6 py-4 font-semibold tracking-wide">Pelanggan</th>
                        <th class="px-6 py-4 font-semibold tracking-wide text-center">Item</th>
                        <th class="px-6 py-4 font-semibold tracking-wide">Metode</th>
                        <th class="px-6 py-4 font-semibold tracking-wide">Total</th>
                        <th class="px-6 py-4 font-semibold tracking-wide text-center">Status</th>
                        <th class="px-6 py-4 font-semibold tracking-wide text-right">Tanggal Selesai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse ($transaksi as $t)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium text-[#4a0105]">
                            #{{ $t->id }}
                            @if($t->is_custom)
                                <span class="ml-2 px-1.5 py-0.5 bg-purple-100 text-purple-700 rounded text-[10px] font-bold border border-purple-200">CUSTOM</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-900">{{ $t->user->name ?? 'Guest' }}</div>
                            <div class="text-xs text-gray-400">ID: {{ $t->user_id }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 bg-gray-100 rounded text-xs font-medium">
                                {{ $t->detailTransaksi->count() }} Produk
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-bold uppercase {{ $t->metode_pembayaran == 'cod' ? 'text-orange-600' : 'text-blue-600' }}">
                                {{ $t->metode_pembayaran }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-900">
                            Rp {{ number_format($t->total, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 rounded-full text-xs font-bold border bg-green-100 text-green-800 border-green-200">
                                {{ $t->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-gray-500">
                            {{ $t->updated_at->format('d M Y') }}
                            <div class="text-xs text-gray-400">{{ $t->updated_at->format('H:i') }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-check-circle text-4xl mb-3 text-gray-300"></i>
                                <p>Tidak ada data transaksi selesai pada periode ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>

                {{-- TFOOT: Total Pemasukan (Hanya muncul jika ada data) --}}
                @if($transaksi->count() > 0)
                <tfoot class="bg-gray-100 border-t-2 border-gray-300 font-bold text-gray-800">
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-right uppercase tracking-wider text-xs text-gray-600">
                            Total Pemasukan :
                        </td>
                        <td class="px-6 py-4 text-[#4a0105] text-base border-t border-gray-300">
                            Rp {{ number_format($pemasukanTotalDariFilter, 0, ',', '.') }}
                        </td>
                        <td colspan="2" class="bg-gray-100"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="p-6 border-t border-gray-100 bg-gray-50 no-print">
            {{ $transaksi->links() }}
        </div>

    </div>
</div>

<style>
/* CSS KHUSUS PRINT */
@media print {
    /* Sembunyikan semua elemen body */
    body * { visibility: hidden; }
    
    /* Tampilkan hanya area print */
    .print-area, .print-area * { visibility: visible !important; }
    
    /* Posisikan area print di pojok kiri atas */
    .print-area { 
        position: absolute; 
        top: 0; 
        left: 0; 
        width: 100%; 
        border: none !important;
        box-shadow: none !important;
    }
    
    /* Hilangkan elemen dengan class no-print (tombol, form, pagination, dll) */
    .no-print { display: none !important; }
    
    /* Tampilkan elemen khusus print (seperti timestamp) */
    .print-block { display: block !important; }

    /* Pastikan warna background tercetak (terutama header tabel) */
    thead tr th { 
        background-color: #4a0105 !important; 
        color: white !important; 
        -webkit-print-color-adjust: exact; 
        print-color-adjust: exact;
    }
    
    /* Pastikan footer tercetak jelas */
    tfoot tr { 
        background-color: #f3f4f6 !important; 
        -webkit-print-color-adjust: exact; 
        print-color-adjust: exact;
    }

    /* Reset background putih untuk container */
    .bg-gray-50 { background-color: white !important; }
}

/* Helper untuk elemen yang hanya muncul saat print */
.print-block { display: none; }
</style>
@endsection