@extends('layouts.navbar')
@section('title', $product->nama_produk)

@section('content')
<div class="bg-gray-100">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">

        {{-- 1. Breadcrumbs --}}
        <div class="text-sm text-gray-500 mb-4">
            <a href="/" class="hover:text-[#700207]">Home</a>
            <span class="mx-2">></span>
            <a href="{{ route('customer.produk.list') }}" class="hover:text-[#700207]">Produk</a>
            <span class="mx-2">></span>
            <span class="font-medium text-gray-800">{{ $product->nama_produk }}</span>
        </div>

        {{-- Kartu Utama --}}
        <div class="bg-white p-6 md:p-8 rounded-lg shadow-md border border-gray-200">
            <div class="md:grid md:grid-cols-5 md:gap-12 items-start">

                {{-- Kolom Kiri: Galeri Gambar --}}
                <div class="md:col-span-2">
                    <div class="mb-4">
                        <img id="main-image" src="{{ asset('storage/produk/' . $product->gambar) }}" alt="{{ $product->nama_produk }}" class="w-full h-auto rounded-lg border border-gray-200 object-cover aspect-square">
                    </div>
                </div>

                {{-- Kolom Kanan: Detail & Aksi --}}
                <div class="md:col-span-3 mt-6 md:mt-0">
                    <span class="text-sm font-semibold text-gray-500 uppercase">{{ $product->jenis->jenis_produk }}</span>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mt-2">{{ $product->nama_produk }}</h1>
                    
                    {{-- 2. Rating Bintang & Ringkasan (DINAMIS) --}}
                    <div class="flex items-center mt-2">
                        <div class="flex text-yellow-400">
                            @php $rating = round($avgRating); @endphp
                            @foreach(range(1,5) as $i)
                                @if($rating >= $i)
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                @endif
                            @endforeach
                        </div>
                        <span class="text-gray-500 ml-2 text-sm">({{ $totalUlasan }} ulasan) | {{ number_format($avgRating, 1) }} / 5.0</span>
                    </div>

                    {{-- Harga --}}
                    <p class="text-3xl font-bold text-[#700207] my-4">
                        Rp {{ number_format($product->harga, 0, ',', '.') }}
                    </p>
                    
                    <div class="border-t border-gray-200 my-4"></div>

                    <p class="text-gray-600 leading-relaxed">{{ $product->deskripsi }}</p>

                    {{-- Info Penting --}}
                    <div class="mt-6">
                        <h3 class="font-semibold text-gray-800 mb-2">Info Penting:</h3>
                        <ul class="space-y-2 text-gray-600 text-sm">
                            <li class="flex items-center"><svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Stok Tersedia: <strong>{{ $product->stok }}</strong></li>
                            <li class="flex items-center"><svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Dibuat dengan bahan premium</li>
                            <li class="flex items-center"><svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Pengiriman aman ke seluruh kota</li>
                        </ul>
                    </div>
                    
                    <div class="border-t border-gray-200 my-6"></div>

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Oops!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    {{-- Form Beli --}}
                    <form action="{{ route('keranjang.tambah', $product->id) }}" method="POST">
                        @csrf
                        <div class="flex items-center space-x-4 mb-4">
                            <label for="quantity" class="font-semibold">Jumlah:</label>
                            <div class="flex items-center border border-gray-300 rounded-lg">
                                <button type="button" id="btn-minus" class="px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-l-lg">-</button>
                                <input type="number" name="jumlah" id="quantity" value="1" min="1" max="{{ $product->stok }}" class="w-12 text-center border-y-0 border-x-0 text-gray-900 focus:ring-[#700207] focus:border-[#700207]">
                                <button type="button" id="btn-plus" class="px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-r-lg">+</button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <button type="submit" class="w-full bg-[#700207] text-white font-bold py-3 px-6 rounded-lg hover:bg-[#4a0105] transition-colors duration-300 disabled:bg-gray-400 flex items-center justify-center" @if($product->stok <= 0) disabled @endif>
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                @if($product->stok <= 0) Stok Habis @else Tambah ke Keranjang @endif
                            </button>
                            <button type="submit" name="action" value="buy_now" class="w-full bg-transparent border border-[#700207] text-[#700207] font-bold py-3 px-6 rounded-lg hover:bg-red-50 transition-colors duration-300 disabled:bg-gray-400 disabled:text-gray-500 disabled:border-gray-400 flex items-center justify-center" @if($product->stok <= 0) disabled @endif>
                                Beli Sekarang
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        
        {{-- ========================================== --}}
        {{--       BAGIAN BARU: TABEL / LIST ULASAN     --}}
        {{-- ========================================== --}}
        <div class="mt-12 bg-white p-6 md:p-8 rounded-lg shadow-md border border-gray-200">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                Ulasan Pelanggan
                <span class="ml-3 text-sm font-normal text-white bg-[#700207] px-2 py-1 rounded-full">{{ $totalUlasan }}</span>
            </h2>

            @if($product->ulasan->count() > 0)
                <div class="space-y-6">
                    @foreach($product->ulasan as $ulasan)
                        <div class="border-b border-gray-100 pb-6 last:border-0 last:pb-0">
                            <div class="flex items-start">
                                {{-- Avatar Dummy (Inisial Nama) --}}
                                <div class="flex-shrink-0 mr-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold">
                                        {{ substr($ulasan->user->name ?? 'A', 0, 1) }}
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-bold text-gray-900">{{ $ulasan->user->name ?? 'Pengguna' }}</h4>
                                        <span class="text-xs text-gray-400">{{ $ulasan->created_at->format('d M Y') }}</span>
                                    </div>
                                    
                                    {{-- Bintang per User --}}
                                    <div class="flex text-yellow-400 my-1">
                                        @foreach(range(1,5) as $star)
                                            @if($ulasan->rating >= $star)
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            @else
                                                <svg class="w-3 h-3 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            @endif
                                        @endforeach
                                    </div>

                                    <p class="text-sm text-gray-600 mt-1">{{ $ulasan->ulasan }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    <p class="text-gray-500">Belum ada ulasan untuk produk ini.</p>
                </div>
            @endif
        </div>

        {{-- Produk Terkait --}}
        @if($relatedProducts->count() > 0)
            <div class="mt-16">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Anda Mungkin Juga Suka</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach($relatedProducts as $related)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden group border border-gray-200 hover:shadow-lg transition-all duration-300">
                            <a href="{{ route('customer.produk.detail', $related->id) }}">
                                <img src="{{ asset('storage/produk/' . $related->gambar) }}" alt="{{ $related->nama_produk }}" class="w-full h-48 object-cover">
                                <div class="p-4">
                                    <p class="text-xs text-gray-500 mb-1">{{ $related->jenis->jenis_produk }}</p>
                                    <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $related->nama_produk }}</h3>
                                    <p class="text-[#700207] font-bold mt-2">Rp {{ number_format($related->harga, 0, ',', '.') }}</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    // JS Lama Anda (Aman)
    function changeImage(src) {
        document.getElementById('main-image').src = src;
    }

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