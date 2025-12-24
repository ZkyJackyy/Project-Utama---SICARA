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
            
            <input type="hidden" name="selected_ids" value="{{ $selectedIdsString }}">
            {{-- Input Hidden untuk Tipe Pengiriman (shipping/pickup) --}}
            <input type="hidden" name="delivery_type" id="delivery_type" value="shipping">
            
            {{-- GRID LAYOUT --}}
            <div class="flex flex-col lg:flex-row gap-8">

                {{-- KOLOM KIRI (UTAMA): Ringkasan Produk & Pengiriman --}}
                <div class="w-full lg:w-7/12 space-y-6">
                    
                    {{-- 1. RINGKASAN PRODUK --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 bg-gray-50/50 border-b border-gray-100">
                            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <i class="fa fa-shopping-bag text-[#700207]"></i> Ringkasan Produk
                            </h2>
                        </div>
                        <div class="p-6 max-h-[300px] overflow-y-auto custom-scrollbar">
                            <ul class="divide-y divide-gray-100">
                                @forelse($cartItems as $item)
                                    <li class="flex py-4 gap-4">
                                        <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-lg border border-gray-200 bg-gray-100">
                                            <img src="{{ asset('storage/produk/' . $item->product->gambar) }}" 
                                                 alt="{{ $item->product->nama_produk }}" 
                                                 class="h-full w-full object-cover object-center">
                                        </div>
                                        <div class="flex flex-1 flex-col justify-center">
                                            <div>
                                                <div class="flex justify-between text-base font-medium text-gray-900">
                                                    <h3 class="text-sm font-semibold line-clamp-1">{{ $item->product->nama_produk }}</h3>
                                                    <p class="ml-4 text-sm whitespace-nowrap">
                                                        @php $hargaFinal = $item->custom_price ?? $item->product->harga; @endphp
                                                        Rp {{ number_format($hargaFinal * $item->jumlah, 0, ',', '.') }}
                                                    </p>
                                                </div>
                                                @if($item->custom_deskripsi)
                                                    <p class="mt-1 text-xs text-gray-500 bg-yellow-50 p-2 rounded border border-yellow-100">
                                                        <i class="fa fa-pen-fancy text-yellow-600 mr-1"></i> {{ Str::limit($item->custom_deskripsi, 60) }}
                                                    </p>
                                                @else
                                                    <p class="mt-1 text-xs text-gray-500">Regular Item</p>
                                                @endif
                                            </div>
                                            <div class="flex items-center justify-between text-xs text-gray-500 mt-2">
                                                <p>Qty: {{ $item->jumlah }}</p>
                                                <p>@ Rp {{ number_format($hargaFinal, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <li class="py-4 text-center text-gray-500 text-sm">Keranjang kosong</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    {{-- 2. METODE PENGIRIMAN --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 border-b border-gray-100 pb-4">
                            Metode Pengiriman
                        </h2>

                        {{-- NEW: PILIHAN TIPE PENGIRIMAN --}}
                        <div class="flex gap-4 mb-6">
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="tipe_pengiriman_radio" value="shipping" class="peer sr-only" checked>
                                <div class="rounded-lg border border-gray-300 p-4 text-center peer-checked:border-[#700207] peer-checked:bg-red-50 hover:bg-gray-50 transition">
                                    <i class="fa fa-truck text-xl mb-2 text-gray-600 peer-checked:text-[#700207]"></i>
                                    <div class="font-semibold text-gray-900 peer-checked:text-[#700207]">Jasa Ekspedisi</div>
                                </div>
                            </label>
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="tipe_pengiriman_radio" value="pickup" class="peer sr-only">
                                <div class="rounded-lg border border-gray-300 p-4 text-center peer-checked:border-[#700207] peer-checked:bg-red-50 hover:bg-gray-50 transition">
                                    <i class="fa fa-store text-xl mb-2 text-gray-600 peer-checked:text-[#700207]"></i>
                                    <div class="font-semibold text-gray-900 peer-checked:text-[#700207]">Ambil di Toko</div>
                                </div>
                            </label>
                        </div>

                        {{-- FORM RAJAONGKIR (Hanya muncul jika pilih Ekspedisi) --}}
                        <div id="shipping-form-container">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                                    <select id="province-select" class="w-full border-gray-300 rounded-lg focus:ring-[#700207] focus:border-[#700207]">
                                        <option value="">Pilih Provinsi</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kota/Kabupaten</label>
                                    <select name="city_destination" id="city-select" class="w-full border-gray-300 rounded-lg focus:ring-[#700207] focus:border-[#700207]" disabled>
                                        <option value="">Pilih Kota</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kurir</label>
                                <select name="courier" id="courier-select" class="w-full border-gray-300 rounded-lg focus:ring-[#700207] focus:border-[#700207]">
                                    <option value="jne">JNE</option>
                                    <option value="pos">POS Indonesia</option>
                                    <option value="tiki">TIKI</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <button type="button" id="btn-check-ongkir" class="w-full bg-gray-800 text-white py-2 rounded-lg hover:bg-black transition font-medium text-sm">
                                    Cek Ongkos Kirim
                                </button>
                            </div>

                            <div id="ongkir-results" class="space-y-3 hidden bg-gray-50 p-4 rounded-xl border border-gray-200">
                                <p class="font-bold text-sm text-gray-700 mb-2">Pilih Layanan Pengiriman:</p>
                                <div id="ongkir-list" class="space-y-2"></div>
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap (Jalan, No Rumah, RT/RW)</label>
                                <textarea name="shipping_address" id="shipping_address" rows="3" class="w-full border-gray-300 rounded-lg focus:ring-[#700207] focus:border-[#700207]" required placeholder="Jl. Merpati No. 10..."></textarea>
                            </div>
                        </div>

                        {{-- INFO AMBIL DI TOKO (Hanya muncul jika pilih Pickup) --}}
                        <div id="pickup-info-container" class="hidden animate-fade-in-down">
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                    <i class="fa fa-map-marker-alt text-[#700207]"></i> Lokasi Toko
                                </h3>
                                <p class="text-sm text-gray-600 mt-2">
                                    <strong>DaraCake</strong><br>
                                    Jl. Jambak Indah No 42 Rimbo Data,<br>
                                     Kel. Bandar Buat, Kota Padang<br>
                                    No. Telp: 081268879898
                                </p>
                                <p class="text-xs text-gray-500 mt-3 italic">*Silakan datang ke toko untuk mengambil pesanan setelah melakukan pembayaran.</p>
                            </div>
                        </div>

                        {{-- Input Hidden Ongkir --}}
                        <input type="hidden" name="shipping_cost" id="shipping_cost_input" value="0">
                        <input type="hidden" name="shipping_service" id="shipping_service_input">
                    </div>

                </div>

                {{-- KOLOM KANAN (SIDEBAR): Total & Pembayaran --}}
                <div class="w-full lg:w-5/12 space-y-6">
                    
                    {{-- 3. TOTAL BAYAR (Sticky) --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:sticky lg:top-24">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Rincian Biaya</h2>
                        
                        <div class="space-y-3 pb-4 border-b border-gray-100">
                            <div class="flex justify-between text-base font-medium text-gray-600">
                                <p>Subtotal Produk</p>
                                <p>Rp {{ number_format($total, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex justify-between text-base font-medium text-gray-600">
                                <p>Ongkos Kirim</p>
                                <p id="shipping-display" class="text-green-600 font-bold">Rp 0</p>
                            </div>
                        </div>
                        
                        <div class="flex justify-between text-xl font-bold text-[#700207] mt-4 mb-6">
                            <p>Total Bayar</p>
                            <p id="total-display">Rp {{ number_format($total, 0, ',', '.') }}</p>
                        </div>

                        {{-- 4. INFORMASI PEMBAYARAN --}}
                        <div class="space-y-4">
                            <div>
                                <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                                <select id="metode_pembayaran" name="metode_pembayaran" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#700207] focus:ring-[#700207] py-3 px-4" required>
                                    <option value="">-- Silakan Pilih --</option>
                                    <option value="transfer_bank">Transfer Bank</option>
                                    <option value="qris">QRIS</option>
                                    <option value="cod">Cash on Delivery (Bayar di Tempat)</option>
                                </select>
                            </div>

                            {{-- Info Transfer --}}
                            <div id="bank-container" class="hidden animate-fade-in-down bg-blue-50 rounded-xl border border-blue-100 p-4">
                                <p class="text-sm font-bold text-gray-900 mb-1">Bank BRI</p>
                                <p class="text-xs text-gray-600 mb-2">No. Rek: <span class="font-mono font-bold text-gray-800">546401019554530</span></p>
                                <p class="text-[10px] text-gray-500">a.n ROZA LINDA</p>
                            </div>

                            {{-- QRIS --}}
                            <div id="qris-container" class="hidden animate-fade-in-down bg-gray-50 rounded-xl border border-gray-200 p-4 text-center">
                                <p class="text-xs font-medium text-gray-900 mb-2">Scan QRIS</p>
                                <img src="{{ asset('gambar/qris.jpg') }}" class="w-32 h-32 object-contain mx-auto border border-gray-200 rounded-lg">
                                <a href="{{ asset('gambar/qris.jpg') }}" download class="text-[10px] text-[#700207] hover:underline mt-2 block">Download QRIS</a>
                            </div>

                            {{-- Upload Bukti --}}
                            <div id="bukti-container" class="hidden animate-fade-in-down">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Bayar <span class="text-red-500">*</span></label>
                                <input id="bukti_pembayaran" name="bukti_pembayaran" type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-[#700207] hover:file:bg-red-100" accept="image/*">
                            </div>
                        </div>

                        {{-- Tombol Submit --}}
                        <button type="submit" class="w-full mt-6 flex justify-center items-center gap-2 bg-[#700207] hover:bg-[#5a0105] text-white py-4 rounded-xl font-bold text-lg shadow-lg shadow-red-900/20 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                            <span>Bayar Sekarang</span>
                            <i class="fa fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    const subtotal = {{ $total }};
    const totalWeight = {{ $totalWeight ?? 1000 }};
    const ORIGIN_CITY_ID = 318; 

    $(document).ready(function () {

        // ==========================================
        // 0. LOGIC TOGGLE (PENGIRIMAN VS AMBIL TOKO)
        // ==========================================
        // Ini logic baru, JS lama di bawah tidak saya sentuh
        $('input[name="tipe_pengiriman_radio"]').on('change', function() {
            const tipe = $(this).val();
            
            // Set value ke hidden input agar terbaca backend
            $('#delivery_type').val(tipe);

            if(tipe === 'pickup') {
                // Sembunyikan Form Ekspedisi
                $('#shipping-form-container').addClass('hidden');
                // Tampilkan Info Toko
                $('#pickup-info-container').removeClass('hidden');
                
                // Matikan required pada alamat pengiriman (supaya bisa submit)
                $('#shipping_address').prop('required', false);

                // Set Ongkir ke 0
                $('#shipping_cost_input').val(0);
                $('#shipping_service_input').val('PICKUP_STORE');
                
                // Update Tampilan Harga
                $('#shipping-display').text('Rp 0');
                const total = subtotal; // Tanpa ongkir
                $('#total-display').text('Rp ' + new Intl.NumberFormat('id-ID').format(total));

            } else {
                // Tampilkan Form Ekspedisi
                $('#shipping-form-container').removeClass('hidden');
                // Sembunyikan Info Toko
                $('#pickup-info-container').addClass('hidden');
                
                // Nyalakan required lagi
                $('#shipping_address').prop('required', true);

                // Reset Ongkir (Balikin ke 0 dulu sampai user cek lagi)
                $('#shipping_cost_input').val(0);
                $('#shipping_service_input').val('');
                $('#shipping-display').text('Rp 0');
                $('#total-display').text('Rp ' + new Intl.NumberFormat('id-ID').format(subtotal));
                $('#ongkir-list').empty(); // Reset list ongkir sebelumnya
            }
        });


        // =========================
        // JS ASLI ANDA (TIDAK SAYA GANTI)
        // =========================
        
        // 1. LOAD PROVINSI (KOMERCE)
        $.get('/api/provinces', function (response) {
            response.data.forEach(function (prov) {
                $('#province-select').append(
                    `<option value="${prov.id}">${prov.name}</option>`
                );
            });
        });

        // 2. LOAD KOTA BY PROVINSI
        $('#province-select').on('change', function () {
            const provinceId = $(this).val();
            const citySelect = $('#city-select');
            
            citySelect.prop('disabled', true);
            citySelect.html('<option value="">Loading kota...</option>');

            if (!provinceId) {
                citySelect.html('<option value="">Pilih Kota</option>');
                return;
            }

            $.ajax({
                url: `/api/cities/${provinceId}`,
                method: 'GET',
                success: function (response) {
                    if (response.success && response.data.length > 0) {
                        citySelect.html('<option value="">Pilih Kota</option>');
                        response.data.forEach(function (city) {
                            let id = city.id || city.city_id;
                            let name = city.name || city.city_name || city.type + ' ' + city.city_name;
                            citySelect.append(`<option value="${id}">${name}</option>`);
                        });
                        citySelect.prop('disabled', false);
                    } else {
                        citySelect.html('<option value="">Kota tidak ditemukan</option>');
                    }
                },
                error: function (xhr) {
                    console.error('Error:', xhr.responseText);
                    citySelect.html('<option value="">Gagal memuat kota (Cek Console)</option>');
                }
            });
        });

        // 3. CEK ONGKIR (FLAT DATA FIX)
        $('#btn-check-ongkir').on('click', function () {
            const destination = $('#city-select').val();
            const courier = $('#courier-select').val();

            if (!destination) {
                alert('Pilih kota tujuan dulu!');
                return;
            }

            $('#ongkir-results').removeClass('hidden');
            $('#ongkir-list').html('<p class="text-sm text-center py-2 animate-pulse">Menghitung...</p>');

            $.ajax({
                url: '/api/ongkir',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    origin: ORIGIN_CITY_ID,
                    destination: destination,
                    weight: totalWeight,
                    courier: courier
                },
                success: function (response) {
                    console.log("Response API:", response);
                    $('#ongkir-list').empty();

                    if (!response.success) {
                        $('#ongkir-list').html(`<p class="text-red-500 text-sm">${response.message}</p>`);
                        return;
                    }

                    const results = response.data;
                    
                    if (!results || results.length === 0) {
                        $('#ongkir-list').html(`
                            <div class="text-center p-3">
                                <p class="text-red-500 text-sm font-bold">Ongkir Tidak Ditemukan</p>
                                <p class="text-xs text-gray-500">Coba pilih kurir lain atau cek kota asal/tujuan.</p>
                            </div>
                        `);
                        return;
                    }

                    results.forEach(function (item) {
                        const code = item.code;
                        const service = item.service;
                        const description = item.description || item.name; 
                        const price = item.cost;
                        const etd = item.etd || '-';
                        const priceFormatted = new Intl.NumberFormat('id-ID').format(price);

                        $('#ongkir-list').append(`
                            <label class="flex items-center p-3 border rounded mb-2 cursor-pointer hover:bg-gray-50 bg-white shadow-sm transition">
                                <input type="radio" name="shipping_option" value="${price}" data-service="${service}" 
                                    class="mr-3 w-4 h-4 text-red-800" onchange="selectShipping(this)">
                                <div class="flex-1">
                                    <div class="flex justify-between font-bold text-gray-800">
                                        <span>${code.toUpperCase()} - ${service}</span>
                                        <span class="text-[#700207]">Rp ${priceFormatted}</span>
                                    </div>
                                    <div class="text-xs text-gray-500">${description} (Est: ${etd} Hari)</div>
                                </div>
                            </label>
                        `);
                    });
                },
                error: function(xhr) {
                    $('#ongkir-list').html(`<p class="text-red-500 text-sm">Error: ${xhr.responseJSON?.message || 'Gagal mengambil data'}</p>`);
                }
            });
        });
    });

    // 4. Update Total Saat Pilih Ongkir
    function selectShipping(el) {
        const cost = parseInt(el.value);
        const service = el.getAttribute('data-service');
        
        $('#shipping-display').text('Rp ' + new Intl.NumberFormat('id-ID').format(cost));
        $('#total-display').text('Rp ' + new Intl.NumberFormat('id-ID').format(subtotal + cost));
        $('#shipping_cost_input').val(cost);
        $('#shipping_service_input').val(service);
    }

    // === 2. LOGIKA METODE PEMBAYARAN ===
    const metodeSelect = document.getElementById('metode_pembayaran');
    const qrisContainer = document.getElementById('qris-container');
    const bankContainer = document.getElementById('bank-container');
    const buktiContainer = document.getElementById('bukti-container');
    const fileInput = document.getElementById('bukti_pembayaran');

    if(metodeSelect) { // Tambahan if check agar tidak error jika element tidak ada
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
    }
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
    .animate-fade-in-down { animation: fadeInDown 0.3s ease-out forwards; }
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection