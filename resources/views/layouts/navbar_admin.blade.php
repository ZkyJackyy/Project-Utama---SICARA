<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - Dara Cake</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c8a8a0; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #b18f87; }
    </style>
</head>
<body class="bg-[#F4ECE9] font-sans">

    <div class="flex h-screen">
        <aside class="w-72 bg-gradient-to-b from-[#ECCFC3] to-[#4a0105] text-white shadow-2xl hidden md:block">
            <div class="p-6 flex flex-col items-center border-b border-white/20">
                <img src="/gambar/5.png" alt="Logo" class="h-20 mb-3 drop-shadow-lg">
                <h1 class="text-3xl font-extrabold tracking-wide">DARA CAKE</h1>
            </div>

            <nav class="mt-6 space-y-1 px-3">
    {{-- Dashboard --}}
    <a href="/dashboard-admin"
       class="flex items-center px-5 py-3 rounded-xl
       {{ request()->is('dashboard-admin') ? 'bg-[#4a0105] shadow-md text-white' : 'text-gray-200 hover:bg-white/10' }}">
        <i class="fas fa-tachometer-alt w-6"></i>
        <span class="ml-3 text-lg font-medium">Dashboard</span>
    </a>

    {{-- Produk --}}
    <a href="/daftar-produk"
       class="flex items-center px-5 py-3 rounded-xl
       {{ request()->is('daftar-produk') ? 'bg-[#4a0105] shadow-md text-white' : 'text-gray-200 hover:bg-white/10' }}">
        <i class="fas fa-box-open w-6"></i>
        <span class="ml-3 text-lg font-medium">Produk</span>
    </a>

    {{-- Kategori --}}
    <a href="/category"
       class="flex items-center px-5 py-3 rounded-xl
       {{ request()->is('category') ? 'bg-[#4a0105] shadow-md text-white' : 'text-gray-200 hover:bg-white/10' }}">
        <i class="fas fa-tags w-6"></i>
        <span class="ml-3 text-lg font-medium">Kategori</span>
    </a>

    {{-- Pesanan --}}
    <a href="{{ route('admin.pesanan.index') }}"
       class="flex items-center px-5 py-3 rounded-xl
       {{ request()->is('admin/pesanan*') ? 'bg-[#4a0105] shadow-md text-white' : 'text-gray-200 hover:bg-white/10' }}">
        <i class="fas fa-box w-6"></i>
        <span class="ml-3 text-lg font-medium">Pesanan</span>
    </a>

    {{-- Laporan --}}
    <a href="/laporan"
       class="flex items-center px-5 py-3 rounded-xl
       {{ request()->is('laporan') ? 'bg-[#4a0105] shadow-md text-white' : 'text-gray-200 hover:bg-white/10' }}">
        <i class="fas fa-chart-line w-6"></i>
        <span class="ml-3 text-lg font-medium">Laporan Penjualan</span>
    </a>

    {{-- Pengaturan --}}
    <a href="/keuangan"
       class="flex items-center px-5 py-3 rounded-xl
       {{ request()->is('pengaturan') ? 'bg-[#4a0105] shadow-md text-white' : 'text-gray-200 hover:bg-white/10' }}">
        <i class="fas fa-chart-line w-6"></i>
        <span class="ml-3 text-lg font-medium">Laporan Keuangan</span>
    </a>
</nav>

        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="flex justify-between items-center px-6 py-4 bg-white shadow-lg border-b border-[#ECCFC3]">
                <div class="flex items-center">
                    <button class="text-gray-600 focus:outline-none md:hidden">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 class="text-2xl font-bold text-gray-700 ml-4">Dashboard</h1>
                </div>

                <div x-data="{ dropdownOpen: false }" class="relative">
                    <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-3">
                        <img src="https://i.pravatar.cc/150?u=a042581f4e29026704d" alt="Admin"
                             class="w-11 h-11 rounded-full border-2 border-[#4a0105] shadow-md">
                        <span class="hidden md:block font-semibold text-gray-700">Admin Dara</span>
                        <i class="fas fa-chevron-down text-sm hidden md:block text-gray-600"></i>
                    </button>

                    <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                         class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-xl overflow-hidden z-20"
                         x-transition>
                        <a href="#" class="block px-5 py-3 text-gray-700 hover:bg-gray-100">Profil</a>
                        <a href="#" class="block px-5 py-3 text-gray-700 hover:bg-gray-100">Pengaturan</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-5 py-3 text-red-600 font-semibold hover:bg-red-50">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-8 bg-[#F4ECE9]">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>