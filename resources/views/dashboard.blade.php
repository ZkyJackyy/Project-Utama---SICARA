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
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-12">

            @forelse($products ?? [] as $produk)
            <a href="{{ route('customer.produk.detail', $produk->id) }}"
               class="block bg-white rounded-3xl shadow-lg hover:shadow-2xl hover:-translate-y-1 
                      transition-all duration-300 p-6 group">

                {{-- Gambar --}}
                <div class="overflow-hidden rounded-2xl mb-6">
                    <img src="{{ asset('storage/produk/' . $produk->gambar) }}" 
                        alt="{{ $produk->nama_produk }}"
                        class="w-full h-60 object-cover rounded-2xl transform group-hover:scale-110 duration-500">
                </div>

                {{-- Nama --}}
                <h3 class="text-2xl font-['Playfair_Display'] font-semibold text-[#700207] mb-2 group-hover:text-[#b1354a] transition">
                    {{ $produk->nama_produk }}
                </h3>

                {{-- Deskripsi --}}
                <p class="text-gray-600 text-sm mb-6 leading-relaxed">
                    {{ Str::limit($produk->deskripsi, 70) }}
                </p>

                <p class="text-gray-600 text-sm mb-6 leading-relaxed">
                    {{ Str::limit($produk->stok) }}
                </p>

                {{-- Harga + Button --}}
                <div class="flex items-center justify-between pt-2">
                    <p class="text-[#700207] font-bold text-xl">
                        Rp {{ number_format($produk->harga, 0, ',', '.') }}
                    </p>

                    <span class="bg-[#700207] hover:bg-[#4a0105] text-white px-5 py-2 rounded-full text-sm font-medium transition">
                        Lihat Detail
                    </span>
                </div>

            </a>
            @empty
            <p class="col-span-3 text-gray-500 text-lg py-10">Belum ada produk tersedia.</p>
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

            <a href="#produk" 
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
            <p class="text-rose-700 text-sm leading-relaxed">Jl. Manis No. 45, Jakarta Selatan</p>
            <p class="text-rose-700 text-sm mt-2">Telp: (021) 555-7788</p>

            <div class="flex gap-4 mt-6">
                @foreach([
                    ['fab fa-instagram', '#'],
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
