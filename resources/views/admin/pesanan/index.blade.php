@extends('layouts.navbar_admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">📦 Manajemen Pesanan</h2>
        <div class="text-sm text-gray-500">
            Total Pesanan: <strong>{{ $pesanan->total() }}</strong>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-left">ID & Tanggal</th>
                        <th class="px-6 py-4 text-left">Pelanggan</th>
                        <th class="px-6 py-4 text-left">Pengiriman</th> {{-- KOLOM BARU --}}
                        <th class="px-6 py-4 text-left">Total</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($pesanan as $item)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        
                        {{-- 1. ID & Tanggal --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">#{{ $item->kode_transaksi ?? $item->id }}</div>
                            <div class="text-xs text-gray-500 mt-1">{{ $item->created_at->format('d M Y') }}</div>
                            <div class="text-xs text-gray-400">{{ $item->created_at->format('H:i') }} WIB</div>
                        </td>

                        {{-- 2. Pelanggan --}}
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $item->user->name ?? 'Guest' }}</div>
                            <div class="text-xs text-gray-500">Bayar: {{ strtoupper(str_replace('_', ' ', $item->metode_pembayaran)) }}</div>
                        </td>

                        {{-- 3. Pengiriman (LOGIKA BARU) --}}
                        <td class="px-6 py-4">
                            @if(empty($item->shipping_method))
                                {{-- Data Lama (Kosong) --}}
                                <span class="text-xs text-gray-400 italic">- Data Lama -</span>
                            @elseif(str_contains(strtoupper($item->shipping_method), 'AMBIL') || str_contains(strtoupper($item->shipping_method), 'PICKUP'))
                                {{-- Ambil di Toko --}}
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                    <i class="fas fa-store mr-1"></i> Ambil di Toko
                                </span>
                            @else
                                {{-- Ekspedisi --}}
                                <div class="text-sm font-bold text-gray-700">
                                    <i class="fas fa-truck text-gray-400 mr-1"></i> {{ $item->shipping_method }}
                                </div>
                                <div class="text-xs text-gray-500 truncate w-40" title="{{ $item->shipping_address }}">
                                    {{ Str::limit($item->shipping_address, 30) }}
                                </div>
                            @endif
                        </td>

                        {{-- 4. Total --}}
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-[#700207]">Rp {{ number_format($item->total, 0, ',', '.') }}</div>
                            <div class="text-xs text-gray-500">{{ $item->detailTransaksi->count() }} Produk</div>
                        </td>

                        {{-- 5. Status --}}
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusColor = match($item->status) {
                                    'Menunggu Konfirmasi' => 'bg-yellow-100 text-yellow-800',
                                    'Akan Diproses' => 'bg-blue-100 text-blue-800',
                                    'Diproses' => 'bg-indigo-100 text-indigo-800',
                                    'Selesai' => 'bg-green-100 text-green-800',
                                    'Dibatalkan' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                {{ $item->status }}
                            </span>
                        </td>

                        {{-- 6. Aksi --}}
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.pesanan.show', $item->id) }}" 
                               class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 text-gray-600 rounded-full hover:bg-[#700207] hover:text-white transition shadow-sm"
                               title="Lihat Detail">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500 bg-gray-50">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-box-open text-4xl mb-3 text-gray-300"></i>
                                <p>Belum ada pesanan yang masuk.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $pesanan->links() }}
        </div>
    </div>
</div>
@endsection