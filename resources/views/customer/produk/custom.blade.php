@extends('layouts.navbar')
@section('title', 'Custom Cake | DaraCake')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#FFF9F9] to-[#FFEFEF] py-20 px-6 font-['Poppins']">

    {{-- JUDUL & DESKRIPSI --}}
    <div class="text-center mb-10">
        <h1 class="text-5xl font-extrabold text-[#700207] mb-4">Custom Birthday Cake</h1>
        <p class="text-gray-600 max-w-2xl mx-auto text-lg leading-relaxed mb-8">
            Wujudkan kue impianmu — pilih desain, rasa, dan tampilan sesuai keinginanmu.  
            <span class="text-[#700207] font-semibold">DaraCake</span> siap menghadirkan kue istimewa untuk momen spesialmu.
        </p>

        {{-- TOMBOL PESAN SEKARANG --}}
        <a href="#form-section"
           class="inline-block bg-[#700207] text-white font-semibold text-lg py-3 px-8 rounded-full shadow-md hover:bg-[#8a0910] transition duration-300">
           Pesan Sekarang 
        </a>
    </div>

    {{-- FITUR KUSTOM --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-20">
        @php
            $features = [
                [
                    'icon' => 'M12 4v2m0 0v2m0-2h2m-2 0H10m-3 7h10m-9 0v7a2 2 0 002 2h6a2 2 0 002-2v-7H7z',
                    'title' => 'Kirimkan Foto/Logo',
                    'desc' => 'Kirimkan foto atau logo melalui WhatsApp untuk kue dengan desain personal dan branding khusus.'
                ],
                [
                    'icon' => 'M20 12H4m16 0v6a2 2 0 01-2 2h-3v-8h5zm-16 0v6a2 2 0 002 2h3v-8H4z',
                    'title' => 'Sesuaikan Hadiah',
                    'desc' => 'Pilih ukuran, properti, dan rasa favoritmu. Ciptakan kue dengan gaya eksklusif.'
                ],
                [
                    'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V8H3v11a2 2 0 002 2z',
                    'title' => 'Rencanakan Kue Anda',
                    'desc' => 'Pesan minimal 5 hari sebelumnya agar kue siap tepat waktu dan sempurna.'
                ],
                [
                    'icon' => 'M9.75 17L8 21l4-2 4 2-1.75-4M12 3v10m0 0a4 4 0 100-8 4 4 0 000 8z',
                    'title' => 'Gratis Custom Desain',
                    'desc' => 'Di DaraCake, kustomisasi kue adalah GRATIS! Bebas berkreasi sesukamu.'
                ]
            ];
        @endphp

        @foreach($features as $f)
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-md p-6 text-center hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="flex justify-center mb-4 text-[#700207]">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $f['icon'] }}" />
                </svg>
            </div>
            <h3 class="font-semibold text-lg text-gray-900 mb-2">{{ $f['title'] }}</h3>
            <p class="text-sm text-gray-600 leading-relaxed">{{ $f['desc'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- PEMBATAS DENGAN "FORM" --}}
    <div class="flex items-center justify-center my-12">
        <div class="flex-grow border-t border-gray-300"></div>
        <span class="mx-4 text-gray-700 font-semibold tracking-wide">FORM</span>
        <div class="flex-grow border-t border-gray-300"></div>
    </div>

    {{-- FORM KUSTOM CAKE --}}
    <div id="form-section" class="max-w-4xl mx-auto bg-white p-10 rounded-3xl shadow-2xl border border-[#f5dada] hover:shadow-[#700207]/10 transition">
        <h2 class="text-2xl font-bold text-center text-[#700207] mb-8">Custom Cake</h2>

        <form method="POST" action="{{ route('custom-cake.store') }}" enctype="multipart/form-data" id="custom-cake-form" class="space-y-6">
    @csrf

    <div>
        <label class="block text-lg font-semibold text-gray-800 mb-2">Nama Produk</label>
        <input type="text" name="nama_produk" value="Birthday Cake" readonly
            class="w-full border-gray-300 rounded-lg bg-gray-100 px-4 py-2 focus:ring-[#700207] focus:border-[#700207]">
    </div>

    <div>
        <label class="block text-lg font-semibold text-gray-800 mb-2">1. Pilih Ukuran</label>
        <input type="text" name="ukuran" placeholder="Contoh: 20cm / 24cm / 28cm"
            class="w-full border-gray-300 rounded-lg px-4 py-2 focus:ring-[#700207] focus:border-[#700207]">
    </div>

    <div>
        <label class="block text-lg font-semibold text-gray-800 mb-2">2. Pilih Rasa</label>
        <input type="text" name="rasa" placeholder="Contoh: Vanilla / Coklat / Red Velvet"
            class="w-full border-gray-300 rounded-lg px-4 py-2 focus:ring-[#700207] focus:border-[#700207]">
    </div>

    <div>
        <label class="block text-lg font-semibold text-gray-800 mb-2">3. Pilih Topping</label>
        <input type="text" name="toppings" placeholder="Contoh: Cherry, Whipped Cream, Sprinkles"
            class="w-full border-gray-300 rounded-lg px-4 py-2 focus:ring-[#700207] focus:border-[#700207]">
    </div>

    <div>
        <label class="block text-lg font-semibold text-gray-800 mb-2">4. Tulisan di Atas Kue</label>
        <input type="text" name="tulisan" placeholder="Contoh: Selamat Ulang Tahun, Ayah!"
            class="w-full border-gray-300 rounded-lg px-4 py-2 focus:ring-[#700207] focus:border-[#700207]">
    </div>

    <button type="submit"
        class="w-full bg-[#700207] text-white font-semibold text-lg py-3 rounded-lg hover:bg-[#8a0910] transition duration-300">
        Kirim ke WhatsApp Admin
    </button>
</form>

    </div>
</div>

{{-- SCRIPT SCROLL & WHATSAPP --}}
<script>
// Smooth scroll ke form
document.querySelector('a[href="#form-section"]').addEventListener('click', function(e) {
    e.preventDefault();
    document.querySelector('#form-section').scrollIntoView({ behavior: 'smooth' });
});


</script>
@endsection
