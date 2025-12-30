@extends('layouts.navbar_admin')

@section('content')
<div class="min-h-screen bg-gray-50/50 py-8 px-6 font-sans text-gray-700">
    
    {{-- HEADER SECTION --}}
    <div class="max-w-7xl mx-auto mb-8 flex flex-col md:flex-row justify-between items-end gap-4 border-b border-gray-100 pb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Laporan Keuangan</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola modal operasional dan pantau arus kas bulanan.</p>
        </div>
        
        {{-- FILTER TAHUN & BULAN --}}
        <div class="flex gap-3">
            <form method="GET" action="{{ route('keuangan.index') }}" class="flex gap-3">
                
                {{-- Filter Bulan --}}
                <div class="relative">
                    <select name="bulan" onchange="this.form.submit()" 
                        class="appearance-none bg-white border border-gray-200 text-gray-700 py-2 pl-4 pr-10 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#700207]/20 focus:border-[#700207] text-sm font-medium cursor-pointer hover:bg-gray-50 transition">
                        <option value="" {{ request('bulan') == '' ? 'selected' : '' }}>Semua Bulan</option>
                        @for($m=1; $m<=12; $m++)
                            <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                                {{ date("F", mktime(0, 0, 0, $m, 10)) }}
                            </option>
                        @endfor
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>

                {{-- Filter Tahun --}}
                <div class="relative">
                    <select name="tahun" onchange="this.form.submit()" 
                        class="appearance-none bg-white border border-gray-200 text-gray-700 py-2 pl-4 pr-10 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#700207]/20 focus:border-[#700207] text-sm font-medium cursor-pointer hover:bg-gray-50 transition">
                        @for($y=2023; $y<=date('Y')+1; $y++)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                        @endfor
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-10">

        {{-- KOLOM KIRI: INPUT MODAL --}}
        <div class="lg:col-span-4">
            <div class="bg-white rounded-2xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] border border-gray-100 sticky top-24">
                
                <div class="px-6 pt-6 pb-4 border-b border-gray-50">
                    <h3 class="font-bold text-gray-900 text-lg">Input Modal Operasional</h3>
                    <p class="text-gray-400 text-xs mt-1">Catat pengeluaran di sini.</p>
                </div>

                <div class="p-6">
                    <form action="{{ route('keuangan.store') }}" method="POST" class="space-y-5">
                        @csrf
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Bulan</label>
                                <select name="bulan" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-[#700207] focus:border-[#700207] block p-2.5">
                                    @for($m=1; $m<=12; $m++)
                                        <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>
                                            {{ date("F", mktime(0, 0, 0, $m, 10)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Tahun</label>
                                <select name="tahun" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-[#700207] focus:border-[#700207] block p-2.5">
                                    @for($y=2023; $y<=date('Y')+1; $y++)
                                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Nominal (Rp)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-400 text-sm font-semibold">Rp</span>
                                </div>
                                <input type="number" name="jumlah_modal" placeholder="0" required
                                    class="w-full pl-10 bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-lg focus:ring-[#700207] focus:border-[#700207] block p-2.5 font-medium placeholder-gray-300">
                            </div>
                        </div>

                        <button type="submit" class="w-full text-white bg-[#700207] hover:bg-[#5a0105] focus:ring-4 focus:ring-red-100 font-medium rounded-lg text-sm px-5 py-3 text-center transition-all shadow-md hover:shadow-lg mt-2">
                            Simpan Data
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: TABEL LAPORAN --}}
        <div class="lg:col-span-8">

            {{-- 3 KARTU STATISTIK DIHAPUS SESUAI PERMINTAAN --}}

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-white">
                    <h3 class="font-bold text-gray-800 text-sm uppercase tracking-wide">
                        Rincian {{ request('bulan') ? 'Bulan ' . date("F", mktime(0, 0, 0, request('bulan'), 10)) : 'Tahunan' }}
                    </h3>
                    <a href="{{ route('keuangan.pdf', ['tahun' => request('tahun'), 'bulan' => request('bulan')]) }}" 
                    class="inline-flex items-center gap-2 bg-[#700207] hover:bg-[#5a0105] text-white text-xs font-bold px-4 py-2 rounded-lg transition shadow-sm">
                        <i class="fas fa-file-pdf"></i> Download PDF
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b border-gray-100 bg-gray-50/50">
                                <th class="px-6 py-4">Periode</th>
                                <th class="px-6 py-4 text-right">Modal</th>
                                <th class="px-6 py-4 text-right">Omset</th>
                                <th class="px-6 py-4 text-right">Net Profit</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @php 
                                $totalModal = 0; 
                                $totalOmset = 0; 
                                $totalBersih = 0; 
                            @endphp

                            @forelse($laporan as $data)
                                @php
                                    $totalModal += $data['modal'];
                                    $totalOmset += $data['omset'];
                                    $totalBersih += $data['bersih'];
                                @endphp
                                <tr class="hover:bg-gray-50/50 transition duration-150">
                                    <td class="px-6 py-4">
                                        <span class="font-medium text-gray-900">{{ $data['nama_bulan'] }}</span>
                                    </td>
                                    
                                    <td class="px-6 py-4 text-right text-sm text-gray-500">
                                        {{ $data['modal'] > 0 ? number_format($data['modal'], 0, ',', '.') : '-' }}
                                    </td>
                                    
                                    <td class="px-6 py-4 text-right text-sm font-medium text-blue-600">
                                        {{ $data['omset'] > 0 ? number_format($data['omset'], 0, ',', '.') : '-' }}
                                    </td>
                                    
                                    <td class="px-6 py-4 text-right text-sm">
                                        @if($data['modal'] == 0 && $data['omset'] == 0)
                                            <span class="text-gray-300">-</span>
                                        @else
                                            <span class="font-bold {{ $data['bersih'] >= 0 ? 'text-green-600' : 'text-red-500' }}">
                                                {{ number_format($data['bersih'], 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-400 text-sm">
                                        Tidak ada data untuk periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        
                        {{-- FOOTER TABEL --}}
                        <tfoot class="bg-gray-50 border-t border-gray-200">
                            <tr>
                                <td class="px-6 py-4 font-bold text-xs text-gray-500 uppercase tracking-wider">Total</td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-600 text-sm">Rp {{ number_format($totalModal, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right font-bold text-blue-700 text-sm">Rp {{ number_format($totalOmset, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right font-extrabold text-base {{ $totalBersih >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    Rp {{ number_format($totalBersih, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection