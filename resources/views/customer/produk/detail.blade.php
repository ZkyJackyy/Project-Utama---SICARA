@extends('layouts.navbar')
@section('title', $product->nama_produk)

@section('content')
<div class="bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">

        {{-- 1. Breadcrumbs untuk Navigasi --}}
        <div class="text-sm text-gray-500 mb-4">
            <a href="/" class="hover:text-pink-600">Home</a>
            <span class="mx-2">></span>
            <a href="{{ route('customer.produk.list') }}" class="hover:text-pink-600">Produk</a>
            <span class="mx-2">></span>
            <span class="font-medium text-gray-800">{{ $product->nama_produk }}</span>
        </div>

        <div class="bg-white p-6 md:p-8 rounded-lg shadow-lg">
            <div class="md:grid md:grid-cols-5 md:gap-12 items-start">

                {{-- Kolom Kiri: Galeri Gambar (2/5 bagian) --}}
                <div class="md:col-span-2">
                    <div class="mb-4">
                        <img id="main-image" src="{{ asset('storage/produk/' . $product->gambar) }}" alt="Gambar Utama {{ $product->nama_produk }}" class="w-full h-auto rounded-lg shadow-md object-cover aspect-square">
                    </div>
                    {{-- Thumbnail (bisa ditambah jika ada lebih banyak gambar) --}}
                    <div class="flex space-x-2">
                        <img src="{{ asset('storage/produk/' . $product->gambar) }}" class="w-1/4 h-auto rounded-md cursor-pointer border-2 border-pink-500" onclick="changeImage(this.src)">
                        {{-- Contoh thumbnail lain, hapus jika tidak ada --}}
                        <img src="https://via.placeholder.com/150" class="w-1/4 h-auto rounded-md cursor-pointer border-2 border-transparent hover:border-pink-500" onclick="changeImage(this.src)">
                        <img src="https://via.placeholder.com/150" class="w-1/4 h-auto rounded-md cursor-pointer border-2 border-transparent hover:border-pink-500" onclick="changeImage(this.src)">
                    </div>
                </div>

                {{-- Kolom Kanan: Detail & Aksi (3/5 bagian) --}}
                <div class="md:col-span-3 mt-6 md:mt-0">
                    <span class="text-sm font-semibold text-gray-500 uppercase">{{ $product->jenis->jenis_produk }}</span>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mt-2">{{ $product->nama_produk }}</h1>
                    
                    {{-- 2. Rating Bintang & Ulasan --}}
                    <div class="flex items-center mt-2">
                        <div class="flex text-yellow-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"> ... </svg> </div>
                        <span class="text-gray-500 ml-2 text-sm">(12 ulasan)</span>
                    </div>

                    <p class="text-3xl font-bold text-pink-600 my-4">
                        Rp {{ number_format($product->harga, 0, ',', '.') }}
                    </p>
                    
                    <div class="border-t border-gray-200 my-4"></div>

                    <p class="text-gray-600 leading-relaxed">{{ $product->deskripsi }}</p>

                    {{-- 3. Spesifikasi / Poin Utama --}}
                    <div class="mt-6">
                        <h3 class="font-semibold text-gray-800 mb-2">Info Penting:</h3>
                        <ul class="space-y-2 text-gray-600 text-sm">
                            <li class="flex items-center"><svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">...</svg> Stok Tersedia: <strong>{{ $product->stok }}</strong></li>
                            <li class="flex items-center"><svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">...</svg> Dibuat dengan bahan premium</li>
                            <li class="flex items-center"><svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">...</svg> Pengiriman aman ke seluruh kota</li>
                        </ul>
                    </div>
                    
                    <div class="border-t border-gray-200 my-6"></div>

                    {{-- 4. Blok Aksi Utama (CTA) --}}
                    <form action="{{ route('keranjang.tambah', $product->id) }}" method="POST">
    @csrf
    <div class="flex items-center space-x-4 mb-4">
        <label for="quantity" class="font-semibold">Jumlah:</label>
        <div class="flex items-center border border-gray-300 rounded-lg">
            <button type="button" id="btn-minus" class="px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-l-lg">-</button>
            <input type="number" name="jumlah" id="quantity" value="1" min="1" max="{{ $product->stok }}" class="w-12 text-center border-none focus:ring-0">
            <button type="button" id="btn-plus" class="px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-r-lg">+</button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <button type="submit" class="w-full bg-pink-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-pink-700 transition-colors duration-300 disabled:bg-gray-400 flex items-center justify-center" @if($product->stok <= 0) disabled @endif>
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">...</svg>
            Tambah ke Keranjang
        </button>

        <button type="button" class="w-full bg-pink-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-pink-700 transition-colors duration-300">
            Beli Sekarang
        </button>
    </div>
</form>


                </div>
            </div>
        </div>

        {{-- Bagian Produk Terkait (Tidak Perlu Diubah) --}}
        @if($relatedProducts->count() > 0)
            <div class="mt-16">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Anda Mungkin Juga Suka</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach($relatedProducts as $related)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden group transform hover:-translate-y-2 transition-all duration-300">
                        <a href="{{ route('customer.produk.detail', $related->id) }}">
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
</div>

{{-- 5. Tambahkan JavaScript ini sebelum @endsection --}}
<script>
    // Fungsi untuk mengubah gambar utama saat thumbnail diklik
    function changeImage(src) {
        document.getElementById('main-image').src = src;
    }

    // Fungsi untuk tombol kuantitas
    const btnMinus = document.getElementById('btn-minus');
    const btnPlus = document.getElementById('btn-plus');
    const quantityInput = document.getElementById('quantity');
    const maxStock = {{ $product->stok }};

    btnMinus.addEventListener('click', () => {
        let currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
        }
    });

    btnPlus.addEventListener('click', () => {
        let currentValue = parseInt(quantityInput.value);
        if (currentValue < maxStock) {
            quantityInput.value = currentValue + 1;
        }
    });
</script>
@endsection