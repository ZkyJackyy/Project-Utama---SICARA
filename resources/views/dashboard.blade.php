@extends('layouts.navbar')

@section('title', 'Beranda')

@section('content')

{{-- Hero Section --}}
<section 
    class="relative w-full min-h-screen flex items-center justify-center text-center font-['Poppins'] bg-cover bg-center bg-no-repeat"
    style="background-image: url('{{ asset('gambar/kuecoklat.jpg') }}');">

    {{-- Overlay Gelap --}}
    <div class="absolute inset-0 bg-black/60"></div>

    <div class="relative z-10 max-w-3xl px-6">

        <h1 class="text-4xl md:text-6xl font-['Playfair_Display'] font-extrabold text-white mb-6 leading-snug">
            DaraCake
        </h1>

        <p class="text-gray-100 font-medium text-lg md:text-xl mb-10">
            "Rasa Manis yang Menghangatkan Hati."
        </p>
    </div>
</section>

{{-- Best Seller Section --}}
<section class="py-24 bg-[#ECE6DA] font-['Poppins']" id="best-seller">
    <div class="max-w-7xl mx-auto px-8 text-center">

        {{-- Title --}}
        <h2 class="text-4xl md:text-5xl font-['Playfair_Display'] font-extrabold text-[#700207] mb-4">
            Best Seller
        </h2>
        <p class="text-gray-600 text-base md:text-lg max-w-2xl mx-auto mb-14">
            Produk pilihan pelanggan yang paling banyak dipesan setiap minggu
        </p>

        {{-- Grid Produk --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10">

            @forelse($products ?? [] as $produk)
            <a href="{{ route('customer.produk.detail', $produk->id) }}"
               class="bg-white rounded-3xl shadow-lg hover:shadow-2xl hover:-translate-y-2 
                      transition-all duration-300 p-5 group flex flex-col h-full">

                {{-- Gambar (Aspect Ratio Fixed) --}}
                <div class="relative w-full h-64 overflow-hidden rounded-2xl mb-5">
                    <img src="{{ asset('storage/produk/' . $produk->gambar) }}" 
                         alt="{{ $produk->nama_produk }}"
                         class="w-full h-full object-cover transform group-hover:scale-110 duration-500">
                    
                    {{-- Badge Stok (Overlay di gambar pojok kiri atas) --}}
                    <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full shadow-sm flex items-center gap-2">
                        <i class="fas fa-box-open text-[#700207] text-xs"></i>
                        <span class="text-xs font-semibold text-gray-800">Stok: {{ $produk->stok }}</span>
                    </div>
                </div>

                {{-- Konten Produk --}}
                <div class="flex flex-col flex-grow text-left px-2">
                    
                    {{-- Nama --}}
                    <h3 class="text-xl font-['Playfair_Display'] font-bold text-[#700207] mb-1 group-hover:text-[#b1354a] transition line-clamp-2">
                        {{ $produk->nama_produk }}
                    </h3>

                    {{-- Spacer agar harga selalu di bawah --}}
                    <div class="flex-grow"></div>

                    {{-- Garis Pemisah Tipis --}}
                    <div class="w-full h-px bg-gray-100 my-4"></div>

                    {{-- Harga + Tombol Kecil --}}
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-400 font-medium">Harga</span>
                            <p class="text-[#700207] font-bold text-xl">
                                Rp {{ number_format($produk->harga, 0, ',', '.') }}
                            </p>
                        </div>

                        {{-- Tombol Panah Bulat --}}
                        <div class="w-10 h-10 rounded-full bg-[#F2D9D9] text-[#700207] flex items-center justify-center group-hover:bg-[#700207] group-hover:text-white transition-colors duration-300 shadow-sm">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </div>

            </a>
            @empty
            <div class="col-span-1 md:col-span-3 py-12">
                <div class="flex flex-col items-center justify-center text-gray-400">
                    <i class="fas fa-cookie-bite text-6xl mb-4 opacity-50"></i>
                    <p class="text-lg font-medium">Belum ada produk tersedia saat ini.</p>
                </div>
            </div>
            @endforelse

        </div>

    </div>
</section>


{{-- Tentang Dara Cake --}}
<section class="relative py-28 font-['Poppins'] text-white bg-[#79533E]">

    <div class="relative z-20 max-w-7xl mx-auto flex flex-col md:flex-row items-center gap-16 px-8">

        {{-- Gambar --}}
        <div class="md:w-1/2 flex justify-center">
            <div class="relative w-80 h-80 rounded-3xl overflow-hidden shadow-2xl border-4 border-[#ECCFC3] group">
                <img src="{{ asset('gambar/kue5.png') }}" 
                     alt="Dara Cake Team" 
                     class="object-cover w-full h-full transition-transform duration-700 group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
            </div>
        </div>

        {{-- Konten --}}
        <div class="md:w-1/2 text-center md:text-left">
            <h2 class="text-4xl md:text-5xl font-['Playfair_Display'] font-bold tracking-tight mb-4">
                Tentang <span class="text-[#ECCFC3]">Dara Cake</span>
            </h2>

            <p class="text-[#F8EAEA] leading-relaxed mb-6">
                Berdiri sejak <span class="font-semibold text-white">2018</span>, <strong>Dara Cake</strong> hadir untuk menghadirkan kebahagiaan dalam setiap gigitan.
                Kami menciptakan kue dengan bahan berkualitas, cinta, dan kreativitas — manis untuk lidah, hangat untuk hati.
            </p>

            <p class="text-[#F8EAEA] leading-relaxed mb-8">
                Dari <em>cupcake</em> lucu sampai <em>wedding cake</em> elegan, semuanya dibuat secara personal, karena setiap momen manis layak dirayakan sepenuh hati.
            </p>

            <a href="/products" 
               class="inline-block bg-[#ECCFC3] text-[#700207] hover:bg-white font-semibold px-6 py-3 rounded-full shadow-lg transform hover:scale-105 transition">
                Lihat Produk Kami
            </a>
        </div>
    </div>
</section>




{{-- 📬 Footer --}}
<footer class="relative bg-[#ECE6DA] text-rose-800 font-['Poppins']">

    <div class="relative max-w-7xl mx-auto px-6 py-20 grid grid-cols-1 md:grid-cols-4 gap-12">
        {{-- Newsletter --}}
        <div>
            <h4 class="text-2xl font-['Playfair_Display'] font-bold mb-4 text-[#700207]">Newsletter</h4>
            <p class="text-rose-700 text-sm mb-6 leading-relaxed">Dapatkan kabar, resep, dan promo manis dari kami</p>
            <form class="flex">
                <input 
                    type="email" 
                    placeholder="Email kamu..." 
                    class="flex-1 px-4 py-3 rounded-l-full text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#700207]">
                <button 
                    type="submit"
                    class="bg-[#700207] hover:bg-[#4a0105] text-white font-semibold px-6 py-3 rounded-r-full transition-all duration-300 shadow-md">
                    GO
                </button>
            </form>
        </div>

        {{-- Latest News --}}
        <div>
            <h4 class="text-2xl font-['Playfair_Display'] font-bold mb-4 text-[#700207]">Berita Terbaru</h4>
            <ul class="text-rose-700 text-sm space-y-3">
                <li class="hover:text-[#700207] transition">Peluncuran Menu Baru</li>
                <li class="hover:text-[#700207] transition">Diskon Spesial Minggu Ini</li>
                <li class="hover:text-[#700207] transition">Tutorial Baking dari Chef Dara</li>
            </ul>
        </div>

        {{-- Tags --}}
        <div>
            <h4 class="text-2xl font-['Playfair_Display'] font-bold mb-4 text-[#700207]">Tag Populer</h4>
            <div class="flex flex-wrap gap-3 text-sm">
                @foreach(['Cupcake', 'Dessert', 'Sweet', 'Birthday', 'Cookies'] as $tag)
                    <span class="bg-[#F2D9D9] text-[#700207] px-4 py-1.5 rounded-full shadow-sm hover:bg-[#e2bbbb] hover:-translate-y-1 transition-transform duration-300 cursor-pointer">
                        {{ $tag }}
                    </span>
                @endforeach
            </div>
        </div>

        {{-- Address --}}
        <div>
            <h4 class="text-2xl font-['Playfair_Display'] font-bold mb-4 text-[#700207]">Alamat Kami</h4>
            <p class="text-rose-700 text-sm leading-relaxed">Jl. Jambak Indah No 42 Rimbo Data, Kel. Bandar Buat, Kec. Lubuk Kilangan, Kota Padang</p>
            <p class="text-rose-700 text-sm mt-2">Telp: 081268879898</p>

            <div class="flex gap-4 mt-6">
                @foreach([
                    ['fab fa-instagram', 'https://www.instagram.com/daracake80?igsh=ZmRqc2kzY2M5dnFn'],
                    ['fab fa-facebook', '#'],
                    ['fab fa-twitter', '#'],
                ] as [$icon, $url])
                <a href="{{ $url }}" 
                   class="w-10 h-10 flex items-center justify-center bg-white text-[#700207] rounded-full hover:bg-[#700207] hover:text-white transition-all duration-300 hover:scale-110 shadow-sm">
                    <i class="{{ $icon }} text-lg"></i>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</footer>





@endsection
