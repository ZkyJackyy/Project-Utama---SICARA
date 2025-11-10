@extends('layouts.navbar')
@section('title', 'Checkout')

@section('content')
{{-- Latar belakang diubah menjadi abu-abu netral yang bersih --}}
<div class="min-h-screen bg-gray-100 py-10 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-6xl mx-auto">

        {{-- Judul Halaman dan Link Kembali --}}
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-center">
            <h1 class="text-3xl font-bold text-[#700207]">Checkout</h1>
            <a href="{{ route('keranjang.index') }}" 
               class="text-sm font-medium text-gray-600 hover:text-[#700207] transition mt-2 sm:mt-0">
                ← Kembali ke Keranjang
            </a>
        </div>

        {{-- Form membungkus kedua kolom --}}
        <form action="{{ route('checkout.proses') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            {{-- Grid utama (2 kolom di layar large) --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

                {{-- KOLOM KIRI: Detail Pembayaran --}}
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-lg shadow-md p-6 sm:p-8 space-y-6">
                        
                        {{-- Metode Pembayaran --}}
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Metode Pembayaran</h2>
                            <label for="metode_pembayaran" class="sr-only">Metode Pembayaran</label>
                            <select id="metode_pembayaran" name="metode_pembayaran"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#700207] focus:border-[#700207] text-gray-700 bg-white"
                                    required>
                                <option value="">-- Pilih Metode Pembayaran --</option>
                                <option value="transfer_bank">Transfer Bank</option>
                                <option value="qris">QRIS</option>
                                <option value="cod">Cash on Delivery (COD)</option>
                            </select>
                        </div>

                        {{-- Info Transfer Bank --}}
                        <div id="bank-container" class="hidden p-5 bg-gray-50 rounded-lg border border-gray-200 shadow-inner space-y-3">
                            <p class="text-sm text-gray-700 font-medium">💳 Silakan transfer ke rekening berikut:</p>
                            <div class="bg-white rounded-xl p-4 border border-gray-200">
                                <p class="text-gray-800 font-semibold">Bank BCA</p>
                                <p class="text-gray-700 text-sm">No. Rekening: <span class="font-semibold">1234567890</span></p>
                                <p class="text-gray-700 text-sm">Atas Nama: <span class="font-semibold">PT. Toko Online Sejahtera</span></p>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Setelah melakukan transfer, silakan unggah bukti pembayaran Anda di bawah.</p>
                        </div>

                        {{-- QRIS --}}
                        <div id="qris-container" class="hidden text-center space-y-3 p-5 bg-gray-50 rounded-lg border border-gray-200 shadow-inner">
                            <p class="text-sm font-medium text-gray-700">Pindai untuk Membayar:</p>
                            <img src="{{ asset('gambar/qris.jpg') }}" 
                                 alt="QRIS Code" 
                                 class="w-64 h-64 mx-auto rounded-xl border-2 border-gray-300 p-1 shadow-sm">
                            <p class="text-xs text-gray-500">Setelah membayar, unggah bukti pembayaran Anda di bawah.</p>
                        </div>

                        {{-- Bukti Pembayaran --}}
                        <div id="bukti-container" class="hidden space-y-2 pt-4 border-t border-gray-200">
                            <label for="bukti_pembayaran" class="block text-sm font-semibold text-gray-700">
                                Upload Bukti Pembayaran
                            </label>
                            <input type="file" id="bukti_pembayaran" name="bukti_pembayaran" accept="image/*"
                                   class="block w-full text-sm text-gray-600 
                                          file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 
                                          file:text-sm file:font-semibold file:bg-red-50 file:text-[#700207] 
                                          hover:file:bg-red-100 border border-gray-300 rounded-lg cursor-pointer 
                                          bg-white focus:outline-none focus:ring-2 focus:ring-[#700207]">
                            <p class="text-xs text-gray-500">Format: JPG, PNG, max 2MB.</p>
                            
                            {{-- Preview --}}
                            <div id="preview-container" class="mt-3 hidden">
                                <p class="text-xs font-semibold text-gray-700 mb-1">Preview:</p>
                                <img id="preview-image" class="w-48 h-48 object-cover rounded-lg border border-gray-300 shadow-md">
                            </div>
                        </div>

                        {{-- Tombol Submit --}}
                        <button type="submit"
                                class="w-full bg-[#700207] hover:bg-[#4a0105] text-white py-3.5 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#700207]">
                            Konfirmasi & Buat Pesanan
                        </button>
                    </div>
                </div>

                {{-- KOLOM KANAN: Ringkasan Pesanan (Sticky) --}}
                <div class="lg:col-span-2">
                    {{-- Posisi sticky agar tetap terlihat saat scroll --}}
                    <div class="bg-white rounded-lg shadow-md lg:sticky lg:top-24">
                        
                        {{-- Header Kartu --}}
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                                🛍️ Ringkasan Pesanan
                            </h2>
                        </div>
                        
                        {{-- Daftar Item --}}
                        <div class="p-6 divide-y divide-gray-200">
                            @forelse($cart as $item)
                                <div class="flex justify-between items-center py-4">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $item['nama_produk'] }}</h3>
                                        <p class="text-sm text-gray-500 mt-1">
                                            Rp {{ number_format($item['harga'], 0, ',', '.') }} × {{ $item['jumlah'] }}
                                        </p>
                                    </div>
                                    <p class="font-semibold text-gray-800">
                                        Rp {{ number_format($item['harga'] * $item['jumlah'], 0, ',', '.') }}
                                    </p>
                                </div>
                            @empty
                                <p class="text-gray-500 py-4 text-center">Keranjang Anda kosong.</p>
                            @endforelse
                        </div>

                        {{-- Total --}}
                        <div class="p-6 border-t border-gray-200 bg-gray-50 rounded-b-lg">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-lg text-gray-900">Total</span>
                                <span class="font-bold text-2xl text-[#700207]">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
            </div>
        </form>
    </div>
</div>

{{-- Script interaktif (Tidak ada perubahan, fungsionalitas tetap aman) --}}
<script>
    const metodeSelect = document.getElementById('metode_pembayaran');
    const qrisContainer = document.getElementById('qris-container');
    const bankContainer = document.getElementById('bank-container');
    const buktiContainer = document.getElementById('bukti-container');
    const fileInput = document.getElementById('bukti_pembayaran');
    const previewContainer = document.getElementById('preview-container');
    const previewImage = document.getElementById('preview-image');

    metodeSelect.addEventListener('change', (event) => {
        const selectedValue = event.target.value;
        qrisContainer.classList.add('hidden');
        bankContainer.classList.add('hidden');
        buktiContainer.classList.add('hidden');
        fileInput.required = false;

        if (selectedValue === 'qris') {
            qrisContainer.classList.remove('hidden');
            buktiContainer.classList.remove('hidden');
            fileInput.required = true;
        } else if (selectedValue === 'transfer_bank') {
            bankContainer.classList.remove('hidden');
            buktiContainer.classList.remove('hidden');
            fileInput.required = true;
        }
    });

    fileInput.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImage.src = e.target.result;
                previewContainer.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            previewContainer.classList.add('hidden');
            previewImage.src = "";
        }
    });
</script>
@endsection