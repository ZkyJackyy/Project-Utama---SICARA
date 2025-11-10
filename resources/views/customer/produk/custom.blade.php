@extends('layouts.navbar')
@section('title', 'Buat Kue Kustom Anda')

@section('content')
<div classT="bg-gray-50 py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Ambil harga dasar dari controller --}}
        @php
            $basePrice = $product->harga;
        @endphp

        <h1 class="text-3xl font-bold text-center text-pink-600 mb-8">Kustomisasi Kue Anda</h1>
        
        <form action="{{ route('keranjang.tambahCustom') }}" method="POST" id="custom-cake-form">
            @csrf
            
            <div class="bg-white p-6 md:p-8 rounded-lg shadow-lg md:grid md:grid-cols-2 md:gap-12">
                
                {{-- Kolom Kiri: Gambar --}}
                <div>
                    <img src="{{ asset('storage/produk/' . $product->gambar) }}" alt="Kue Kustom" class="w-full h-auto rounded-lg shadow-md object-cover aspect-square">
                    
                    {{-- Tampilan Harga Total (diperbarui oleh JS) --}}
                    <div class="mt-8 text-center">
                        <span class="text-gray-600 text-lg">Total Harga:</span>
                        <h2 id="display-price" class="text-4xl font-bold text-pink-600">
                            Rp {{ number_format($basePrice, 0, ',', '.') }}
                        </h2>
                    </div>
                </div>

                {{-- Kolom Kanan: Opsi --}}
                <div class="mt-6 md:mt-0">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $product->nama_produk }}</h2>
                    <p class="text-gray-600 mt-2">{{ $product->deskripsi }}</p>

                    <div class="border-t border-gray-200 my-6"></div>

                    {{-- Opsi 1: Ukuran (Radio Button) --}}
                    <div class="mb-6">
                        <label class="block text-lg font-semibold text-gray-800 mb-3">1. Pilih Ukuran</label>
                        <div class="space-y-2" id="options-size">
                            <label class="flex items-center p-3 border rounded-lg has-[:checked]:bg-pink-50 has-[:checked]:border-pink-500 transition">
                                <input type="radio" name="ukuran" value="20cm" data-price="0" class="form-radio text-pink-600" checked>
                                <span class="ml-3 text-gray-700">20cm (Harga Dasar)</span>
                            </label>
                            <label class="flex items-center p-3 border rounded-lg has-[:checked]:bg-pink-50 has-[:checked]:border-pink-500 transition">
                                <input type="radio" name="ukuran" value="24cm" data-price="50000" class="form-radio text-pink-600">
                                <span class="ml-3 text-gray-700">24cm (+ Rp 50.000)</span>
                            </label>
                            <label class="flex items-center p-3 border rounded-lg has-[:checked]:bg-pink-50 has-[:checked]:border-pink-500 transition">
                                <input type="radio" name="ukuran" value="28cm" data-price="100000" class="form-radio text-pink-600">
                                <span class="ml-3 text-gray-700">28cm (+ Rp 100.000)</span>
                            </label>
                        </div>
                    </div>

                    {{-- Opsi 2: Rasa (Dropdown) --}}
                    <div class="mb-6">
                        <label for="rasa" class="block text-lg font-semibold text-gray-800 mb-3">2. Pilih Rasa</label>
                        <select name="rasa" id="options-flavor" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500">
                            <option value="Vanilla" data-price="0">Vanilla (Gratis)</option>
                            <option value="Chocolate" data-price="20000">Chocolate (+ Rp 20.000)</option>
                            <option value="Red Velvet" data-price="30000">Red Velvet (+ Rp 30.000)</option>
                        </select>
                    </div>

                    {{-- Opsi 3: Topping (Checkbox) --}}
                    <div class="mb-6">
                        <label class="block text-lg font-semibold text-gray-800 mb-3">3. Pilih Topping (Bisa lebih dari 1)</label>
                        <div class="space-y-2" id="options-toppings">
                            <label class="flex items-center p-3 border rounded-lg has-[:checked]:bg-pink-50 has-[:checked]:border-pink-500 transition">
                                <input type="checkbox" name="toppings[]" value="Sprinkles" data-price="5000" class="form-checkbox text-pink-600 rounded">
                                <span class="ml-3 text-gray-700">Sprinkles (+ Rp 5.000)</span>
                            </label>
                            <label class="flex items-center p-3 border rounded-lg has-[:checked]:bg-pink-50 has-[:checked]:border-pink-500 transition">
                                <input type="checkbox" name="toppings[]" value="Cherry" data-price="10000" class="form-checkbox text-pink-600 rounded">
                                <span class="ml-3 text-gray-700">Cherry (+ Rp 10.000)</span>
                            </label>
                        </div>
                    </div>
                    
                    {{-- Opsi 4: Tulisan --}}
                    <div class="mb-6">
                        <label for="tulisan" class="block text-lg font-semibold text-gray-800 mb-3">4. Tulisan di Atas Kue</label>
                        <input type="text" name="tulisan" placeholder="Contoh: Selamat Ulang Tahun, Ayah!" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500">
                    </div>

                    {{-- Hidden input untuk menyimpan total harga --}}
                    <input type="hidden" name="final_price" id="final-price-input" value="{{ $basePrice }}">
                    
                    <button type="submit" class="w-full bg-pink-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-pink-700 transition-colors duration-300 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Tambah ke Keranjang
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Ambil harga dasar dari PHP
const basePrice = {{ $basePrice }};
const form = document.getElementById('custom-cake-form');
const displayPrice = document.getElementById('display-price');
const finalPriceInput = document.getElementById('final-price-input');

// Fungsi untuk format angka ke Rupiah
function formatRupiah(number) {
    return 'Rp ' + number.toLocaleString('id-ID');
}

// Fungsi untuk menghitung total harga
function calculateTotal() {
    let total = basePrice;
    
    // 1. Hitung harga ukuran (radio)
    const selectedSize = form.querySelector('input[name="ukuran"]:checked');
    if (selectedSize) {
        total += parseInt(selectedSize.dataset.price);
    }
    
    // 2. Hitung harga rasa (select)
    const selectedFlavor = form.querySelector('#options-flavor option:checked');
    if (selectedFlavor) {
        total += parseInt(selectedFlavor.dataset.price);
    }

    // 3. Hitung harga topping (checkbox)
    const selectedToppings = form.querySelectorAll('input[name="toppings[]"]:checked');
    selectedToppings.forEach(topping => {
        total += parseInt(topping.dataset.price);
    });

    // Update tampilan harga dan hidden input
    displayPrice.textContent = formatRupiah(total);
    finalPriceInput.value = total;
}

// Tambahkan listener ke setiap grup opsi
form.querySelector('#options-size').addEventListener('change', calculateTotal);
form.querySelector('#options-flavor').addEventListener('change', calculateTotal);
form.querySelector('#options-toppings').addEventListener('change', calculateTotal);

// Inisialisasi harga saat halaman dimuat
calculateTotal();
</script>
@endsection