@extends('layouts.navbar_admin')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        {{-- Card 1: Total Penjualan --}}
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Penjualan (Selesai)</p>
                <p class="text-3xl font-bold">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
            </div>
            <i class="fas fa-dollar-sign text-4xl text-green-500"></i>
        </div>
        
        {{-- Card 2: Pesanan Baru --}}
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Pesanan Baru</p>
                <p class="text-3xl font-bold">{{ $pesananBaru }}</p>
            </div>
            <i class="fas fa-shopping-cart text-4xl text-blue-500"></i>
        </div>
        
        {{-- Card 3: Total Stok Produk (PERUBAHAN) --}}
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Stok Produk</p>
                <p class="text-3xl font-bold">{{ number_format($totalStok) }}</p>
            </div>
            <i class="fas fa-boxes text-4xl text-yellow-500"></i> {{-- Ikon diubah --}}
        </div>
        
        {{-- Card 4: Produk Terjual --}}
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Produk Terjual (Selesai)</p>
                <p class="text-3xl font-bold">{{ number_format($produkTerjual) }}</p>
            </div>
            <i class="fas fa-box-open text-4xl text-red-500"></i>
        </div>
    </div>

    {{-- Tabel Pesanan Terbaru (PERUBAHAN) --}}
    <div class="mt-8 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Pesanan Terbaru</h2>
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead>
                    <tr class="text-left font-bold border-b">
                        <th class="px-6 py-3">ID Pesanan</th>
                        <th class="px-6 py-3">Pelanggan</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Ganti <tbody> dengan loop dinamis --}}
                    @forelse ($pesananTerbaru as $pesanan)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4">#{{ $pesanan->id }}</td>
                        <td class="px-6 py-4">{{ $pesanan->user->name ?? 'User Dihapus' }}</td>
                        <td class="px-6 py-4">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            {{-- Badge status dinamis --}}
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                @if($pesanan->status == 'Menunggu Konfirmasi') bg-yellow-200 text-yellow-800
                                @elseif($pesanan->status == 'Akan Diproses') bg-cyan-200 text-cyan-800
                                @elseif($pesanan->status == 'Diproses') bg-blue-200 text-blue-800
                                @elseif($pesanan->status == 'Selesai') bg-green-200 text-green-800
                                @elseif($pesanan->status == 'Dibatalkan') bg-red-200 text-red-800
                                @else bg-gray-200 text-gray-800
                                @endif">
                                {{ $pesanan->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">{{ $pesanan->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.pesanan.show', $pesanan->id) }}" class="text-pink-600 hover:text-pink-800 font-medium">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Belum ada pesanan terbaru tes 11.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection