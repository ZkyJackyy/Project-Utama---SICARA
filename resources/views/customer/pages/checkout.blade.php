@extends('layouts.navbar')
@section('title', 'Checkout')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-50 via-blue-50 to-purple-100 py-10 px-4 sm:px-6 lg:px-8">

    {{-- Card utama --}}
    <div class="w-full max-w-3xl bg-white/80 backdrop-blur-xl border border-gray-200 shadow-2xl rounded-3xl overflow-hidden">

        {{-- Header --}}
        <div class="p-8 border-b border-gray-200 flex justify-between items-center bg-gradient-to-r from-indigo-600 to-purple-600">
            <h1 class="text-3xl font-bold text-white tracking-tight flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>
                </svg>
                Checkout
            </h1>
            <a href="{{ route('keranjang.index') }}" 
               class="text-sm font-medium text-white/90 hover:text-white transition">
                ← Kembali ke Keranjang
            </a>
        </div>

        {{-- Body --}}
        <div class="p-8">

            {{-- Ringkasan Pesanan --}}
            <h2 class="text-xl font-semibold text-gray-800 mb-5">🛍️ Ringkasan Pesanan</h2>
            
            <div class="divide-y divide-gray-200/70">
                @forelse($cart as $item)
                    <div class="flex justify-between items-center py-4">
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $item['nama_produk'] }}</h3>
                            <p class="text-sm text-gray-500 mt-1">
                                Rp {{ number_format($item['harga'], 0, ',', '.') }} × {{ $item['jumlah'] }}
                            </p>
                        </div>
                        <p class="font-semibold text-indigo-700">
                            Rp {{ number_format($item['harga'] * $item['jumlah'], 0, ',', '.') }}
                        </p>
                    </div>
                @empty
                    <p class="text-gray-500 py-4 text-center">Keranjang Anda kosong.</p>
                @endforelse
            </div>

            {{-- Total --}}
            <div class="flex justify-between items-center mt-8 border-t border-gray-200 pt-6">
                <span class="font-bold text-lg text-gray-900">Total</span>
                <span class="font-bold text-2xl text-indigo-600">
                    Rp {{ number_format($total, 0, ',', '.') }}
                </span>
            </div>

            {{-- Form Pembayaran --}}
            <form action="{{ route('checkout.proses') }}" method="POST" enctype="multipart/form-data" class="mt-10 space-y-6">
                @csrf

                {{-- Metode Pembayaran --}}
                <div>
                    <label for="metode_pembayaran" class="block text-sm font-semibold text-gray-700 mb-2">
                        Metode Pembayaran
                    </label>
                    <select id="metode_pembayaran" name="metode_pembayaran"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-700 bg-white"
                        required>
                        <option value="">-- Pilih Metode Pembayaran --</option>
                        <option value="transfer_bank">Transfer Bank</option>
                        <option value="qris">QRIS</option>
                        <option value="cod">Cash on Delivery (COD)</option>
                    </select>
                </div>

                {{-- Info Transfer Bank --}}
                <div id="bank-container" class="hidden p-5 bg-indigo-50/70 rounded-2xl border border-indigo-100 shadow-inner space-y-3">
                    <p class="text-sm text-gray-700 font-medium">💳 Silakan transfer ke rekening berikut:</p>
                    <div class="bg-white rounded-xl p-4 shadow">
                        <p class="text-gray-800 font-semibold">Bank BCA</p>
                        <p class="text-gray-700 text-sm">No. Rekening: <span class="font-semibold">1234567890</span></p>
                        <p class="text-gray-700 text-sm">Atas Nama: <span class="font-semibold">PT. Toko Online Sejahtera</span></p>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Setelah melakukan transfer, silakan unggah bukti pembayaran Anda di bawah.</p>
                </div>

                {{-- QRIS --}}
                <div id="qris-container" class="hidden text-center space-y-3 p-5 bg-indigo-50/70 rounded-2xl border border-indigo-100 shadow-inner">
                    <p class="text-sm font-medium text-gray-700">Pindai untuk Membayar:</p>
                    <img src="{{ asset('gambar/qris.jpg') }}" 
                         alt="QRIS Code" 
                         class="w-64 h-64 mx-auto rounded-xl border-2 border-indigo-200 p-1 shadow">
                    <p class="text-xs text-gray-500">Setelah membayar, unggah bukti pembayaran Anda di bawah.</p>
                </div>

                {{-- Bukti Pembayaran --}}
                <div id="bukti-container" class="hidden space-y-2">
                    <label for="bukti_pembayaran" class="block text-sm font-semibold text-gray-700">
                        Upload Bukti Pembayaran
                    </label>
                    <input type="file" id="bukti_pembayaran" name="bukti_pembayaran" accept="image/*"
                        class="block w-full text-sm text-gray-600 
                               file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 
                               file:text-sm file:font-semibold file:bg-indigo-100 file:text-indigo-700 
                               hover:file:bg-indigo-200 border border-gray-300 rounded-lg cursor-pointer 
                               bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <p class="text-xs text-gray-500">Format: JPG, PNG, max 2MB.</p>

                    {{-- Preview --}}
                    <div id="preview-container" class="mt-3 hidden">
                        <p class="text-xs font-semibold text-gray-700 mb-1">Preview:</p>
                        <img id="preview-image" class="w-48 h-48 object-cover rounded-lg border border-gray-300 shadow-md">
                    </div>
                </div>

                {{-- Tombol Submit --}}
                <button type="submit"
                    class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white py-3.5 rounded-xl font-semibold shadow-md hover:shadow-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Konfirmasi & Buat Pesanan
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Script interaktif --}}
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
