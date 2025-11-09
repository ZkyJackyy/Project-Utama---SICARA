@extends('layouts.navbar')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 flex items-center justify-center py-10 px-4 sm:px-6">

    {{-- Card Utama --}}
    <div class="w-full max-w-5xl bg-white/80 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-gray-200/50">

        {{-- Header --}}
        <div class="p-8 border-b border-gray-200/60 flex flex-col sm:flex-row justify-between sm:items-center">
            <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Keranjang Belanja</h1>
            <a href="{{ route('customer.produk.list') }}" 
               class="text-sm text-indigo-600 font-medium hover:text-indigo-800 transition mt-3 sm:mt-0">
                ← Kembali Belanja
            </a>
        </div>

        @if(count($cart ?? []) > 0)
            {{-- Daftar Item --}}
            <div class="divide-y divide-gray-200/60">
                @foreach($cart as $item)
                    <div class="flex flex-col md:flex-row justify-between items-center p-6 hover:bg-gray-50 transition">
                        {{-- Info Produk --}}
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <img src="{{ $item['image_url'] }}" alt="{{ $item['nama_produk'] }}" 
                                     class="w-24 h-24 rounded-2xl object-cover shadow-md hover:scale-105 transition-transform duration-200">
                                <span class="absolute -top-2 -right-2 bg-indigo-500 text-white text-xs px-2 py-0.5 rounded-full shadow">
                                    {{ $item['jumlah'] }}x
                                </span>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">{{ $item['nama_produk'] }}</h2>
                                <p class="text-indigo-600 font-medium mt-1">Rp {{ number_format($item['harga'], 0, ',', '.') }}</p>
                            </div>
                        </div>

                        {{-- Aksi --}}
                        <div class="flex items-center space-x-3 mt-4 md:mt-0 justify-end">
                            {{-- Update Jumlah --}}
                            <form action="{{ route('keranjang.update', $item['id']) }}" method="POST" class="flex items-center space-x-2">
                                @csrf
                                @method('PUT')
                                <input type="number" name="jumlah" value="{{ $item['jumlah'] }}" min="1" 
                                       class="w-16 text-center border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <button type="submit" 
                                        class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1.5 rounded-xl text-sm font-medium shadow transition">
                                    Ubah
                                </button>
                            </form>

                            {{-- Hapus --}}
                            <form action="{{ route('keranjang.hapus', $item['id']) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-rose-500 hover:bg-rose-600 text-white px-3 py-1.5 rounded-xl text-sm font-medium shadow transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Footer --}}
            <div class="p-8 bg-gradient-to-r from-indigo-100 via-blue-100 to-purple-100 border-t border-gray-200/60 flex flex-col sm:flex-row justify-between sm:items-center">
                <h3 class="text-xl font-semibold text-gray-800">
                    Total:
                    <span class="text-indigo-700 font-bold">Rp {{ number_format($total ?? 0, 0, ',', '.') }}</span>
                </h3>

                <a href="{{ route('checkout') }}" 
                   class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-6 py-2.5 rounded-xl font-semibold shadow-md hover:shadow-lg transition mt-4 sm:mt-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                    Lanjut ke Pembayaran
                </a>
            </div>

        @else
            {{-- Kosong --}}
            <div class="p-16 text-center text-gray-600">
                <p class="text-xl font-medium">Keranjang Anda masih kosong.</p>
                <a href="{{ route('customer.produk.list') }}" 
                   class="mt-6 inline-block bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-6 py-2.5 rounded-xl font-semibold shadow-md hover:shadow-lg transition">
                    Belanja Sekarang
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
