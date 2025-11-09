@extends('layouts.navbar')
@section('navProduk', 'active')
@section('title', 'Daftar Produk Kami')
@section('content')

{{-- HERO SECTION --}}
<section class="relative py-32 font-['Poppins'] text-white text-center bg-cover bg-center bg-no-repeat" 
    style="background-image: url('{{ asset('gambar/77.jpg') }}');">

    <!-- Overlay Gelap -->
    <div class="absolute inset-0 bg-black/40"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-6">
        <h1 class="text-5xl md:text-6xl font-['Playfair_Display'] font-extrabold tracking-wide">
            Shop
        </h1>
        <p class="mt-4 text-[#F8EAEA] text-lg md:text-xl leading-relaxed">
            Temukan berbagai pilihan kue terbaik kami yang dibuat dengan bahan berkualitas dan penuh rasa.
        </p>
    </div>
</section>



{{-- CONTENT CONTAINER --}}
<div class="bg-[#ECE6DA] min-h-screen py-24 px-6 sm:px-10 lg:px-16">

    {{-- FILTER & SEARCH --}}
    <div class="max-w-6xl mx-auto mb-14">
        <div class="flex flex-col lg:flex-row gap-5 items-center justify-between bg-[#ECE6DA] p-6 rounded-2xl shadow-sm border border-[#79533E]">

            {{-- Search --}}
            <div class="w-full lg:w-1/2">
                <div class="relative">
                    <input type="text" id="productSearchInput"
                        class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-[#A83F3F] bg-white placeholder-gray-400 transition"
                        placeholder="Cari produk kesukaanmu...">
                    
                    <svg class="w-5 h-5 text-gray-500 absolute top-1/2 -translate-y-1/2 left-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            {{-- Dropdown Filter --}}
            <div class="w-full lg:w-1/2">
                <div class="relative">
                    <button id="dropdownToggle"
                        class="w-full flex items-center justify-between px-6 py-3 rounded-full border border-gray-300 bg-white text-gray-700 text-sm font-medium hover:border-[#A83F3F] hover:text-[#A83F3F] transition shadow-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2H3V4zm0 6h18v2H3v-2zm0 6h12v2H3v-2z" />
                            </svg>
                            <span id="selectedFilterText">Semua Kategori</span>
                        </div>

                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div id="dropdownMenu"
                        class="absolute z-10 mt-2 w-full bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden hidden">
                        
                        <button class="filter-btn block w-full text-left px-5 py-2.5 text-sm hover:bg-gray-100 transition" data-filter="all">
                            Semua Kategori
                        </button>

                        @foreach($categories as $category)
                        <button class="filter-btn block w-full text-left px-5 py-2.5 text-sm hover:bg-gray-100 transition"
                            data-filter="{{ $category->id }}">
                            {{ $category->jenis_produk }}
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>



    {{-- GRID PRODUK --}}
    @if($products->count() > 0)
    <div id="productList" class="max-w-7xl mx-auto grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-12">

        @foreach ($products as $product)
        <a href="{{ route('customer.produk.detail', $product->id) }}"
           class="product-col bg-white rounded-2xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 p-6 group text-center border border-gray-100"
           data-nama="{{ strtolower($product->nama_produk) }}"
           data-kategori="{{ $product->jenis_id }}">

            {{-- Gambar --}}
            <div class="w-full h-48 flex items-center justify-center overflow-hidden rounded-xl bg-white">
                <img src="{{ asset('storage/produk/' . $product->gambar) }}"
                     alt="{{ $product->nama_produk }}"
                     class="h-full w-auto transition-transform duration-500 group-hover:scale-105">
            </div>

            {{-- Nama --}}
            <p class="mt-6 text-base font-semibold text-gray-800 group-hover:text-[#A83F3F] transition">
                {{ $product->nama_produk }}
            </p>

            {{-- Harga --}}
            <p class="text-[#A83F3F] font-bold text-lg mt-2">
                Rp {{ number_format($product->harga, 0, ',', '.') }}
            </p>

            {{-- Plus --}}
            <div class="mt-6 flex justify-center">
                <div class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 
                            hover:bg-[#A83F3F] hover:text-white transition text-lg">
                    +
                </div>
            </div>
        </a>
        @endforeach
    </div>

    {{-- PAGINATION --}}
    <div class="mt-16 flex justify-center">
        {{ $products->links() }}
    </div>

    @else
    <div class="text-center py-20 text-gray-600 text-lg">
        😔 Belum ada produk untuk ditampilkan.
    </div>
    @endif

</div>



{{-- SCRIPT FILTER --}}
<script>
    const searchInput = document.getElementById('productSearchInput');
    const productList = document.querySelectorAll('#productList .product-col');
    const filterButtons = document.querySelectorAll('.filter-btn');
    let activeFilter = 'all';

    function filterProducts() {
        const searchTerm = searchInput.value.toLowerCase();
        productList.forEach(product => {
            const name = product.dataset.nama;
            const category = product.dataset.kategori;
            product.style.display =
                (name.includes(searchTerm) && (activeFilter === 'all' || activeFilter === category))
                ? '' : 'none';
        });
    }

    searchInput.addEventListener('keyup', filterProducts);

    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            activeFilter = btn.dataset.filter;
            filterProducts();
        });
    });
</script>

{{-- SCRIPT DROPDOWN --}}
<script>
    const dropdownToggle = document.getElementById('dropdownToggle');
    const dropdownMenu = document.getElementById('dropdownMenu');
    const selectedText = document.getElementById('selectedFilterText');

    dropdownToggle.addEventListener('click', () => dropdownMenu.classList.toggle('hidden'));

    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            selectedText.textContent = btn.textContent.trim();
            dropdownMenu.classList.add('hidden');
        });
    });

    document.addEventListener('click', (e) => {
        if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.classList.add('hidden');
        }
    });
</script>

@endsection
