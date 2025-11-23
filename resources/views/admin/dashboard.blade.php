@extends('layouts.navbar_admin')

@section('title', 'Admin Dashboard - DaraCake')

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
    
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-800">Dashboard Overview</h1>
        <p class="text-gray-500 text-sm mt-1">Pantau performa penjualan toko Anda hari ini.</p>
    </div>

    {{-- STATISTIK CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        
        {{-- Card 1: Pendapatan --}}
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 shadow-lg text-white transform hover:scale-105 transition duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Total Pendapatan</p>
                    <h3 class="text-3xl font-bold">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h3>
                </div>
                <div class="p-2 bg-white/20 rounded-lg">
                    <i class="fas fa-wallet text-2xl"></i>
                </div>
            </div>
            <p class="text-xs text-green-100 mt-4 flex items-center gap-1">
                <i class="fas fa-check-circle"></i> Transaksi Selesai
            </p>
        </div>

        {{-- Card 2: Pesanan Baru (Urgent) --}}
        <div class="bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl p-6 shadow-lg text-white transform hover:scale-105 transition duration-300 relative overflow-hidden">
            {{-- Animasi pulse jika ada pesanan baru --}}
            @if($pesananBaru > 0)
                <span class="absolute top-0 right-0 -mt-1 -mr-1 flex h-4 w-4">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-4 w-4 bg-red-200"></span>
                </span>
            @endif

            <div class="flex justify-between items-start">
                <div>
                    <p class="text-orange-100 text-sm font-medium mb-1">Pesanan Baru</p>
                    <h3 class="text-4xl font-bold">{{ $pesananBaru }}</h3>
                </div>
                <div class="p-2 bg-white/20 rounded-lg">
                    <i class="fas fa-bell text-2xl"></i>
                </div>
            </div>
            <p class="text-xs text-orange-100 mt-4">Perlu Konfirmasi Segera!</p>
        </div>

        {{-- Card 3: Sedang Proses --}}
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 shadow-lg text-white transform hover:scale-105 transition duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Sedang Diproses</p>
                    <h3 class="text-4xl font-bold">{{ $pesananDiproses }}</h3>
                </div>
                <div class="p-2 bg-white/20 rounded-lg">
                    <i class="fas fa-spinner text-2xl"></i>
                </div>
            </div>
            <p class="text-xs text-blue-100 mt-4">Dapur / Pengiriman</p>
        </div>

        {{-- Card 4: Stok & Produk --}}
        <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Inventory</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ number_format($totalStok) }} <span class="text-sm text-gray-400 font-normal">unit</span></h3>
                </div>
                <div class="p-2 bg-gray-100 rounded-lg text-gray-600">
                    <i class="fas fa-box text-xl"></i>
                </div>
            </div>
            <div class="border-t pt-4 flex justify-between items-center">
                <span class="text-xs text-gray-500">Produk Terjual</span>
                <span class="text-sm font-bold text-[#700207]">{{ $produkTerjual }} pcs</span>
            </div>
        </div>

    </div>

    {{-- TABEL PESANAN TERBARU --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-800">📦 Pesanan Terbaru Masuk</h3>
            <a href="{{ route('admin.pesanan.index') }}" class="text-sm text-[#700207] font-medium hover:underline">Lihat Semua &rarr;</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs font-semibold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">ID Order</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Detail</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse ($pesananTerbaru as $pesanan)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium text-[#700207]">
                            #{{ $pesanan->id }}
                            <div class="text-xs text-gray-400 font-normal mt-1">
                                {{ $pesanan->created_at->diffForHumans() }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-900">{{ $pesanan->user->name ?? 'Guest' }}</div>
                            <div class="text-xs text-gray-500">{{ $pesanan->user->email ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                {{-- Cek apakah ada custom cake di detail --}}
                                @php
                                    $isCustom = $pesanan->detailTransaksi->contains(fn($d) => !empty($d->catatan));
                                    $itemCount = $pesanan->detailTransaksi->count();
                                @endphp

                                @if($isCustom)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 w-fit">
                                        ✨ Custom Cake
                                    </span>
                                @endif
                                <span class="text-gray-600 text-xs">{{ $itemCount }} Produk dipesan</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-900">
                            Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                            <div class="text-xs font-normal text-gray-500 mt-1 uppercase">
                                {{ $pesanan->metode_pembayaran }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusColor = match($pesanan->status) {
                                    'Menunggu Konfirmasi' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                    'Akan Diproses' => 'bg-blue-100 text-blue-800 border border-blue-200',
                                    'Diproses' => 'bg-indigo-100 text-indigo-800 border border-indigo-200',
                                    'Selesai' => 'bg-green-100 text-green-800 border border-green-200',
                                    'Dibatalkan' => 'bg-red-100 text-red-800 border border-red-200',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusColor }}">
                                {{ $pesanan->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.pesanan.show', $pesanan->id) }}" 
                               class="text-white bg-[#700207] hover:bg-[#5a0105] px-3 py-1.5 rounded-lg text-xs font-medium shadow-sm transition flex items-center justify-center gap-1 mx-auto w-20">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-check-circle text-4xl text-green-100 mb-3"></i>
                                <p>Semua pesanan sudah tertangani.</p>
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