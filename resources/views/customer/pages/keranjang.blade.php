@extends('layouts.navbar')

@section('title', 'Keranjang Belanja')

@section('content')
{{-- Meta CSRF Token --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 font-sans">

    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Keranjang Belanja</h1>

        {{-- Alert Error --}}
        <div id="alert-container" class="hidden fixed top-24 right-5 z-50">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline" id="alert-message"></span>
            </div>
        </div>

        {{-- PERUBAHAN 1: Ganti $cart menjadi $cartItems --}}
        @if($cartItems->count() > 0)
        <div class="flex flex-col lg:flex-row gap-8" id="cart-container">
            
            {{-- BAGIAN KIRI: LIST ITEM --}}
            <div class="flex-1">
                <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-gray-100">
                    <ul class="divide-y divide-gray-100" id="cart-list">
                        {{-- PERUBAHAN 2: Loop $cartItems sebagai object --}}
                        @foreach($cartItems as $item)
                            <li class="p-6 flex gap-4 sm:gap-6 transition hover:bg-gray-50/50" id="row-{{ $item->id }}">
                                
                                {{-- Gambar --}}
                                <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-xl border border-gray-200 bg-gray-100">
                                    {{-- PERUBAHAN 3: Akses data via relasi ->product --}}
                                    <img src="{{ asset('storage/produk/' . $item->product->gambar) }}" 
                                         alt="{{ $item->product->nama_produk }}" 
                                         class="h-full w-full object-cover object-center">
                                </div>

                                {{-- Detail --}}
                                <div class="flex flex-1 flex-col justify-between">
                                    <div>
                                        <div class="flex justify-between">
                                            <h3 class="text-lg font-semibold text-gray-900">
                                                <a href="#">{{ $item->product->nama_produk }}</a>
                                            </h3>
                                            <p class="ml-4 text-lg font-bold text-[#700207]">
                                                Rp {{ number_format($item->product->harga, 0, ',', '.') }}
                                            </p>
                                        </div>
                                        
                                        {{-- Deskripsi Custom --}}
                                        @if ($item->custom_deskripsi)
                                            <p class="mt-1 text-sm text-gray-500">{{ $item->custom_deskripsi }}</p>
                                        @else
                                            <p class="mt-1 text-sm text-gray-500">{{ $item->product->jenis->jenis_produk ?? 'Kue Lezat' }}</p>
                                        @endif
                                    </div>

                                    {{-- Kontrol Kuantitas & Hapus --}}
                                    <div class="flex items-end justify-between text-sm mt-4">
                                        
                                        {{-- Input Jumlah --}}
                                        <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                            <button onclick="updateQuantity('{{ $item->id }}', -1)" 
                                                    class="px-3 py-2 text-gray-600 bg-gray-50 hover:bg-gray-200 transition active:bg-gray-300">
                                                -
                                            </button>
                                            
                                            <input type="number" 
                                                   id="qty-{{ $item->id }}" 
                                                   value="{{ $item->jumlah }}" 
                                                   min="1" 
                                                   class="w-12 text-center border-none focus:ring-0 p-1 text-gray-900 font-medium bg-white"
                                                   readonly>
                                            
                                            <button onclick="updateQuantity('{{ $item->id }}', 1)" 
                                                    class="px-3 py-2 text-gray-600 bg-gray-50 hover:bg-gray-200 transition active:bg-gray-300">
                                                +
                                            </button>
                                        </div>

                                        {{-- Tombol Hapus --}}
                                        <button type="button" onclick="removeItem('{{ $item->id }}')" 
                                                class="flex items-center gap-1 text-gray-400 hover:text-red-600 font-medium transition duration-200 group">
                                            <i class="fa fa-trash-alt text-lg group-hover:scale-110 transition-transform"></i>
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
                    
                    <div class="flow-root">
                        <dl class="-my-4 text-sm divide-y divide-gray-100">
                            <div class="py-4 flex items-center justify-between">
                                <dt class="text-gray-600">Subtotal</dt>
                                <dd class="font-medium text-gray-900 total-price-display">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </dd>
                            </div>
                            <div class="py-4 flex items-center justify-between border-t border-gray-100">
                                <dt class="text-base font-bold text-gray-900">Total</dt>
                                <dd class="text-xl font-bold text-[#700207] total-price-display">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('checkout') }}" 
                           class="w-full flex justify-center items-center gap-2 bg-[#700207] border border-transparent rounded-xl px-6 py-3 text-base font-bold text-white shadow-sm hover:bg-[#5a0105] transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#700207] transform active:scale-95">
                            Checkout
                            <i class="fa fa-arrow-right text-sm"></i>
                        </a>
                    </div>
                    
                    <div class="mt-6 text-center text-sm text-gray-500">
                        <p>atau <a href="{{ route('customer.produk.list') }}" class="font-medium text-[#700207] hover:text-[#5a0105]">Lanjut Belanja<span aria-hidden="true"> &rarr;</span></a></p>
                    </div>
                </div>
            </div>

        </div>
        
        {{-- State Kosong (Hidden by default) --}}
        <div id="empty-state" class="hidden text-center py-20 bg-white rounded-2xl shadow-sm border border-gray-100">
            <i class="fa fa-shopping-basket text-6xl text-gray-200 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900">Keranjang kamu kosong</h3>
            <p class="text-gray-500 mt-1 mb-6">Yuk, isi dengan kue-kue manis favoritmu!</p>
            <a href="{{ route('customer.produk.list') }}" class="inline-block px-6 py-3 bg-[#700207] text-white font-semibold rounded-xl hover:bg-[#5a0105] transition shadow-lg hover:shadow-xl hover:-translate-y-1">
                Mulai Belanja
            </a>
        </div>

        @else
            {{-- State Kosong (Initial) --}}
            <div class="text-center py-20 bg-white rounded-2xl shadow-sm border border-gray-100">
                <i class="fa fa-shopping-basket text-6xl text-gray-200 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900">Keranjang kamu kosong</h3>
                <p class="text-gray-500 mt-1 mb-6">Yuk, isi dengan kue-kue manis favoritmu!</p>
                <a href="{{ route('customer.produk.list') }}" class="inline-block px-6 py-3 bg-[#700207] text-white font-semibold rounded-xl hover:bg-[#5a0105] transition shadow-lg hover:shadow-xl hover:-translate-y-1">
                    Mulai Belanja
                </a>
            </div>
        @endif
    </div>
