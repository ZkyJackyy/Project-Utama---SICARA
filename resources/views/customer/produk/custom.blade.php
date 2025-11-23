@extends('layouts.navbar')
@section('title', 'Custom Cake | DaraCake')

@section('content')
<div class="min-h-screen bg-[#FAFAFA] font-['Poppins'] pb-20">

    {{-- HERO HEADER --}}
    <div class="relative bg-[#700207] text-white py-16 px-6 text-center overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/food.png')]"></div>
        <div class="relative z-10 max-w-3xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4 font-['Playfair_Display']">Design Your Dream Cake</h1>
            <p class="text-red-100 text-lg">Kreasikan kue ulang tahun impianmu. Pilih rasa, ukuran, dan dekorasi sesuka hati!</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20">
        
        <form method="POST" action="{{ route('keranjang.tambahCustom') }}" enctype="multipart/form-data" id="custom-form">
            @csrf
            
            <div class="flex flex-col lg:flex-row gap-8">

                {{-- KOLOM KIRI: FORM KUSTOMISASI --}}
                <div class="flex-1 space-y-6">
                    
                    {{-- 1. PILIH UKURAN (Card Selection) --}}
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <span class="bg-red-100 text-[#700207] w-8 h-8 flex items-center justify-center rounded-full text-sm">1</span>
                            Pilih Ukuran Kue
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            {{-- Option 1 --}}
                            <label class="cursor-pointer relative">
                                <input type="radio" name="ukuran" value="16cm (Mini)" data-price="150000" class="peer sr-only" checked onchange="calculateTotal()">
                                <div class="p-4 rounded-xl border-2 border-gray-200 hover:border-red-300 peer-checked:border-[#700207] peer-checked:bg-red-50 transition-all text-center">
                                    <div class="text-3xl mb-2">🎂</div>
                                    <div class="font-bold text-gray-800">16cm (Mini)</div>
                                    <div class="text-sm text-gray-500">Start Rp 150rb</div>
                                </div>
                                <div class="absolute top-3 right-3 text-[#700207] opacity-0 peer-checked:opacity-100 transition-opacity">
                                    <i class="fa fa-check-circle"></i>
                                </div>
                            </label>

                            {{-- Option 2 --}}
                            <label class="cursor-pointer relative">
                                <input type="radio" name="ukuran" value="20cm (Medium)" data-price="220000" class="peer sr-only" onchange="calculateTotal()">
                                <div class="p-4 rounded-xl border-2 border-gray-200 hover:border-red-300 peer-checked:border-[#700207] peer-checked:bg-red-50 transition-all text-center">
                                    <div class="text-3xl mb-2">🍰</div>
                                    <div class="font-bold text-gray-800">20cm (Medium)</div>
                                    <div class="text-sm text-gray-500">Start Rp 220rb</div>
                                </div>
                                <div class="absolute top-3 right-3 text-[#700207] opacity-0 peer-checked:opacity-100 transition-opacity">
                                    <i class="fa fa-check-circle"></i>
                                </div>
                            </label>

                            {{-- Option 3 --}}
                            <label class="cursor-pointer relative">
                                <input type="radio" name="ukuran" value="24cm (Large)" data-price="300000" class="peer sr-only" onchange="calculateTotal()">
                                <div class="p-4 rounded-xl border-2 border-gray-200 hover:border-red-300 peer-checked:border-[#700207] peer-checked:bg-red-50 transition-all text-center">
                                    <div class="text-3xl mb-2">🏰</div>
                                    <div class="font-bold text-gray-800">24cm (Large)</div>
                                    <div class="text-sm text-gray-500">Start Rp 300rb</div>
                                </div>
                                <div class="absolute top-3 right-3 text-[#700207] opacity-0 peer-checked:opacity-100 transition-opacity">
                                    <i class="fa fa-check-circle"></i>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- 2. PILIH RASA (List Selection) --}}
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <span class="bg-red-100 text-[#700207] w-8 h-8 flex items-center justify-center rounded-full text-sm">2</span>
                            Pilih Base Cake
                        </h3>
                        <div class="space-y-3">
                            @php
                                $flavors = [
                                    ['name' => 'Vanilla Cloud', 'price' => 0, 'desc' => 'Lembut & klasik'],
                                    ['name' => 'Double Chocolate', 'price' => 25000, 'desc' => '+ Rp 25.000'],
                                    ['name' => 'Red Velvet', 'price' => 35000, 'desc' => '+ Rp 35.000'],
                                    ['name' => 'Mocha Nougat', 'price' => 30000, 'desc' => '+ Rp 30.000'],
                                ];
                            @endphp
                            @foreach($flavors as $index => $flavor)
                            <label class="flex items-center justify-between p-4 border rounded-xl cursor-pointer hover:bg-gray-50 transition">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="rasa" value="{{ $flavor['name'] }}" data-price="{{ $flavor['price'] }}" class="text-[#700207] focus:ring-[#700207] w-5 h-5" {{ $index == 0 ? 'checked' : '' }} onchange="calculateTotal()">
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $flavor['name'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $flavor['desc'] }}</p>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- 3. TOPPING (Checkbox) --}}
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <span class="bg-red-100 text-[#700207] w-8 h-8 flex items-center justify-center rounded-full text-sm">3</span>
                            Tambah Topping (Opsional)
                        </h3>
                        <div class="grid grid-cols-2 gap-3">
                            @php
                                $toppings = [
                                    ['name' => 'Fresh Fruits', 'price' => 20000],
                                    ['name' => 'Macarons (3pcs)', 'price' => 35000],
                                    ['name' => 'Gold Flakes', 'price' => 15000],
                                    ['name' => 'Choco Drip', 'price' => 10000],
                                ];
                            @endphp
                            @foreach($toppings as $top)
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer has-[:checked]:bg-red-50 has-[:checked]:border-[#700207] transition">
                                <input type="checkbox" name="toppings[]" value="{{ $top['name'] }}" data-price="{{ $top['price'] }}" class="rounded text-[#700207] focus:ring-[#700207] w-5 h-5 mr-3" onchange="calculateTotal()">
                                <span class="text-sm font-medium text-gray-700">{{ $top['name'] }} (+{{ number_format($top['price']/1000) }}k)</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- 4. DETAIL PESAN --}}
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <span class="bg-red-100 text-[#700207] w-8 h-8 flex items-center justify-center rounded-full text-sm">4</span>
                            Detail Tulisan
                        </h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Tulisan di atas kue (Maks 30 karakter)</label>
                            <input type="text" name="tulisan" placeholder="Happy Birthday Sayang..." maxlength="30"
                                class="w-full border-gray-300 rounded-lg px-4 py-3 focus:ring-[#700207] focus:border-[#700207] transition">
                        </div>
                        
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-600 mb-2">Catatan Tambahan (Warna krim, jam ambil, dll)</label>
                            <textarea name="catatan_tambahan" rows="2" placeholder="Contoh: Krim warna pink pastel, lilin angka 2..."
                                class="w-full border-gray-300 rounded-lg px-4 py-3 focus:ring-[#700207] focus:border-[#700207] transition"></textarea>
                        </div>
                    </div>

                </div>

                {{-- KOLOM KANAN: SUMMARY (STICKY) --}}
                <div class="lg:w-1/3">
                    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 sticky top-24">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 border-b pb-4">Ringkasan Pesanan</h3>
                        
                        <div class="space-y-3 text-sm text-gray-600 mb-6">
                            <div class="flex justify-between">
                                <span>Base Price (Ukuran)</span>
                                <span class="font-medium text-gray-900" id="summary-size">Rp 0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Rasa Tambahan</span>
                                <span class="font-medium text-gray-900" id="summary-flavor">Rp 0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Topping</span>
                                <span class="font-medium text-gray-900" id="summary-topping">Rp 0</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center border-t pt-4 mb-6">
                            <span class="text-lg font-bold text-gray-900">Total Estimasi</span>
                            <span class="text-2xl font-bold text-[#700207]" id="display-total">Rp 0</span>
                        </div>

                        {{-- HIDDEN INPUT UNTUK HARGA FINAL --}}
                        <input type="hidden" name="final_price" id="final-price-input" value="0">

                        <button type="submit" class="w-full bg-[#700207] text-white font-bold py-4 rounded-xl hover:bg-[#8a0910] shadow-lg hover:shadow-xl transform active:scale-95 transition duration-200 flex items-center justify-center gap-2">
                            <i class="fa fa-cart-plus"></i> Masukkan Keranjang
                        </button>
                        
                        <p class="text-xs text-center text-gray-400 mt-3">
                            *Harga final, silakan lanjut checkout untuk pembayaran.
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