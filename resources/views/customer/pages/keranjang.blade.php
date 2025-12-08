@extends('layouts.navbar')

@section('title', 'Keranjang Belanja')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 font-sans">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Keranjang Belanja</h1>

        <div id="alert-container" class="hidden fixed top-24 right-5 z-50">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg">
                <strong class="font-bold">Oops!</strong> <span id="alert-message"></span>
            </div>
        </div>

        @if($cartItems->count() > 0)
        <div class="flex flex-col lg:flex-row gap-8" id="cart-container">
            
            {{-- BAGIAN KIRI: LIST ITEM --}}
            <div class="flex-1">
                {{-- Header Pilih Semua --}}
                <div class="bg-white shadow-sm rounded-t-2xl border-b border-gray-100 p-4 flex items-center gap-3">
                    <input type="checkbox" id="select-all" class="w-5 h-5 text-[#700207] rounded border-gray-300 focus:ring-[#700207]" onchange="toggleSelectAll(this)">
                    <label for="select-all" class="text-gray-700 font-medium cursor-pointer select-none">Pilih Semua</label>
                </div>

                <div class="bg-white shadow-sm rounded-b-2xl overflow-hidden border border-gray-100 border-t-0">
                    <ul class="divide-y divide-gray-100" id="cart-list">
                        @foreach($cartItems as $item)
                            <li class="p-6 flex gap-4 sm:gap-6 transition hover:bg-gray-50/50" id="row-{{ $item->id }}">
                                
                                {{-- CHECKBOX ITEM --}}
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           class="cart-item-checkbox w-5 h-5 text-[#700207] rounded border-gray-300 focus:ring-[#700207]"
                                           value="{{ $item->id }}"
                                           data-price="{{ $item->product->harga }}"
                                           data-qty="{{ $item->jumlah }}"
                                           onchange="recalculateTotal()">
                                </div>

                                {{-- Gambar --}}
                                <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-xl border border-gray-200 bg-gray-100">
                                    <img src="{{ asset('storage/produk/' . $item->product->gambar) }}" 
                                         alt="{{ $item->product->nama_produk }}" 
                                         class="h-full w-full object-cover object-center">
                                </div>

                                {{-- Detail --}}
                                <div class="flex flex-1 flex-col justify-between">
                                    <div>
                                        <div class="flex justify-between">
                                            <h3 class="text-lg font-semibold text-gray-900">
                                                {{ $item->product->nama_produk }}
                                            </h3>
                                            <p class="ml-4 text-lg font-bold text-[#700207]">
                                                Rp {{ number_format($item->custom_price ?? $item->product->harga, 0, ',', '.') }}
                                            </p>
                                        </div>
                                        @if ($item->custom_deskripsi)
                                            <p class="mt-1 text-sm text-gray-500">{{ $item->custom_deskripsi }}</p>
                                        @else
                                            <p class="mt-1 text-sm text-gray-500">{{ $item->product->jenis->jenis_produk ?? 'Kue Lezat' }}</p>
                                        @endif
                                    </div>

                                    {{-- Kontrol Kuantitas --}}
                                    <div class="flex items-end justify-between text-sm mt-4">
                                        <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                            <button onclick="updateQuantity('{{ $item->id }}', -1)" class="px-3 py-2 bg-gray-50 hover:bg-gray-200">-</button>
                                            <input type="number" id="qty-{{ $item->id }}" value="{{ $item->jumlah }}" class="w-12 text-center border-none p-1 font-medium bg-white" readonly>
                                            <button onclick="updateQuantity('{{ $item->id }}', 1)" class="px-3 py-2 bg-gray-50 hover:bg-gray-200">+</button>
                                        </div>
                                        <button type="button" onclick="removeItem('{{ $item->id }}')" class="text-gray-400 hover:text-red-600">
                                            <i class="fa fa-trash-alt text-lg"></i>
                                        </button>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- BAGIAN KANAN: RINGKASAN --}}
            <div class="lg:w-80">
                <div class="bg-white shadow-sm rounded-2xl p-6 border border-gray-100 sticky top-24">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Ringkasan Belanja</h2>
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-gray-600">Total (<span id="selected-count">0</span> barang)</span>
                        <span class="text-xl font-bold text-[#700207]" id="display-total">Rp 0</span>
                    </div>
                    
                    <button onclick="proceedToCheckout()" 
                            id="checkout-btn"
                            class="w-full flex justify-center items-center gap-2 bg-gray-300 text-gray-500 cursor-not-allowed px-6 py-3 rounded-xl font-bold transition duration-300"
                            disabled>
                        Checkout
                        <i class="fa fa-arrow-right text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
        @else
            <div class="text-center py-20 bg-white rounded-2xl shadow-sm border border-gray-100">
                <i class="fa fa-shopping-basket text-6xl text-gray-200 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900">Keranjang kosong</h3>
                <a href="{{ route('customer.produk.list') }}" class="mt-4 inline-block px-6 py-3 bg-[#700207] text-white font-semibold rounded-xl">Mulai Belanja</a>
            </div>
        @endif
    </div>
