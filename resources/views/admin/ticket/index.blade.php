@extends('layouts.navbar_admin')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8 font-sans">
    <div class="max-w-6xl mx-auto">
        
        {{-- Header --}}
        <div class="flex justify-between items-end mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Pusat Bantuan (Admin)</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola laporan dan pertanyaan dari customer.</p>
            </div>
            
            {{-- Statistik Singkat (Opsional) --}}
            <div class="flex gap-3">
                <div class="bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-200 text-center">
                    <span class="block text-xs text-gray-400">Menunggu</span>
                    <span class="block font-bold text-[#700207] text-lg">{{ $tickets->where('status', 'open')->count() }}</span>
                </div>
                <div class="bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-200 text-center">
                    <span class="block text-xs text-gray-400">Selesai</span>
                    <span class="block font-bold text-gray-600 text-lg">{{ $tickets->where('status', 'closed')->count() }}</span>
                </div>
            </div>
        </div>

        {{-- Tabel Daftar Tiket --}}
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
            @if($tickets->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID & Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer / Subjek</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Update Terakhir</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($tickets as $ticket)
                        <tr class="hover:bg-gray-50 transition">
                            
                            {{-- Kolom 1: ID & Kategori --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">#{{ $ticket->id }}</div>
                                <div class="text-xs text-gray-500 bg-gray-100 inline-block px-2 py-0.5 rounded mt-1">
                                    {{ $ticket->category }}
                                </div>
                            </td>

                            {{-- Kolom 2: Customer & Subjek --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="ml-0">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $ticket->user->name ?? 'User Terhapus' }}
                                        </div>
                                        <div class="text-sm text-gray-500 truncate max-w-xs">
                                            {{ $ticket->subject }}
                                        </div>
                                        {{-- Badge Pesan Belum Dibaca --}}
                                        @if($ticket->unread_count > 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 mt-1">
                                                {{ $ticket->unread_count }} pesan baru
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Kolom 3: Status --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($ticket->status == 'open')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Open
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Closed
                                    </span>
                                @endif
                            </td>

                            {{-- Kolom 4: Waktu --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $ticket->updated_at->diffForHumans() }}
                            </td>

                            {{-- Kolom 5: Tombol Aksi --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('tickets.show', $ticket->id) }}" class="text-[#700207] hover:text-[#900309] font-bold border border-[#700207] px-3 py-1 rounded-lg hover:bg-red-50 transition">
                                    Buka
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <div class="text-center py-12">
                    <i class="fa fa-inbox text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Tidak ada tiket laporan masuk saat ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection