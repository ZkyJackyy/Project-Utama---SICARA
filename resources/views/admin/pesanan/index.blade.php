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
                        <th class="px-6 py-4 text-left">Ringkasan Item</th>
                        <th class="px-6 py-4 text-center">Pembayaran</th>
                        <th class="px-6 py-4 text-center">Bukti</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($pesanan as $item)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        
                        {{-- 1. ID & Tanggal --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">#{{ $item->id }}</div>
                            <div class="text-xs text-gray-500 mt-1">{{ $item->created_at->format('d M Y') }}</div>
                            <div class="text-xs text-gray-400">{{ $item->created_at->format('H:i') }} WIB</div>
                        </td>

                        {{-- 2. Pelanggan --}}
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $item->user->name ?? 'Guest' }}</div>
                            {{-- Jika nanti ada kolom no_hp di user, bisa tambah tombol WA disini --}}
                            <div class="text-xs text-gray-500">ID: {{ $item->user_id }}</div>
                        </td>

                        {{-- 3. Ringkasan Item (PENTING) --}}
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-700 max-w-xs">
                                @if($item->is_custom)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 mb-1">
                                        ✨ Custom Cake
                                    </span>
                                @endif
                                
                                <ul class="list-disc list-inside text-xs text-gray-600">
                                    @foreach($item->detailTransaksi->take(2) as $detail)
                                        <li class="truncate">
                                            {{ $detail->produk->nama_produk ?? 'Produk Dihapus' }} 
                                            <span class="font-semibold">x{{ $detail->jumlah }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                
                                @if($item->detailTransaksi->count() > 2)
                                    <span class="text-xs text-gray-400 italic">+ {{ $item->detailTransaksi->count() - 2 }} item lainnya</span>
                                @endif
                            </div>
                        </td>

                        {{-- 4. Metode & Total --}}
                        <td class="px-6 py-4 text-center">
                            <div class="text-sm font-bold text-[#700207]">Rp {{ number_format($item->total, 0, ',', '.') }}</div>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $item->metode_pembayaran == 'cod' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ strtoupper($item->metode_pembayaran) }}
                            </span>
                        </td>

                        {{-- 5. Bukti Bayar (PENTING) --}}
                        <td class="px-6 py-4 text-center">
                            @if($item->metode_pembayaran == 'cod')
                                <span class="text-xs text-gray-400 italic">-</span>
                            @elseif($item->bukti_pembayaran)
                                <a href="{{ asset('storage/' . $item->bukti_pembayaran) }}" target="_blank" class="group relative inline-block">
                                    <img src="{{ asset('storage/' . $item->bukti_pembayaran) }}" alt="Bukti" class="h-10 w-10 object-cover rounded-md border border-gray-300 shadow-sm group-hover:scale-150 transition-transform z-10">
                                    <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 w-max px-2 py-1 bg-black text-white text-[10px] rounded opacity-0 group-hover:opacity-100 transition">Lihat</span>
                                </a>
                            @else
                                <span class="text-xs text-red-500 font-semibold">Belum Upload</span>
                            @endif
                        </td>

                        {{-- 6. Status --}}
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

                        {{-- 7. Aksi --}}
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
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500 bg-gray-50">
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