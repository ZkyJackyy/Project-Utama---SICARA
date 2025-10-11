@extends('layouts.navbar')
@section('title', $product->nama_produk)

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white p-6 md:p-8 rounded-lg shadow-lg">
        <div class="md:grid md:grid-cols-2 md:gap-12 items-start">
            <div>
                <img src="{{ asset('storage/produk/' . $product->gambar) }}" alt="Gambar {{ $product->nama_produk }}" class="w-full h-auto rounded-lg shadow-md object-cover">
            </div>
            <div class="mt-6 md:mt-0">
                <span class="text-sm font-semibold text-gray-500 uppercase">{{ $product->jenis->jenis_produk }}</span>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mt-2">{{ $product->nama_produk }}</h1>
                <p class="text-3xl font-bold text-pink-600 my-4">
                    Rp {{ number_format($product->harga, 0, ',', '.') }}
                </p>
                <div class="text-gray-600 leading-relaxed mt-4">
                    <h3 class="font-semibold text-gray-800 mb-2">Deskripsi:</h3>
                    <p>{{ $product->deskripsi }}</p>
                </div>
                <div class="mt-6">
                    @if($product->stok > 0)
                        <span class="inline-block bg-green-100 text-green-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded-full">Stok: {{ $product->stok }}</span>
                    @else
                        <span class="inline-block bg-red-100 text-red-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded-full">Stok Habis</span>
                    @endif
                </div>
                {{-- ... (Form Aksi lainnya) ... --}}
            </div>
        </div>
    </div>

    {{-- Bagian Produk Terkait --}}
    @if($relatedProducts->count() > 0)
    <div class="mt-16">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Produk Terkait</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach($relatedProducts as $related)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden group transform hover:-translate-y-2 transition-all duration-300">
                {{-- PERBAIKAN DI SINI: ganti $related->slug menjadi $related->id --}}
                <a href="{{ route('produk.detail', $related->id) }}">
                    <img src="{{ asset('storage/produk/' . $related->gambar) }}" alt="Gambar {{ $related->nama_produk }}" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <p class="text-xs text-gray-500 mb-1">{{ $related->jenis->jenis_produk }}</p>
                        <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $related->nama_produk }}</h3>
                        <p class="text-pink-600 font-bold mt-2">Rp {{ number_format($related->harga, 0, ',', '.') }}</p>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection