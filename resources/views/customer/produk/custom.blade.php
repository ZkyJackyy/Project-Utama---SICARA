@extends('layouts.navbar')
@section('title', 'Custom Cake | DaraCake')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans pb-20">

    {{-- HERO HEADER --}}
    <div class="relative bg-gradient-to-r from-[#700207] to-[#8B0309] text-white py-20 px-6 text-center overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/food.png')]"></div>
        <div class="relative z-10 max-w-2xl mx-auto">
            <h1 class="text-3xl md:text-5xl font-bold mb-4 font-serif tracking-tight">Design Your Dream Cake</h1>
            <p class="text-red-100 text-lg font-light leading-relaxed">Kreasikan kue impianmu dengan sentuhan personal. Pilih rasa, ukuran, dan dekorasi sesuka hati.</p>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative z-20">
        
        <form method="POST" action="{{ route('keranjang.tambahCustom') }}" enctype="multipart/form-data" id="custom-form">
            @csrf
            
            <div class="flex flex-col lg:flex-row gap-8 items-start">

                {{-- KOLOM KIRI: FORM KUSTOMISASI --}}
                <div class="flex-1 w-full space-y-8">
                    
                    {{-- 1. PILIH UKURAN --}}
                    <section class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 transition hover:shadow-md">
                        <div class="flex items-center gap-3 mb-6 border-b border-gray-50 pb-4">
                            <span class="bg-red-50 text-[#700207] w-8 h-8 flex items-center justify-center rounded-full text-sm font-bold">1</span>
                            <h3 class="text-lg font-bold text-gray-800">Pilih Ukuran Kue</h3>
                        </div>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            <label class="cursor-pointer relative group">
                                <input type="radio" name="ukuran" value="18cm" data-price="65000" class="peer sr-only" checked onchange="calculateTotal()">
                                <div class="p-5 rounded-xl border border-gray-200 bg-gray-50 peer-checked:bg-white peer-checked:border-[#700207] peer-checked:ring-1 peer-checked:ring-[#700207] hover:border-red-200 transition-all text-center h-full flex flex-col justify-center items-center">
                                    <span class="text-3xl mb-2 group-hover:scale-110 transition-transform">🎂</span>
                                    <span class="font-bold text-gray-800 block">18cm</span>
                                    <span class="text-xs text-gray-500 mt-1 block">Mulai Rp 65rb</span>
                                </div>
                                <div class="absolute top-2 right-2 text-[#700207] opacity-0 peer-checked:opacity-100 transition-opacity transform scale-0 peer-checked:scale-100">
                                    <i class="fa fa-check-circle"></i>
                                </div>
                            </label>

                            <label class="cursor-pointer relative group">
                                <input type="radio" name="ukuran" value="20cm" data-price="80000" class="peer sr-only" onchange="calculateTotal()">
                                <div class="p-5 rounded-xl border border-gray-200 bg-gray-50 peer-checked:bg-white peer-checked:border-[#700207] peer-checked:ring-1 peer-checked:ring-[#700207] hover:border-red-200 transition-all text-center h-full flex flex-col justify-center items-center">
                                    <span class="text-3xl mb-2 group-hover:scale-110 transition-transform">🍰</span>
                                    <span class="font-bold text-gray-800 block">20cm</span>
                                    <span class="text-xs text-gray-500 mt-1 block">Mulai Rp 80rb</span>
                                </div>
                                <div class="absolute top-2 right-2 text-[#700207] opacity-0 peer-checked:opacity-100 transition-opacity transform scale-0 peer-checked:scale-100">
                                    <i class="fa fa-check-circle"></i>
                                </div>
                            </label>
                        </div>
                    </section>

                    {{-- 2. PILIH RASA --}}
                    <section class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 transition hover:shadow-md">
                        <div class="flex items-center gap-3 mb-6 border-b border-gray-50 pb-4">
                            <span class="bg-red-50 text-[#700207] w-8 h-8 flex items-center justify-center rounded-full text-sm font-bold">2</span>
                            <h3 class="text-lg font-bold text-gray-800">Pilih Base Cake</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @php
                                $flavors = [
                                    ['name' => 'Vanilla Cloud', 'price' => 0, 'desc' => 'Lembut & klasik', 'icon' => '☁️'],
                                    ['name' => 'Double Chocolate', 'price' => 0, 'desc' => 'Manis & rich', 'icon' => '🍫'],
                                    ['name' => 'Red Velvet', 'price' => 0, 'desc' => 'Elegan & lembut', 'icon' => '🍰'],
                                    ['name' => 'Mocha Nougat', 'price' => 0, 'desc' => 'Creamy & crunchy', 'icon' => '☕'],
                                ];
                            @endphp
                            @foreach($flavors as $index => $flavor)
                            <label class="cursor-pointer relative">
                                <input type="radio" name="rasa" value="{{ $flavor['name'] }}" data-price="{{ $flavor['price'] }}" class="peer sr-only" {{ $index == 0 ? 'checked' : '' }} onchange="calculateTotal()">
                                <div class="flex items-center p-4 border border-gray-200 rounded-xl hover:border-red-200 peer-checked:border-[#700207] peer-checked:bg-red-50/30 transition-all">
                                    <span class="text-2xl mr-4">{{ $flavor['icon'] }}</span>
                                    <div>
                                        <p class="font-semibold text-gray-800 text-sm">{{ $flavor['name'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $flavor['desc'] }}</p>
                                    </div>
                                    <div class="ml-auto w-4 h-4 rounded-full border border-gray-300 peer-checked:border-[#700207] peer-checked:bg-[#700207] flex items-center justify-center transition-colors">
                                        <div class="w-1.5 h-1.5 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </section>

                    {{-- 3. TOPPING --}}
                    <section class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 transition hover:shadow-md">
                        <div class="flex items-center gap-3 mb-6 border-b border-gray-50 pb-4">
                            <span class="bg-red-50 text-[#700207] w-8 h-8 flex items-center justify-center rounded-full text-sm font-bold">3</span>
                            <h3 class="text-lg font-bold text-gray-800">Tambah Topping (Opsional)</h3>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            @php
                                $toppings = [
                                    ['name' => 'Fresh Fruits', 'price' => 5000],
                                    ['name' => 'Macarons (3pcs)', 'price' => 5000],
                                    ['name' => 'Gold Flakes', 'price' => 5000],
                                    ['name' => 'Choco Drip', 'price' => 10000],
                                ];
                            @endphp
                            @foreach($toppings as $top)
                            <label class="cursor-pointer select-none">
                                <input type="checkbox" name="toppings[]" value="{{ $top['name'] }}" data-price="{{ $top['price'] }}" class="peer sr-only" onchange="calculateTotal()">
                                <div class="p-3 border border-gray-200 rounded-lg text-center hover:bg-gray-50 peer-checked:border-[#700207] peer-checked:bg-[#700207] peer-checked:text-white transition-all duration-200">
                                    <span class="text-sm font-medium block">{{ $top['name'] }}</span>
                                    <span class="text-xs opacity-70 block">+{{ number_format($top['price']/1000) }}k</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </section>

                    {{-- 4. DETAIL PESAN --}}
                    <section class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 transition hover:shadow-md">
                        <div class="flex items-center gap-3 mb-6 border-b border-gray-50 pb-4">
                            <span class="bg-red-50 text-[#700207] w-8 h-8 flex items-center justify-center rounded-full text-sm font-bold">4</span>
                            <h3 class="text-lg font-bold text-gray-800">Detail Tulisan</h3>
                        </div>
                        
                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tulisan di atas kue</label>
                                <input type="text" name="tulisan" placeholder="Contoh: Happy Birthday Sayang..." maxlength="30"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-red-100 focus:border-[#700207] outline-none transition placeholder-gray-400">
                                <p class="text-xs text-gray-400 mt-1 text-right">Maks 30 karakter</p>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Catatan Tambahan</label>
                                <textarea name="catatan_tambahan" rows="3" placeholder="Contoh: Krim warna pink pastel, lilin angka 2, dikirim jam 2 siang..."
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-red-100 focus:border-[#700207] outline-none transition placeholder-gray-400"></textarea>
                            </div>
                        </div>
                    </section>

                </div>

                {{-- KOLOM KANAN: SUMMARY (STICKY) --}}
                <div class="w-full lg:w-[380px] shrink-0">
                    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 sticky top-24">
                        <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                            <i class="fa fa-receipt text-gray-400"></i> Ringkasan Pesanan
                        </h3>
                        
                        <div class="space-y-4 text-sm mb-8">
                            <div class="flex justify-between items-center text-gray-600">
                                <span>Base Price (Ukuran)</span>
                                <span class="font-medium text-gray-900 bg-gray-100 px-2 py-1 rounded" id="summary-size">Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center text-gray-600">
                                <span>Rasa Tambahan</span>
                                <span class="font-medium text-gray-900 bg-gray-100 px-2 py-1 rounded" id="summary-flavor">Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center text-gray-600">
                                <span>Topping</span>
                                <span class="font-medium text-gray-900 bg-gray-100 px-2 py-1 rounded" id="summary-topping">Rp 0</span>
                            </div>
                        </div>

                        <div class="border-t border-dashed border-gray-200 pt-6 mb-6">
                            <div class="flex justify-between items-end">
                                <span class="text-sm font-semibold text-gray-500 mb-1">Total Estimasi</span>
                                <span class="text-3xl font-bold text-[#700207]" id="display-total">Rp 0</span>
                            </div>
                        </div>

                        {{-- HIDDEN INPUT UNTUK HARGA FINAL --}}
                        <input type="hidden" name="final_price" id="final-price-input" value="0">

                        <button type="submit" class="w-full bg-[#700207] text-white font-bold py-4 rounded-xl hover:bg-[#5a0105] shadow-lg hover:shadow-red-900/20 transform hover:-translate-y-0.5 active:translate-y-0 active:shadow-md transition-all duration-200 flex items-center justify-center gap-2 group">
                            <span>Pesan Sekarang</span>
                            <i class="fa fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </button>
                        
                        <p class="text-[10px] text-center text-gray-400 mt-4 leading-tight">
                            *Harga yang tertera adalah harga final. Silakan lanjut checkout untuk pembayaran.
                        </p>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- JAVASCRIPT CALCULATOR --}}
<script>
    function formatRupiah(angka) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
    }

    function calculateTotal() {
        let total = 0;
        let sizePrice = 0;
        let flavorPrice = 0;
        let toppingPrice = 0;

        // 1. Get Size Price
        const selectedSize = document.querySelector('input[name="ukuran"]:checked');
        if (selectedSize) {
            sizePrice = parseInt(selectedSize.dataset.price);
        }

        // 2. Get Flavor Price
        const selectedFlavor = document.querySelector('input[name="rasa"]:checked');
        if (selectedFlavor) {
            flavorPrice = parseInt(selectedFlavor.dataset.price);
        }

        // 3. Get Topping Price
        const selectedToppings = document.querySelectorAll('input[name="toppings[]"]:checked');
        selectedToppings.forEach(top => {
            toppingPrice += parseInt(top.dataset.price);
        });

        // Calculate
        total = sizePrice + flavorPrice + toppingPrice;

        // Update UI
        document.getElementById('summary-size').innerText = formatRupiah(sizePrice);
        document.getElementById('summary-flavor').innerText = formatRupiah(flavorPrice);
        document.getElementById('summary-topping').innerText = formatRupiah(toppingPrice);
        document.getElementById('display-total').innerText = formatRupiah(total);

        // Update Input Hidden
        document.getElementById('final-price-input').value = total;
    }

    // Run on load
    document.addEventListener('DOMContentLoaded', calculateTotal);
</script>
@endsection