{{-- Ganti dengan file layout utama Anda untuk sisi customer --}}
@extends('layouts.navbar') 
@section('na')
@section('title', 'Daftar Produk Kami')


@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    {{-- Judul Halaman --}}
    <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">
        Pilihan Produk Terbaik Kami
    </h1>

    {{-- Grid untuk Daftar Produk --}}
    @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            
            {{-- Loop untuk setiap produk --}}
            @foreach ($products as $product)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden group transform hover:-translate-y-2 transition-all duration-300">
                
                {{-- Gambar Produk --}}
                <a href="#"> {{-- Arahkan ke halaman detail produk jika ada --}}
                    <img src="{{ asset('storage/produk/' . $product->gambar) }}" alt="Gambar {{ $product->nama_produk }}" class="w-full h-48 object-cover">
                </a>

                {{-- Konten Card --}}
                <div class="p-4">
                    {{-- Nama Kategori/Jenis --}}
                    {{-- Asumsi relasi 'jenis' sudah ada di Model Product --}}
                    <p class="text-xs text-gray-500 mb-1">{{ $product->jenis->jenis_produk }}</p>
                    
                    {{-- Nama Produk --}}
                    <h3 class="text-lg font-semibold text-gray-900 truncate">
                        {{ $product->nama_produk }}
                    </h3>
                    
                    {{-- Harga Produk --}}
                    <p class="text-pink-600 font-bold mt-2 text-xl">
                        Rp {{ number_format($product->harga, 0, ',', '.') }}
                    </p>

                    {{-- Tombol Aksi --}}
                    <div class="mt-4 space-y-2">

                        <a href="{{ route('produk.detail', $product->id) }}" class="block w-full text-center bg-pink-500 text-white font-bold py-2 px-4 rounded-lg mt-4 hover:bg-pink-600 transition-colors">
                            Lihat Detail
                        </a>
                        <a href="#" class="block w-full text-center bg-pink-500 text-white font-bold py-2 px-4 rounded-lg mt-4 hover:bg-pink-600 transition-colors">
                            Order
                        </a>
                        {{-- <a href="https://api.whatsapp.com/send?phone=6281234567890&text=Halo,%20saya%20tertarik%20untuk%20memesan%20produk%20*{{ urlencode($product->nama_produk) }}*" target="_blank" class="block w-full text-center bg-green-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-600 transition-colors">
                            Pesan via WA
                        </a> --}}
                    </div>
                </div>

            </div>
            @endforeach
            
        </div>

        {{-- Link Paginasi --}}
        <div class="mt-12">
            {{ $products->links() }}
        </div>

    @else
        {{-- Pesan Jika Produk Kosong --}}
        <div class="text-center py-16">
            <p class="text-gray-500 text-xl">Oops! Belum ada produk yang tersedia saat ini.</p>
        </div>
    @endif

</div>
@endsection