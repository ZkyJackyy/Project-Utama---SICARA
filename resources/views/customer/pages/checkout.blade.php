@extends('layouts.navbar')
@section('title', 'Checkout')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8 font-sans">
    <div class="w-full max-w-7xl mx-auto">

        {{-- Header --}}
        <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Checkout</h1>
                <p class="text-sm text-gray-500 mt-1">Selesaikan pesanan Anda dengan aman</p>
            </div>
            <a href="{{ route('keranjang.index') }}" 
               class="text-sm font-medium text-[#700207] hover:text-[#5a0105] flex items-center gap-2 transition bg-white px-4 py-2 rounded-full shadow-sm border border-gray-200 hover:shadow-md">
                <i class="fa fa-arrow-left"></i> Kembali ke Keranjang
            </a>
        </div>

        <form action="{{ route('checkout.proses') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            {{-- GRID LAYOUT --}}
            <div class="flex flex-col lg:flex-row gap-8">

                {{-- BAGIAN KANAN (RINGKASAN) --}}
                {{-- Mobile: Urutan PERTAMA (order-1) | Desktop: Urutan KEDUA (lg:order-2) --}}
                <div class="w-full lg:w-5/12 order-1 lg:order-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden lg:sticky lg:top-24">
                        
                        <div class="p-6 bg-gray-50/50 border-b border-gray-100">
                            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <i class="fa fa-shopping-bag text-[#700207]"></i> Ringkasan Pesanan
                            </h2>
                        </div>

                        {{-- List Item (Scrollable jika terlalu panjang) --}}
                        <div class="p-6 max-h-[400px] overflow-y-auto custom-scrollbar">
                            <ul class="divide-y divide-gray-100">
                                @forelse($cartItems as $item)
                                    <li class="flex py-4 gap-4">
                                        {{-- Gambar Kecil --}}
                                        <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-lg border border-gray-200 bg-gray-100">
                                            <img src="{{ asset('storage/produk/' . $item->product->gambar) }}" 
                                                 alt="{{ $item->product->nama_produk }}" 
                                                 class="h-full w-full object-cover object-center">
                                        </div>

                                        <div class="flex flex-1 flex-col justify-center">
                                            <div>
                                                <div class="flex justify-between text-base font-medium text-gray-900">
                                                    <h3 class="text-sm font-semibold line-clamp-1" title="{{ $item->product->nama_produk }}">
                                                        {{ $item->product->nama_produk }}
                                                    </h3>
                                                    <p class="ml-4 text-sm whitespace-nowrap">
                                                        Rp {{ number_format($item->product->harga * $item->jumlah, 0, ',', '.') }}
                                                    </p>
                                                </div>
                                                
                                                {{-- Detail Varian/Custom --}}
                                                @if($item->custom_deskripsi)
                                                    <p class="mt-1 text-xs text-gray-500 bg-yellow-50 p-2 rounded border border-yellow-100">
                                                        <i class="fa fa-pen-fancy text-yellow-600 mr-1"></i>
                                                        {{ Str::limit($item->custom_deskripsi, 60) }}
                                                    </p>
                                                @else
                                                    <p class="mt-1 text-xs text-gray-500">Regular Item</p>
                                                @endif
                                            </div>
                                            <div class="flex items-center justify-between text-xs text-gray-500 mt-2">
                                                <p>Qty: {{ $item->jumlah }}</p>
                                                <p>@ Rp {{ number_format($item->product->harga, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <li class="py-4 text-center text-gray-500 text-sm">Keranjang kosong</li>
                                @endforelse
                            </ul>
                        </div>

                        {{-- Total Section --}}
                        <div class="border-t border-gray-100 bg-gray-50 p-6">
                            <div class="flex justify-between text-base font-medium text-gray-900 mb-4">
                                <p>Subtotal</p>
                                <p>Rp {{ number_format($total, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex justify-between text-lg font-bold text-[#700207]">
                                <p>Total Bayar</p>
                                <p>Rp {{ number_format($total, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BAGIAN KIRI (FORM PEMBAYARAN) --}}
                {{-- Mobile: Urutan KEDUA (order-2) | Desktop: Urutan PERTAMA (lg:order-1) --}}
                <div class="w-full lg:w-7/12 order-2 lg:order-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                        
                        <h2 class="text-xl font-bold text-gray-900 mb-6 border-b border-gray-100 pb-4">
                            Informasi Pembayaran
                        </h2>

                        <div class="space-y-6">
                            
                            {{-- Pilihan Metode --}}
                            <div>
                                <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">
                                    Pilih Metode Pembayaran
                                </label>
                                <div class="relative">
                                    <select id="metode_pembayaran" name="metode_pembayaran"
                                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#700207] focus:ring-[#700207] sm:text-sm py-3 px-4 appearance-none cursor-pointer"
                                            required>
                                        <option value="">-- Silakan Pilih --</option>
                                        <option value="transfer_bank">Transfer Bank (BCA)</option>
                                        <option value="qris">QRIS (Scan Barcode)</option>
                                        <option value="cod">Cash on Delivery (Bayar di Tempat)</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                        <i class="fa fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            </div>

                            {{-- Info Transfer Bank --}}
                            <div id="bank-container" class="hidden animate-fade-in-down">
                                <div class="bg-blue-50 rounded-xl border border-blue-100 p-5">
                                    <div class="flex gap-3 items-start">
                                        <div class="bg-white p-2 rounded shadow-sm text-blue-600">
                                            <i class="fa fa-university text-xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-gray-900 text-sm">Bank BCA</h3>
                                            <p class="text-gray-600 text-xs mt-1">Silakan transfer ke nomor rekening di bawah ini:</p>
                                            
                                            <div class="mt-3 bg-white border border-blue-100 rounded-lg p-3 flex justify-between items-center">
                                                <div>
                                                    <p class="text-xs text-gray-500">No. Rekening</p>
                                                    <p class="text-lg font-mono font-bold text-gray-800">1234567890</p>
                                                    <p class="text-xs text-gray-500">a.n PT. Toko Online Sejahtera</p>
                                                </div>
                                                <button type="button" onclick="navigator.clipboard.writeText('1234567890'); alert('Nomor rekening disalin!')" class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                                    Salin
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- QRIS --}}
                            <div id="qris-container" class="hidden animate-fade-in-down">
                                <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 text-center">
                                    <p class="font-medium text-gray-900 mb-3">Scan QRIS untuk Membayar</p>
                                    
                                    {{-- Wrapper Gambar --}}
                                    <div class="bg-white p-2 inline-block rounded-lg shadow-sm border border-gray-200 group relative">
                                        {{-- Tambahkan Link Download di sini --}}
                                        <a href="{{ asset('gambar/qris.jpg') }}" download="QRIS-DaraCake.jpg" title="Klik untuk download">
                                            <img src="{{ asset('gambar/qris.jpg') }}" 
                                                 alt="QRIS Code" 
                                                 class="w-48 h-48 object-contain hover:opacity-90 transition">
                                            
                                            {{-- Overlay icon download saat di-hover (Opsional, pemanis visual) --}}
                                            <div class="absolute inset-0 flex items-center justify-center bg-black/10 opacity-0 group-hover:opacity-100 transition rounded-lg cursor-pointer">
                                                <i class="fa fa-download text-gray-800 bg-white/80 p-2 rounded-full"></i>
                                            </div>
                                        </a>
                                    </div>

                                    {{-- Tombol Teks Download --}}
                                    <div class="mt-2">
                                        <a href="{{ asset('gambar/qris.jpg') }}" download="QRIS-DaraCake.jpg" 
                                           class="text-sm font-medium text-[#700207] hover:text-[#5a0105] hover:underline inline-flex items-center gap-1 transition">
                                            <i class="fa fa-download"></i> Simpan Gambar QRIS
                                        </a>
                                    </div>

                                    <p class="text-xs text-gray-500 mt-3">Mendukung GoPay, OVO, Dana, ShopeePay, BCA Mobile, dll.</p>
                                </div>
                            </div>

                            {{-- Upload Bukti --}}
                            <div id="bukti-container" class="hidden animate-fade-in-down pt-4 border-t border-gray-100">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Upload Bukti Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-[#700207] hover:bg-red-50 transition cursor-pointer relative">
                                    <div class="space-y-1 text-center">
                                        <i class="fa fa-image text-gray-400 text-3xl mb-2"></i>
                                        <div class="flex text-sm text-gray-600 justify-center">
                                            <label for="bukti_pembayaran" class="relative cursor-pointer rounded-md font-medium text-[#700207] hover:underline focus-within:outline-none">
                                                <span>Upload file</span>
                                                <input id="bukti_pembayaran" name="bukti_pembayaran" type="file" class="sr-only" accept="image/*">
                                            </label>
                                            <p class="pl-1">atau drag & drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                    </div>
                                    
                                    {{-- Preview Image Overlay --}}
                                    <img id="preview-image" class="hidden absolute inset-0 w-full h-full object-contain bg-white rounded-lg p-2">
                                </div>
                                <button type="button" id="remove-preview" class="hidden text-xs text-red-600 mt-2 hover:underline">Hapus Gambar</button>
                            </div>

                            {{-- Tombol Aksi --}}
                            <div class="pt-6">
                                <button type="submit"
                                        class="w-full flex justify-center items-center gap-2 bg-[#700207] hover:bg-[#5a0105] text-white py-4 rounded-xl font-bold text-lg shadow-lg shadow-red-900/20 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                                    <span>Bayar Sekarang</span>
                                    <i class="fa fa-arrow-right"></i>
                                </button>
                                <p class="text-center text-xs text-gray-400 mt-4">
                                    <i class="fa fa-lock mr-1"></i> Transaksi Anda aman dan terenkripsi.
                                </p>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- Script --}}
<script>
    const metodeSelect = document.getElementById('metode_pembayaran');
    const qrisContainer = document.getElementById('qris-container');
    const bankContainer = document.getElementById('bank-container');
    const buktiContainer = document.getElementById('bukti-container');
    const fileInput = document.getElementById('bukti_pembayaran');
    const previewImage = document.getElementById('preview-image');
    const removePreviewBtn = document.getElementById('remove-preview');

    metodeSelect.addEventListener('change', (event) => {
        const selectedValue = event.target.value;
        
        // Reset Tampilan
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
                previewImage.classList.remove('hidden');
                removePreviewBtn.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    removePreviewBtn.addEventListener('click', () => {
        fileInput.value = '';
        previewImage.src = '';
        previewImage.classList.add('hidden');
        removePreviewBtn.classList.add('hidden');
    });
</script>

<style>
    /* Scrollbar Custom untuk list keranjang */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1; 
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #d1d5db; 
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #9ca3af; 
    }
    
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-down {
        animation: fadeInDown 0.3s ease-out forwards;
    }
</style>
@endsection