</div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // 1. Hitung Ulang Total Berdasarkan Checkbox
    function recalculateTotal() {
        let total = 0;
        let count = 0;
        const checkboxes = document.querySelectorAll('.cart-item-checkbox:checked');
        const checkoutBtn = document.getElementById('checkout-btn');

        checkboxes.forEach(box => {
            const price = parseInt(box.dataset.price);
            const qty = parseInt(box.dataset.qty);
            total += price * qty;
            count++;
        });

        document.getElementById('display-total').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        document.getElementById('selected-count').innerText = count;

        // Enable/Disable Tombol Checkout
        if (count > 0) {
            checkoutBtn.disabled = false;
            checkoutBtn.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
            checkoutBtn.classList.add('bg-[#700207]', 'text-white', 'hover:bg-[#5a0105]', 'shadow-sm');
        } else {
            checkoutBtn.disabled = true;
            checkoutBtn.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
            checkoutBtn.classList.remove('bg-[#700207]', 'text-white', 'hover:bg-[#5a0105]', 'shadow-sm');
        }
    }

    // 2. Pilih Semua Checkbox
    function toggleSelectAll(source) {
        const checkboxes = document.querySelectorAll('.cart-item-checkbox');
        checkboxes.forEach(box => {
            box.checked = source.checked;
        });
        recalculateTotal();
    }

    // 3. Lanjut ke Checkout (Kirim ID yang dipilih via URL)
    function proceedToCheckout() {
        const checkboxes = document.querySelectorAll('.cart-item-checkbox:checked');
        const selectedIds = Array.from(checkboxes).map(box => box.value);

        if (selectedIds.length === 0) return;

        // Redirect ke halaman checkout dengan parameter ID
        window.location.href = `{{ route('checkout') }}?selected_ids=${selectedIds.join(',')}`;
    }

    // 4. Update Quantity (AJAX) - Updated untuk Checkbox
    function updateQuantity(id, change) {
        const input = document.getElementById(`qty-${id}`);
        const checkbox = document.querySelector(`.cart-item-checkbox[value="${id}"]`);
        let currentQty = parseInt(input.value);
        let newQty = currentQty + change;

        if (newQty < 1) return;

        input.value = newQty;
        // Update data attribute di checkbox agar kalkulasi benar
        checkbox.dataset.qty = newQty;
        recalculateTotal(); // Hitung ulang langsung

        fetch(`{{ url('/keranjang/update') }}/${id}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ jumlah: newQty })
        }).catch(err => console.error(err));
    }

    // 5. Hapus Item (AJAX)
    function removeItem(id) {
        if(!confirm('Hapus item ini?')) return;
        
        fetch(`{{ url('/keranjang/hapus') }}/${id}`, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' }
        }).then(res => res.json()).then(data => {
            if(data.status === 'success') {
                document.getElementById(`row-${id}`).remove();
                recalculateTotal();
                if(document.querySelectorAll('#cart-list li').length === 0) location.reload();
            }
        });
    }
</script>
@endsection