</div>

{{-- SCRIPT AJAX (TETAP SAMA SEPERTI SEBELUMNYA) --}}
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function showError(message) {
        const alertBox = document.getElementById('alert-container');
        document.getElementById('alert-message').innerText = message;
        alertBox.classList.remove('hidden');
        setTimeout(() => {
            alertBox.classList.add('hidden');
        }, 3000);
    }

    function updateQuantity(id, change) {
        const input = document.getElementById(`qty-${id}`);
        let currentQty = parseInt(input.value);
        let newQty = currentQty + change;

        if (newQty < 1) return;

        input.value = newQty;

        fetch(`{{ url('/keranjang/update') }}/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ jumlah: newQty })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.querySelectorAll('.total-price-display').forEach(el => {
                    el.innerText = 'Rp ' + data.formatted_total;
                });
            } else {
                input.value = currentQty;
                showError(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            input.value = currentQty;
            showError('Terjadi kesalahan koneksi.');
        });
    }

    function removeItem(id) {
        if(!confirm('Yakin ingin menghapus kue ini dari keranjang?')) return;

        const row = document.getElementById(`row-${id}`);
        
        row.style.opacity = '0.5';
        row.style.pointerEvents = 'none';

        fetch(`{{ url('/keranjang/hapus') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                row.remove();
                document.querySelectorAll('.total-price-display').forEach(el => {
                    el.innerText = 'Rp ' + data.formatted_total;
                });

                if (data.cart_count === 0) {
                    document.getElementById('cart-container').remove();
                    document.getElementById('empty-state').classList.remove('hidden');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            row.style.opacity = '1';
            row.style.pointerEvents = 'auto';
            showError('Gagal menghapus item.');
        });
    }
</script>
@endsection