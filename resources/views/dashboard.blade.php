@extends('layouts.navbar')

@section('title', 'Beranda')

@section('content')

{{-- Hero Section --}}
<section class=" py-20 lg:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center gap-12">

            <div class="w-full md:w-1/2 text-center md:text-left">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-gray-800 leading-tight">
                    SELAMAT DATANG DI 
                    <span class="bg-gradient-to-r from-pink-500 to-rose-500 text-transparent bg-clip-text">
                        DARA CAKE
                    </span>
                </h1>
                <p class="mt-6 text-lg text-gray-600">
                    Creating beautiful, delicious cakes for your special moments. From birthdays to weddings, we make every celebration sweeter with our handcrafted creations.
                </p>
                
                <div class="mt-10 flex flex-col sm:flex-row items-center justify-center md:justify-start gap-4">
                    <a href="#produk" 
                       class="bg-gradient-to-r from-pink-500 to-rose-500 text-white font-semibold px-8 py-3 rounded-full shadow-lg transform transition hover:scale-105 w-full sm:w-auto">
                        Beli Sekarang
                    </a>
                    <a href="#about" 
                       class="bg-transparent border-2 border-gray-700 text-gray-700 font-semibold px-8 py-3 rounded-full transition hover:bg-gray-700 hover:text-white w-full sm:w-auto">
                        Learn Now
                    </a>
                </div>
            </div>

            <div class="w-full md:w-1/2 mt-10 md:mt-0">
                <img src="{{ asset('gambar/imgkue.png') }}"
                     alt="Beautifully decorated cake" 
                     class="w-full h-auto rounded-lg shadow-2xl object-cover transform transition hover:rotate-1">
            </div>

        </div>
    </div>
</section>

{{-- Produk Unggulan --}}
<section id="produk" 
         class="py-16" 
         style="background-image: url('{{ asset('gambar/Hero.jpg') }}'); background-size: cover; background-position: center;">
    
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-black">Produk Unggulan</h2>
            <p class="text-gray-800">Kue pilihan terbaik dari Daracake</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            
            {{-- ✔️ Ganti perulangan statis dengan ini --}}
            @foreach ($products as $product)
            <div class="bg-gradient-to-br from-pink-50 to-rose-100 rounded-2xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105">
                
                {{-- Tampilkan gambar dari database --}}
                {{-- Pastikan Anda sudah menjalankan "php artisan storage:link" --}}
                <img src="{{ asset('storage/produk/' . $product->gambar) }}" class="w-full h-56 object-cover" alt="{{ $product->nama_produk }}">
                
                <div class="p-5 text-center">
                    {{-- Tampilkan nama produk dari database --}}
                    <h3 class="font-semibold text-lg text-gray-800">{{ $product->nama_produk }}</h3>
                    
                    {{-- Tampilkan harga dari database (dengan format Rupiah) --}}
                    <p class="text-gray-600">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                    
                    <a href="#" class="mt-4 bg-yellow-400 hover:bg-yellow-500 px-4 py-2 rounded-full font-medium">
                        Beli Sekarang
                    </a>
                </div>
            </div>
            @endforeach

        </div>

    </div>
</section>

{{-- Testimoni --}}
<section class="py-16 bg-white">
    <div class="text-center mb-10">
        <h2 class="text-3xl font-bold">Apa Kata Mereka?</h2>
        <p class="text-gray-600">Testimoni pelanggan setia Daracake</p>
    </div>
    <div class="flex justify-center space-x-6 max-w-4xl mx-auto">
        <div class="bg-gray-100 p-6 rounded-xl shadow-md w-1/3">
            <p>"Rasanya enak banget, apalagi chocolate cake-nya 🤤"</p>
            <h4 class="mt-4 font-semibold">– Sinta</h4>
        </div>
        <div class="bg-gray-100 p-6 rounded-xl shadow-md w-1/3">
            <p>"Pengiriman cepat, packaging aman. Recommended!"</p>
            <h4 class="mt-4 font-semibold">– Andi</h4>
        </div>
        <div class="bg-gray-100 p-6 rounded-xl shadow-md w-1/3">
            <p>"Kuenya lembut, manisnya pas. Anak-anak suka 👍"</p>
            <h4 class="mt-4 font-semibold">– Rina</h4>
        </div>
    </div>
</section>

{{-- Tentang Kami --}}
<section class="py-16 bg-gray-50">
    <div class="max-w-5xl mx-auto text-center px-6">
        <h2 class="text-3xl font-bold">Tentang Daracake</h2>
        <p class="mt-4 text-gray-600 leading-relaxed">
            Daracake berdiri sejak 2020, menghadirkan berbagai jenis kue premium yang dibuat dengan bahan berkualitas 
            dan penuh cinta. Misi kami adalah membuat setiap momen Anda lebih manis dengan kue terbaik. 🎂
        </p>
    </div>
</section>


@endsection
