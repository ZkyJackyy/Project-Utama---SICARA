@extends('layouts.navbar_admin')

@section('content')
<div class="min-h-screen bg-gray-50/50 py-8 px-6 font-sans text-gray-700">
    
    {{-- HEADER SECTION --}}
    <div class="max-w-7xl mx-auto mb-12 flex flex-col md:flex-row justify-between items-end gap-4 border-b border-gray-100 pb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Laporan Keuangan</h1>
            <p class="text-gray-500 text-sm mt-1">Ringkasan arus kas, modal operasional, dan laba bersih tahunan.</p>
        </div>
        
        {{-- Filter Tahun (Simple Dropdown) --}}
        <div class="relative">
            <form method="GET" action="{{ route('keuangan.index') }}">
                <select name="tahun" onchange="this.form.submit()" 
                    class="appearance-none bg-white border border-gray-200 text-gray-700 py-2 pl-4 pr-10 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#700207]/20 focus:border-[#700207] text-sm font-medium cursor-pointer hover:bg-gray-50 transition">
                    @for($y=2023; $y<=date('Y')+1; $y++)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                    @endfor
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                    <i class="fas fa-chevron-down text-xs"></i>
                </div>
            </form>
        </div>
    </div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-10">

        {{-- KOLOM KIRI: INPUT MODAL --}}
        <div class="lg:col-span-4">
            <div class="bg-white rounded-2xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] border border-gray-100 sticky top-24">
                
                <div class="px-6 pt-6 pb-4">
                    <h3 class="font-bold text-gray-900 text-lg">Input Modal Operasional</h3>
                    <p class="text-gray-400 text-xs mt-1">Catat pengeluaran bulanan di sini.</p>
                </div>

                <div class="p-6 pt-2">
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

        {{-- KOLOM KANAN: LAPORAN --}}
        <div class="lg:col-span-8 space-y-8">

            {{-- LOGIC HITUNG TOTAL --}}
            @php 
                $sumModal = 0; $sumOmset = 0; $sumBersih = 0;
                foreach($laporan as $d) {
                    $sumModal += $d['modal'];
                    $sumOmset += $d['omset'];
                    $sumBersih += $d['bersih'];
                }
            @endphp

            {{-- STATS CARDS (Minimalist) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Modal --}}
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between h-32 relative overflow-hidden group hover:border-gray-200 transition">
                    <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition"><i class="fas fa-wallet text-6xl"></i></div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Modal</p>
                    <h3 class="text-2xl font-bold text-gray-800">Rp {{ number_format($sumModal, 0, ',', '.') }}</h3>
                </div>

                {{-- Omset --}}
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between h-32 relative overflow-hidden group hover:border-blue-100 transition">
                    <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition text-blue-500"><i class="fas fa-chart-line text-6xl"></i></div>
                    <p class="text-xs font-bold text-blue-400 uppercase tracking-wider">Total Omset</p>
                    <h3 class="text-2xl font-bold text-blue-600">Rp {{ number_format($sumOmset, 0, ',', '.') }}</h3>
                </div>

                {{-- Laba Bersih --}}
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between h-32 relative overflow-hidden group hover:border-green-100 transition">
                    <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition {{ $sumBersih >= 0 ? 'text-green-500' : 'text-red-500' }}"><i class="fas fa-coins text-6xl"></i></div>
                    <p class="text-xs font-bold {{ $sumBersih >= 0 ? 'text-green-500' : 'text-red-500' }} uppercase tracking-wider">Laba Bersih</p>
                    <h3 class="text-2xl font-bold {{ $sumBersih >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $sumBersih < 0 ? '-' : '' }}Rp {{ number_format(abs($sumBersih), 0, ',', '.') }}
                    </h3>
                </div>
            </div>

            {{-- TABEL --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-white">
                    <h3 class="font-bold text-gray-800 text-sm uppercase tracking-wide">Rincian Bulanan</h3>
                    <button onclick="window.print()" class="text-gray-400 hover:text-gray-600 transition">
                        <i class="fas fa-print"></i>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b border-gray-100 bg-gray-50/50">
                                <th class="px-6 py-4">Bulan</th>
                                <th class="px-6 py-4 text-right">Modal</th>
                                <th class="px-6 py-4 text-right">Omset</th>
                                <th class="px-6 py-4 text-right">Net Profit</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($laporan as $data)
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
                            @endforeach
                        </tbody>
                        
                        {{-- FOOTER TABEL --}}
                        <tfoot class="bg-gray-50 border-t border-gray-200">
                            <tr>
                                <td class="px-6 py-4 font-bold text-xs text-gray-500 uppercase tracking-wider">Total Akhir</td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-600 text-sm">Rp {{ number_format($sumModal, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right font-bold text-blue-700 text-sm">Rp {{ number_format($sumOmset, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right font-extrabold text-base {{ $sumBersih >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    Rp {{ number_format($sumBersih, 0, ',', '.') }}
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