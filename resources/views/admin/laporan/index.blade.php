@extends('layouts.navbar_admin')

@section('content')
<div class="space-y-6 font-sans text-gray-800">
    
    {{-- HEADER & ACTIONS --}}
    <div class="flex flex-col md:flex-row justify-between items-end gap-4 border-b border-gray-200 pb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                Laporan Penjualan
            </h1>
            <p class="text-gray-500 mt-1 text-sm">Ringkasan transaksi yang telah selesai diproses.</p>
        </div>
        
        {{-- Tombol Download PDF --}}
        {{-- Pastikan Anda sudah membuat Route 'laporan.pdf' di web.php --}}
        <a href="{{ route('laporan.pdf', request()->all()) }}" target="_blank"
           class="bg-[#4a0105] text-white px-5 py-2.5 rounded-lg shadow-sm hover:bg-[#6a0208] transition flex items-center gap-2 text-sm font-medium">
            <i class="fas fa-file-pdf"></i> Download PDF
        </a>
    </div>

    {{-- KARTU STATISTIK (Clean Style) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- 1. Pendapatan Bersih --}}
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-start justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Pendapatan</p>
                <h3 class="text-xl font-bold text-gray-900 mt-1">
                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </h3>
            </div>
            <div class="p-2 bg-green-50 text-green-600 rounded-lg">
                <i class="fas fa-coins text-lg"></i>
            </div>
        </div>

        {{-- 2. Total Pesanan --}}
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-start justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Pesanan</p>
                <h3 class="text-xl font-bold text-gray-900 mt-1">
                    {{ $jumlahPesanan }}
                </h3>
            </div>
            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                <i class="fas fa-shopping-bag text-lg"></i>
            </div>
        </div>

        {{-- 3. Perlu Proses --}}
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-start justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Perlu Proses</p>
                <h3 class="text-xl font-bold text-gray-900 mt-1">
                    {{ $pesananPending }}
                </h3>
            </div>
            <div class="p-2 bg-yellow-50 text-yellow-600 rounded-lg">
                <i class="fas fa-clock text-lg"></i>
            </div>
        </div>

        {{-- 4. Omset Hari Ini --}}
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-start justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Omset Hari Ini</p>
                <h3 class="text-xl font-bold text-gray-900 mt-1">
                    Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}
                </h3>
            </div>
            <div class="p-2 bg-purple-50 text-purple-600 rounded-lg">
                <i class="fas fa-calendar-day text-lg"></i>
            </div>
        </div>

    </div>

    {{-- CONTAINER UTAMA --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        
        {{-- FORM FILTER (Background Abu lembut) --}}
        <div class="bg-gray-50 border-b border-gray-200 p-5">
            <form method="GET" action="{{ route('laporan.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    
                    {{-- Tanggal --}}
                    <div class="md:col-span-3">
                        <label class="text-xs font-semibold text-gray-500 mb-1 block">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                            class="w-full px-3 py-2 bg-white rounded-md border border-gray-300 focus:ring-1 focus:ring-[#4a0105] focus:border-[#4a0105] text-sm outline-none transition">
                    </div>

                    {{-- Bulan --}}
                    <div class="md:col-span-3">
                        <label class="text-xs font-semibold text-gray-500 mb-1 block">Bulan</label>
                        <select name="bulan" class="w-full px-3 py-2 bg-white rounded-md border border-gray-300 focus:ring-1 focus:ring-[#4a0105] focus:border-[#4a0105] text-sm outline-none transition">
                            <option value="">Semua Bulan</option>
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    {{-- Tahun --}}
                    <div class="md:col-span-3">
                        <label class="text-xs font-semibold text-gray-500 mb-1 block">Tahun</label>
                        <select name="tahun" class="w-full px-3 py-2 bg-white rounded-md border border-gray-300 focus:ring-1 focus:ring-[#4a0105] focus:border-[#4a0105] text-sm outline-none transition">
                            <option value="">Semua Tahun</option>
                            @for ($y = 2023; $y <= date('Y'); $y++)
                                <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    {{-- Buttons --}}
                    <div class="md:col-span-3 flex gap-2">
                        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-900 transition text-sm font-medium flex-1">
                            Terapkan
                        </button>
                        <a href="{{ route('laporan.index') }}" class="bg-white border border-gray-300 text-gray-600 px-4 py-2 rounded-md hover:bg-gray-50 transition text-sm font-medium flex-1 text-center">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- TABEL DATA --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-white border-b border-gray-200 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-semibold">ID & Pelanggan</th>
                        <th class="px-6 py-4 font-semibold text-center">Item</th>
                        <th class="px-6 py-4 font-semibold">Pembayaran</th>
                        <th class="px-6 py-4 font-semibold">Total</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Waktu Selesai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse ($transaksi as $t)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        {{-- ID & Pelanggan Digabung agar lebih rapi --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="font-mono text-[#4a0105] font-bold bg-red-50 px-2 py-1 rounded text-xs">
                                    #{{ $t->id }}
                                </span>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $t->user->name ?? 'Guest' }}</div>
                                    @if($t->is_custom)
                                        <span class="text-[10px] uppercase font-bold text-purple-600 bg-purple-50 px-1.5 rounded-sm">Custom Order</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        
                        <td class="px-6 py-4 text-center">
                            <span class="text-gray-600 bg-gray-100 px-2 py-1 rounded-full text-xs font-medium">
                                {{ $t->detailTransaksi->count() }} item
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <div class="text-xs font-bold uppercase {{ $t->metode_pembayaran == 'cod' ? 'text-orange-600' : 'text-blue-600' }}">
                                {{ $t->metode_pembayaran }}
                            </div>
                        </td>

                        <td class="px-6 py-4 font-bold text-gray-900">
                            Rp {{ number_format($t->total, 0, ',', '.') }}
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $t->status }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="text-gray-900 font-medium">{{ $t->updated_at->format('d M Y') }}</div>
                            <div class="text-xs text-gray-400">{{ $t->updated_at->format('H:i') }} WIB</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 bg-gray-50/50">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-inbox text-3xl mb-3 text-gray-300"></i>
                                <p>Tidak ada data ditemukan untuk periode ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>

                {{-- TFOOT: Total Pemasukan --}}
                @if($transaksi->count() > 0)
                <tfoot class="bg-gray-50 border-t border-gray-200">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right text-xs font-bold uppercase text-gray-500 tracking-wider">
                            Total Periode Ini :
                        </td>
                        <td class="px-6 py-4 text-[#4a0105] text-lg font-bold">
                            Rp {{ number_format($pemasukanTotalDariFilter, 0, ',', '.') }}
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="p-4 border-t border-gray-200 bg-white">
            {{ $transaksi->links() }}
        </div>
    </div>
</div>
@endsection