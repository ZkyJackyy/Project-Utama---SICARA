@extends('layouts.navbar_admin')

@section('content')
<div class="min-h-screen bg-gray-50 py-10 px-6 font-sans text-gray-800">
    
    {{-- HEADER SECTION --}}
    <div class="max-w-7xl mx-auto mb-10 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#700207] tracking-tight flex items-center gap-3">
                <i class="fas fa-chart-pie text-2xl"></i> Laporan Keuangan
            </h1>
            <p class="text-gray-500 text-sm mt-1">Kelola arus kas, modal, dan pantau keuntungan bersih (Net Profit).</p>
        </div>
        
        {{-- Filter Tahun (Dropdown Modern) --}}
        <div class="bg-white p-1.5 rounded-xl shadow-sm border border-gray-200 flex items-center">
            <span class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Tahun:</span>
            <form method="GET" action="{{ route('keuangan.index') }}">
                <select name="tahun" onchange="this.form.submit()" 
                    class="border-none bg-transparent text-[#700207] font-bold text-lg focus:ring-0 cursor-pointer py-0 pl-0 pr-8">
                    @for($y=2023; $y<=date('Y')+1; $y++)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
        </div>
    </div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8">

        {{-- KOLOM KIRI: INPUT MODAL (3 Kolom Grid) --}}
        <div class="lg:col-span-4">
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden sticky top-24">
                
                <div class="bg-[#700207] px-6 py-5">
                    <h3 class="text-white font-bold text-lg flex items-center gap-2">
                        <i class="fas fa-wallet"></i> Input Modal
                    </h3>
                    <p class="text-red-200 text-xs mt-1">Catat pengeluaran modal operasional bulanan di sini.</p>
                </div>

                <div class="p-6">
                    <form action="{{ route('keuangan.store') }}" method="POST" class="space-y-5">
                        @csrf
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Bulan</label>
                                <select name="bulan" class="w-full border-gray-200 bg-gray-50 rounded-xl focus:ring-[#700207] focus:border-[#700207] transition text-sm py-2.5">
                                    @for($m=1; $m<=12; $m++)
                                        <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>
                                            {{ date("F", mktime(0, 0, 0, $m, 10)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tahun</label>
                                <select name="tahun" class="w-full border-gray-200 bg-gray-50 rounded-xl focus:ring-[#700207] focus:border-[#700207] transition text-sm py-2.5">
                                    @for($y=2023; $y<=date('Y')+1; $y++)
                                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nominal Modal (Rp)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-400 font-bold">Rp</span>
                                </div>
                                <input type="number" name="jumlah_modal" placeholder="0" required
                                    class="w-full pl-10 border-gray-200 bg-gray-50 rounded-xl focus:ring-[#700207] focus:border-[#700207] transition py-3 font-medium text-gray-800">
                            </div>
                            <p class="text-[10px] text-gray-400 mt-2 italic">
                                <i class="fas fa-info-circle"></i> Termasuk belanja bahan, listrik, gaji, dll.
                            </p>
                        </div>

                        <button type="submit" class="w-full bg-[#700207] hover:bg-[#5a0105] text-white font-bold py-3 rounded-xl transition shadow-lg shadow-red-900/20 flex justify-center items-center gap-2 group">
                            <span>Simpan Data</span>
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: LAPORAN (9 Kolom Grid) --}}
        <div class="lg:col-span-8 space-y-6">

            {{-- HITUNG TOTAL TAHUNAN DULU (Logic di Blade agar Realtime sesuai view) --}}
            @php 
                $sumModal = 0; $sumOmset = 0; $sumBersih = 0;
                foreach($laporan as $d) {
                    $sumModal += $d['modal'];
                    $sumOmset += $d['omset'];
                    $sumBersih += $d['bersih'];
                }
            @endphp

            {{-- KARTU RINGKASAN TAHUNAN --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Card Modal --}}
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-gray-100 text-gray-600 rounded-lg"><i class="fas fa-money-bill-wave"></i></div>
                        <p class="text-xs font-bold text-gray-400 uppercase">Total Modal</p>
                    </div>
                    <h3 class="text-xl font-extrabold text-gray-700">Rp {{ number_format($sumModal, 0, ',', '.') }}</h3>
                </div>

                {{-- Card Omset --}}
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-blue-100">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-blue-50 text-blue-600 rounded-lg"><i class="fas fa-cash-register"></i></div>
                        <p class="text-xs font-bold text-blue-400 uppercase">Total Omset</p>
                    </div>
                    <h3 class="text-xl font-extrabold text-blue-700">Rp {{ number_format($sumOmset, 0, ',', '.') }}</h3>
                </div>

                {{-- Card Laba Bersih --}}
                <div class="bg-white p-5 rounded-2xl shadow-sm border {{ $sumBersih >= 0 ? 'border-green-100' : 'border-red-100' }}">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 {{ $sumBersih >= 0 ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }} rounded-lg">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                        <p class="text-xs font-bold {{ $sumBersih >= 0 ? 'text-green-500' : 'text-red-500' }} uppercase">Laba Bersih</p>
                    </div>
                    <h3 class="text-xl font-extrabold {{ $sumBersih >= 0 ? 'text-green-700' : 'text-red-700' }}">
                        Rp {{ number_format($sumBersih, 0, ',', '.') }}
                    </h3>
                </div>
            </div>

            {{-- TABEL DETAIL BULANAN --}}
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800">Rincian Bulanan ({{ $tahun }})</h3>
                    <button onclick="window.print()" class="text-xs text-gray-500 hover:text-[#700207] flex items-center gap-1 transition">
                        <i class="fas fa-print"></i> Cetak
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-100 text-gray-500 text-xs uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Bulan</th>
                                <th class="px-6 py-4 font-semibold text-right">Modal (Keluar)</th>
                                <th class="px-6 py-4 font-semibold text-right">Omset (Masuk)</th>
                                <th class="px-6 py-4 font-semibold text-right">Laba Bersih</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-sm">
                            @foreach($laporan as $data)
                            <tr class="hover:bg-gray-50 transition group">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full {{ $data['bersih'] >= 0 ? 'bg-green-400' : ($data['modal'] > 0 ? 'bg-red-400' : 'bg-gray-300') }}"></span>
                                        {{ $data['nama_bulan'] }}
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4 text-right">
                                    <span class="{{ $data['modal'] > 0 ? 'text-gray-700' : 'text-gray-300' }}">
                                        Rp {{ number_format($data['modal'], 0, ',', '.') }}
                                    </span>
                                </td>
                                
                                <td class="px-6 py-4 text-right">
                                    <span class="{{ $data['omset'] > 0 ? 'text-blue-600 font-bold' : 'text-gray-300' }}">
                                        Rp {{ number_format($data['omset'], 0, ',', '.') }}
                                    </span>
                                </td>
                                
                                <td class="px-6 py-4 text-right">
                                    @if($data['modal'] == 0 && $data['omset'] == 0)
                                        <span class="text-gray-300">-</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold {{ $data['bersih'] >= 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $data['bersih'] >= 0 ? '+' : '' }} Rp {{ number_format($data['bersih'], 0, ',', '.') }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        
                        {{-- FOOTER TABEL --}}
                        <tfoot class="bg-[#700207] text-white">
                            <tr>
                                <td class="px-6 py-4 font-bold text-sm uppercase">TOTAL AKHIR</td>
                                <td class="px-6 py-4 text-right font-medium text-red-100">Rp {{ number_format($sumModal, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right font-bold text-white">Rp {{ number_format($sumOmset, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right font-extrabold text-lg {{ $sumBersih >= 0 ? 'text-green-200' : 'text-red-200' }}">
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