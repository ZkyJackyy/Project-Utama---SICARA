@extends('layouts.navbar')

@section('title', 'Keranjang Belanja')

@section('content')
{{-- Latar belakang diubah ke abu-abu netral --}}
<div class="min-h-screen bg-gray-100 flex items-center justify-center py-10 px-4 sm:px-6">

    {{-- Card Utama - Dibuat lebih tajam dan profesional --}}
    <div class="w-full max-w-5xl bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">

        {{-- Header --}}
        <div class="p-8 border-b border-gray-200 flex flex-col sm:flex-row justify-between sm:items-center">
            <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Keranjang Belanja</h1>
            {{-- Link diubah ke warna tema (merah marun) --}}
            <a href="{{ route('customer.produk.list') }}" 
               class="text-sm text-[#700207] font-medium hover:text-[#4a0105] transition mt-3 sm:mt-0">
                ← Kembali Belanja
            </a>
        </div>

        {{-- Notifikasi (Tidak diubah, warna error sudah sesuai) --}}
        @if (session('error'))
            <div class="m-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
        @if (session('warning'))
            <div class="m-6 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Info:</strong>
                <span class="block sm:inline">{{ session('warning') }}</span>
            </div>
        @endif

        @if(count($cart ?? []) > 0)
            {{-- Daftar Item --}}
            <div class="divide-y divide-gray-200">
                @foreach($cart as $item)
                    <div class="flex flex-col md:flex-row justify-between items-center p-6 hover:bg-gray-50/50 transition">
                        {{-- Info Produk --}}
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <img src="{{ $item['image_url'] }}" alt="{{ $item['nama_produk'] }}" 
                                     class="w-24 h-24 rounded-lg object-cover shadow-sm border border-gray-100">
                                {{-- Badge Kuantitas diubah ke warna tema --}}
                                <span class="absolute -top-2 -right-2 bg-[#700207] text-white text-xs px-2 py-0.5 rounded-full shadow">
                                    {{ $item['jumlah'] }}x
                                </span>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">{{ $item['nama_produk'] }}</h2>
                                {{-- Harga dibuat standar agar tidak bentrok dengan link --}}
                                <p class="text-gray-700 font-medium mt-1">Rp {{ number_format($item['harga'], 0, ',', '.') }}</p>
                            </div>
                        </div>

                        {{-- Aksi --}}
                        <div class="flex items-center space-x-3 mt-4 md:mt-0 justify-end">
                            {{-- Update Jumlah --}}
                            <form action="{{ route('keranjang.update', $item['id']) }}" method="POST" class="flex items-center space-x-2">
                                @csrf
                                @method('PUT')
                                {{-- Input focus diubah ke warna tema --}}
                                <input type="number" name="jumlah" value="{{ $item['jumlah'] }}" min="1" 
                                       class="w-16 text-center border-gray-300 rounded-lg shadow-sm focus:border-[#700207] focus:ring-[#700207] sm:text-sm">
                                
                                {{-- Tombol "Ubah" dibuat netral --}}
                                <button type="submit" 
                                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded-lg text-sm font-medium shadow-sm transition border border-gray-300">
                                    Ubah
                                </button>
                            </form>

                            {{-- Hapus --}}
                            <form action="{{ route('keranjang.hapus', $item['id']) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                {{-- Tombol "Hapus" dibuat merah standar --}}
                                <button type="submit" 
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-sm font-medium shadow transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Footer (Total & Checkout) --}}
            {{-- Latar gradasi dihilangkan, diganti bg-gray-50 --}}
            <div class="p-8 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row justify-between sm:items-center">
                <h3 class="text-xl font-semibold text-gray-800">
                    Total:
                    {{-- Total diubah ke warna tema --}}
                    <span class="text-[#700207] font-bold">Rp {{ number_format($total ?? 0, 0, ',', '.') }}</span>
                </h3>

                {{-- Tombol Checkout diubah ke warna tema --}}
                <a href="{{ route('checkout') }}" 
                   class="inline-flex items-center justify-center gap-2 bg-[#700207] hover:bg-[#4a0105] text-white px-6 py-2.5 rounded-lg font-semibold shadow-md hover:shadow-lg transition mt-4 sm:mt-0">
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
                {{-- Tombol "Belanja Sekarang" diubah ke warna tema --}}
                <a href="{{ route('customer.produk.list') }}" 
                   class="mt-6 inline-block bg-[#700207] hover:bg-[#4a0105] text-white px-6 py-2.5 rounded-lg font-semibold shadow-md hover:shadow-lg transition">
                    Belanja Sekarang
                </a>
            </div>
        @endif
    </div>
</div>
@endsection