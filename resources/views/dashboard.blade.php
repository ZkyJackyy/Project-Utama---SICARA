@extends('layouts.navbar')

@section('title', 'Beranda')

@section('content')

{{-- 🎀 Hero Section --}}
<section class="relative bg-gradient-to-b from-pink-100 via-pink-50 to-white pt-24 pb-32 overflow-hidden">
    <div class="max-w-6xl mx-auto px-6 text-center">
        <img src="{{ asset('gambar/little.png') }}" alt="Cupcake" 
             class="mx-auto mb-8 w-64 md:w-80 drop-shadow-2xl animate-fadeInUp">

        <h1 class="text-5xl md:text-6xl font-extrabold text-pink-700 mb-4 leading-tight">
            We Implement Your <span class="text-pink-500">Delicious Dreams</span> ✨
        </h1>
        <p class="text-gray-600 text-lg md:text-xl max-w-2xl mx-auto mb-8">
            Kami hadir untuk mewujudkan impian manismu — cupcake lembut, desain cantik, dan rasa yang tak terlupakan.
        </p>

        <div class="flex justify-center gap-4 mt-10">
            <a href="#"
               class="bg-pink-600 hover:bg-pink-700 text-white font-semibold px-8 py-3 rounded-full shadow-lg transition transform hover:-translate-y-1 hover:scale-105 duration-300">
                🍰 Pesan Sekarang
            </a>
            <a href="#"
               class="border-2 border-pink-600 text-pink-600 hover:bg-pink-600 hover:text-white font-semibold px-8 py-3 rounded-full transition transform hover:-translate-y-1 hover:scale-105 duration-300">
                📋 Lihat Menu
            </a>
        </div>
    </div>

    {{-- Background Pattern --}}
    <div class="absolute bottom-0 left-0 right-0 h-16 bg-pink-200 [clip-path:polygon(0_100%,100%_0,100%_100%)]"></div>
</section>

{{-- 🍓 Fitur Section --}}
<section class="py-24 bg-white text-center">
    <h2 class="text-4xl font-extrabold text-pink-700 mb-3 tracking-tight">MADE FOR YOU</h2>
    <p class="text-gray-500 mb-14 font-medium italic">{ With Love }</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-10 max-w-6xl mx-auto px-6">
        {{-- Fitur 1 --}}
        <div class="bg-gradient-to-b from-pink-50 to-white rounded-2xl p-10 shadow-md hover:shadow-xl hover:-translate-y-2 transition duration-300">
            <div class="text-pink-500 mb-6 text-6xl">🚴‍♀️</div>
            <h3 class="text-2xl font-semibold text-pink-700 mb-2">Delivery Cepat</h3>
            <p class="text-gray-600">Pesananmu sampai hanya dalam 30 menit!</p>
            <a href="#" class="inline-block mt-4 text-pink-500 font-medium hover:underline">Read More →</a>
        </div>

        {{-- Fitur 2 --}}
        <div class="bg-gradient-to-b from-pink-100 to-white rounded-2xl p-10 shadow-md hover:shadow-xl hover:-translate-y-2 transition duration-300">
            <div class="text-pink-500 mb-6 text-6xl">🎁</div>
            <h3 class="text-2xl font-semibold text-pink-700 mb-2">Gratis Kemasan</h3>
            <p class="text-gray-600">Kado manis siap dikirim tanpa biaya tambahan!</p>
            <a href="#" class="inline-block mt-4 text-pink-500 font-medium hover:underline">Read More →</a>
        </div>

        {{-- Fitur 3 --}}
        <div class="bg-gradient-to-b from-pink-50 to-white rounded-2xl p-10 shadow-md hover:shadow-xl hover:-translate-y-2 transition duration-300">
            <div class="text-pink-500 mb-6 text-6xl">🧁</div>
            <h3 class="text-2xl font-semibold text-pink-700 mb-2">Diskon 15%</h3>
            <p class="text-gray-600">Promo spesial pembukaan toko kami!</p>
            <a href="#" class="inline-block mt-4 text-pink-500 font-medium hover:underline">Read More →</a>
        </div>
    </div>
</section>

{{-- 👩‍🍳 Tim Kami --}}
<section class="py-24 bg-gradient-to-b from-pink-50 via-pink-100 to-pink-200">
    <div class="text-center mb-14">
        <h2 class="text-4xl font-extrabold text-pink-800 tracking-tight">{ Our Teams }</h2>
    </div>

    <div class="flex flex-col md:flex-row justify-center gap-12 max-w-6xl mx-auto px-6">
        @foreach([
            ['team1.jpg', 'Kimberly Thompson', 'Founder & CEO'],
            ['team2.jpg', 'Jame Adams', 'Pastry Designer'],
            ['team3.jpg', 'Blaz Robar', 'Baking Master'],
        ] as [$img, $name, $role])
            <div class="group text-center">
                <div class="relative mx-auto w-40 h-40 rounded-full overflow-hidden border-4 border-white shadow-xl transition transform group-hover:scale-110 group-hover:shadow-2xl">
                    <img src="{{ asset('gambar/' . $img) }}" alt="{{ $name }}" class="object-cover w-full h-full">
                </div>
                <h3 class="mt-5 text-lg font-bold text-pink-800">{{ $name }}</h3>
                <p class="text-pink-600 text-sm">{{ $role }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- 📬 Footer --}}
<footer class="bg-gradient-to-b from-pink-800 to-pink-900 text-white py-16">
    <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-10">
        {{-- Newsletter --}}
        <div>
            <h4 class="text-xl font-semibold mb-3">Newsletter</h4>
            <p class="text-pink-200 text-sm mb-4">Dapatkan info promo terbaru kami!</p>
            <form class="flex">
                <input type="email" placeholder="Email kamu..." class="flex-1 px-4 py-2 rounded-l-md text-gray-800 focus:outline-none">
                <button class="bg-pink-600 px-4 py-2 rounded-r-md hover:bg-pink-500 transition">GO</button>
            </form>
        </div>

        {{-- Latest News --}}
        <div>
            <h4 class="text-xl font-semibold mb-3">Latest News</h4>
            <ul class="text-pink-100 text-sm space-y-2">
                <li>🎂 Peluncuran Menu Baru</li>
                <li>🍪 Diskon Spesial Minggu Ini</li>
            </ul>
        </div>

        {{-- Tags --}}
        <div>
            <h4 class="text-xl font-semibold mb-3">Tags</h4>
            <div class="flex flex-wrap gap-2 text-sm">
                <span class="bg-pink-600 px-3 py-1 rounded-full">Cupcake</span>
                <span class="bg-pink-600 px-3 py-1 rounded-full">Dessert</span>
                <span class="bg-pink-600 px-3 py-1 rounded-full">Sweet</span>
            </div>
        </div>

        {{-- Address --}}
        <div>
            <h4 class="text-xl font-semibold mb-3">Address</h4>
            <p class="text-pink-100 text-sm">Jl. Manis No. 45, Jakarta Selatan</p>
            <p class="text-pink-100 text-sm mt-2">Telp: (021) 555-7788</p>
            <div class="flex gap-3 mt-4 text-xl">
                <a href="#" class="hover:text-pink-400 transition">🌐</a>
                <a href="#" class="hover:text-pink-400 transition">📘</a>
                <a href="#" class="hover:text-pink-400 transition">📷</a>
            </div>
        </div>
    </div>

    <div class="text-center mt-10 text-sm text-pink-300 border-t border-pink-700 pt-6">
        © 2025 <span class="font-semibold">Daracake</span>. All Rights Reserved.
    </div>
</footer>

@endsection
