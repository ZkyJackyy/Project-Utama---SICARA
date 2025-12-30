@extends('layouts.navbar_admin')

@section('title', 'Admin Dashboard - DaraCake')

@section('content')
<div class="min-h-screen bg-gray-50/50 p-6 md:p-8 font-sans">
    
    {{-- HEADER SECTION --}}
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 tracking-tight">Dashboard Overview</h1>
            <p class="text-gray-500 text-sm mt-1">Pantau performa penjualan toko Anda hari ini.</p>
        </div>
        <div class="text-sm text-gray-500 bg-white px-4 py-2 rounded-full shadow-sm border border-gray-100 flex items-center gap-2">
            <i class="far fa-calendar-alt text-[#700207]"></i>
            {{ now()->translatedFormat('l, d F Y') }}
        </div>
    </div>

    {{-- STATISTIK CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        
        {{-- Card 1: Pendapatan --}}
        <div class="bg-white rounded-xl p-6 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] border border-gray-100 relative overflow-hidden group hover:border-green-100 transition-all duration-300">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <i class="fas fa-wallet text-6xl text-green-600 transform rotate-12"></i>
            </div>
            <div class="flex flex-col h-full justify-between">
                <div>
                    <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-2">Total Pendapatan</p>
                    <h3 class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h3>
                </div>
                <div class="mt-4 flex items-center text-xs font-medium text-green-600 bg-green-50 w-fit px-2 py-1 rounded-md">
                    <i class="fas fa-check-circle mr-1.5"></i> Transaksi Selesai
                </div>
            </div>
        </div>

        {{-- Card 2: Pesanan Baru (Urgent) --}}
        <div class="bg-white rounded-xl p-6 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] border border-gray-100 relative overflow-hidden group hover:border-orange-100 transition-all duration-300">
            
            {{-- Pulse Animation if Logic is True --}}
            @if($pesananBaru > 0)
                <span class="absolute top-4 right-4 flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                </span>
            @endif

            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <i class="fas fa-bell text-6xl text-orange-600 transform rotate-12"></i>
            </div>
            <div class="flex flex-col h-full justify-between">
                <div>
                    <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-2">Pesanan Baru</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $pesananBaru }}</h3>
                </div>
                <div class="mt-4 flex items-center text-xs font-medium text-orange-600 bg-orange-50 w-fit px-2 py-1 rounded-md">
                    <i class="fas fa-exclamation-circle mr-1.5"></i> Perlu Konfirmasi
                </div>
            </div>
        </div>

        {{-- Card 3: Sedang Proses --}}
        <div class="bg-white rounded-xl p-6 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] border border-gray-100 relative overflow-hidden group hover:border-blue-100 transition-all duration-300">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <i class="fas fa-spinner text-6xl text-blue-600 transform rotate-12"></i>
            </div>
            <div class="flex flex-col h-full justify-between">
                <div>
                    <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-2">Sedang Diproses</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $pesananDiproses }}</h3>
                </div>
                <div class="mt-4 flex items-center text-xs font-medium text-blue-600 bg-blue-50 w-fit px-2 py-1 rounded-md">
                    <i class="fas fa-shipping-fast mr-1.5"></i> Dapur / Kurir
                </div>
            </div>
        </div>

        {{-- Card 4: Inventory --}}
        <div class="bg-white rounded-xl p-6 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] border border-gray-100 relative overflow-hidden group hover:border-gray-200 transition-all duration-300">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <i class="fas fa-boxes text-6xl text-gray-600 transform rotate-12"></i>
            </div>
            <div class="flex flex-col h-full justify-between">
                <div>
                    <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-2">Inventory Total</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ number_format($totalStok) }} <span class="text-base font-normal text-gray-400">unit</span></h3>
                    <div class="flex-grow mb-2">
                        {{-- Cek variabel jumlah produk yang menipis --}}
                        @if ($stokMenipisCount > 0)
                            <p class="text-xs font-bold text-red-600 animate-pulse flex items-center gap-1 mt-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $stokMenipisCount }} Produk Stok Menipis!
                            </p>
                        @elseif ($totalStok == 0)
                            <p class="text-xs font-bold text-gray-500 mt-1">
                                Stok Kosong Melompong
                            </p>
                        @else
                            <p class="text-xs text-green-600 mt-1">
                                Stok Aman
                            </p>
                        @endif
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between border-t border-gray-50 pt-3">
                    <span class="text-xs text-gray-400">Terjual bulan ini</span>
                    <span class="text-xs font-bold text-[#700207]">{{ $produkTerjual }} pcs</span>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL PESANAN TERBARU --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-white">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Pesanan Terbaru</h3>
                <p class="text-xs text-gray-400 mt-1">5 Transaksi terakhir yang masuk ke sistem.</p>
            </div>
            <a href="{{ route('admin.pesanan.index') }}" class="text-xs font-semibold text-[#700207] hover:bg-red-50 px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1">
                Lihat Semua <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode Order</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Detail</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse ($pesananTerbaru as $pesanan)
                    <tr class="hover:bg-gray-50/80 transition duration-150">
                        <td class="px-6 py-4">
                            <span class="font-mono font-medium text-[#700207]">#{{ $pesanan->kode_transaksi ?? $pesanan->id }}</span>
                            <div class="text-[10px] text-gray-400 mt-1">
                                {{ $pesanan->created_at->diffForHumans() }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $pesanan->user->name ?? 'Guest' }}</div>
                            <div class="text-xs text-gray-400 truncate max-w-[150px]">{{ $pesanan->user->email ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1.5">
                                @php
                                    $isCustom = $pesanan->detailTransaksi->contains(fn($d) => !empty($d->catatan));
                                    $itemCount = $pesanan->detailTransaksi->count();
                                @endphp

                                @if($isCustom)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-purple-50 text-purple-700 border border-purple-100 w-fit">
                                        ✨ Custom
                                    </span>
                                @endif
                                <span class="text-gray-500 text-xs">{{ $itemCount }} Produk</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</div>
                            <div class="text-[10px] text-gray-400 mt-0.5 uppercase tracking-wide">
                                {{ str_replace('_', ' ', $pesanan->metode_pembayaran) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusClass = match($pesanan->status) {
                                    'Menunggu Konfirmasi' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                                    'Akan Diproses' => 'bg-blue-50 text-blue-700 border-blue-100',
                                    'Diproses' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                    'Selesai' => 'bg-green-50 text-green-700 border-green-100',
                                    'Dibatalkan' => 'bg-red-50 text-red-700 border-red-100',
                                    default => 'bg-gray-50 text-gray-600 border-gray-100'
                                };
                            @endphp
                            <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-semibold border {{ $statusClass }}">
                                {{ $pesanan->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.pesanan.show', $pesanan->id) }}" 
                               class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white border border-gray-200 text-gray-500 hover:text-[#700207] hover:border-[#700207] transition-colors shadow-sm"
                               title="Lihat Detail">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                    <i class="fas fa-inbox text-2xl text-gray-300"></i>
                                </div>
                                <h3 class="text-sm font-medium text-gray-900">Belum ada pesanan</h3>
                                <p class="text-xs text-gray-500 mt-1 max-w-xs mx-auto">Pesanan baru yang masuk akan muncul di sini secara otomatis.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection