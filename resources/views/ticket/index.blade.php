@extends('layouts.navbar')

@section('content')
<div class="min-h-screen bg-gray-50 py-10 px-4 sm:px-6 lg:px-8 font-['Poppins']">
    <div class="max-w-5xl mx-auto">
        
        {{-- HEADER SECTION --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">Layanan Bantuan</h1>
                <p class="text-sm text-gray-500 mt-1">Pantau status laporan dan percakapan Anda dengan Admin.</p>
            </div>
            
            <a href="{{ route('tickets.create') }}" class="group relative inline-flex items-center justify-center px-6 py-3 text-sm font-bold text-white transition-all duration-200 bg-[#700207] font-pj rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#700207] hover:bg-[#5a0105] shadow-lg shadow-red-900/30">
                <i class="fa fa-plus mr-2 group-hover:rotate-90 transition-transform duration-300"></i>
                Buat Laporan
            </a>
        </div>

        {{-- DAFTAR TIKET (GRID LAYOUT) --}}
        <div class="space-y-4">
            @forelse($tickets as $ticket)
            <a href="{{ route('tickets.show', $ticket->id) }}" class="block group">
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md hover:border-red-100 transition-all duration-300 relative overflow-hidden">
                    
                    {{-- Dekorasi Hover (Garis Merah di Kiri) --}}
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#700207] transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>

                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        
                        {{-- Bagian Kiri: Info Utama --}}
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                {{-- Badge Kategori --}}
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                    <i class="fa fa-tag mr-1.5 text-xs text-gray-400"></i> {{ $ticket->category }}
                                </span>
                                
                                {{-- Waktu --}}
                                <span class="text-xs text-gray-400 flex items-center">
                                    <i class="fa fa-clock mr-1"></i> {{ $ticket->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-[#700207] transition-colors line-clamp-1">
                                #{{ $ticket->id }} - {{ $ticket->subject }}
                            </h3>
                            
                            <p class="text-sm text-gray-500 mt-1 line-clamp-1 group-hover:text-gray-600">
                                Klik untuk melihat detail percakapan...
                            </p>
                        </div>

                        {{-- Bagian Kanan: Status & Arrow --}}
                        <div class="flex items-center gap-4 self-end sm:self-center">
                            {{-- Status Badge --}}
                            @if($ticket->status == 'open')
                                <div class="flex items-center px-3 py-1 rounded-full bg-green-50 border border-green-100">
                                    <span class="relative flex h-2 w-2 mr-2">
                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                      <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                    </span>
                                    <span class="text-xs font-bold text-green-700 uppercase tracking-wide">Diproses</span>
                                </div>
                            @else
                                <div class="flex items-center px-3 py-1 rounded-full bg-gray-100 border border-gray-200">
                                    <i class="fa fa-check-circle text-gray-400 mr-1.5"></i>
                                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">Selesai</span>
                                </div>
                            @endif

                            {{-- Arrow Icon --}}
                            <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-[#700207] group-hover:text-white transition-colors duration-300">
                                <i class="fa fa-chevron-right text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            @empty
                {{-- EMPTY STATE (Tampilan jika belum ada laporan) --}}
                <div class="text-center py-16 bg-white rounded-3xl border-2 border-dashed border-gray-200">
                    <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce-slow">
                        <i class="fa fa-clipboard-list text-3xl text-[#700207]"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Belum Ada Laporan</h3>
                    <p class="text-gray-500 mt-2 max-w-sm mx-auto mb-6">
                        Jika Anda memiliki kendala pesanan atau pertanyaan, jangan ragu untuk menghubungi kami.
                    </p>
                    <a href="{{ route('tickets.create') }}" class="inline-flex items-center justify-center px-6 py-3 text-sm font-bold text-[#700207] bg-red-50 rounded-xl hover:bg-red-100 transition-colors">
                        Buat Laporan Sekarang
                    </a>
                </div>
            @endforelse
        </div>

    </div>
</div>

<style>
    /* Animasi halus untuk empty state icon */
    @keyframes bounce-slow {
        0%, 100% { transform: translateY(-5%); }
        50% { transform: translateY(5%); }
    }
    .animate-bounce-slow {
        animation: bounce-slow 2s infinite ease-in-out;
    }
</style>
@endsection