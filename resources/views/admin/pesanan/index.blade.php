@extends('layouts.navbar_admin')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4">Daftar Pesanan</h2>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- PERUBAHAN: Ganti ke table-auto agar lebar kolom lebih fleksibel --}}
    <table class="w-full table-auto border-collapse border border-gray-300">
        <thead class="bg-pink-600 text-white">
    <tr>
        <th class="px-4 py-2 border">ID</th>
        <th class="px-4 py-2 border">Customer</th>
        <th class="px-4 py-2 border">Tanggal</th>
        <th class="px-4 py-2 border">Metode Pembayaran</th>
        <th class="px-4 py-2 border">Jenis Pesanan</th> {{-- ✅ Tambahan --}}
        <th class="px-4 py-2 border">Total</th>
        <th class="px-4 py-2 border">Status</th>
        <th class="px-4 py-2 border">Aksi</th>
    </tr>
</thead>
<tbody>
@forelse ($pesanan as $item)
<tr class="text-center border hover:bg-gray-50 text-sm">
    <td class="px-4 py-2 border">{{ $item->id }}</td>
    <td class="px-4 py-2 border">{{ $item->user->name ?? 'Tidak diketahui' }}</td>
    <td class="px-4 py-2 border">{{ $item->created_at->format('d M Y, H:i') }}</td>
    <td class="px-4 py-2 border">{{ ucfirst(str_replace('_', ' ', $item->metode_pembayaran)) }}</td>

    {{-- ✅ Kolom baru --}}
    <td class="px-4 py-2 border">
        @if ($item->is_custom)
            <span class="bg-purple-600 text-white px-2 py-1 rounded text-xs font-semibold">Custom</span>
        @else
            <span class="bg-gray-400 text-white px-2 py-1 rounded text-xs font-semibold">Reguler</span>
        @endif
    </td>

    <td class="px-4 py-2 border">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
    <td class="px-4 py-2 border">
        <span class="px-2 py-1 rounded text-white text-xs font-semibold
            @if($item->status == 'Menunggu Konfirmasi') bg-yellow-500 
            @elseif($item->status == 'Akan Diproses') bg-cyan-500 
            @elseif($item->status == 'Diproses') bg-blue-500 
            @elseif($item->status == 'Selesai') bg-green-500 
            @else bg-gray-400 @endif">
            {{ $item->status }}
        </span>
    </td>

    <td class="px-4 py-2 border">
        <a href="{{ route('admin.pesanan.show', $item->id) }}" 
           class="bg-pink-600 hover:bg-pink-700 text-white px-3 py-1 rounded text-sm">
           Detail
        </a>
    </td>
</tr>
@empty
<tr>
    <td colspan="8" class="px-4 py-4 border text-center text-gray-500">
        Belum ada pesanan yang masuk.
    </td>
</tr>
@endforelse
</tbody>


    </table>
</div>
@endsection