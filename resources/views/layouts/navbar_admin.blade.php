<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Dara Cake</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #888; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #555; }
    </style>
</head>
<body class="bg-gray-100 font-sans">

    <div class="flex h-screen bg-gray-200">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-pink-600 to-pink-800 text-white flex-shrink-0 hidden md:block shadow-xl">
            <div class="p-5 text-2xl font-extrabold tracking-wide border-b border-pink-500">
                🎂 DARA CAKE
            </div>
            <nav class="mt-4 space-y-1">
                <a href="/dashboard-admin" class="flex items-center px-5 py-3 bg-pink-700 text-white rounded-lg">
                    <i class="fas fa-tachometer-alt w-6 text-center"></i>
                    <span class="mx-3 font-medium">Dashboard</span>
                </a>
                <a href="/daftar-produk" class="flex items-center px-5 py-3 text-pink-100 hover:bg-pink-600 hover:text-white rounded-lg transition">
                    <i class="fas fa-box-open w-6 text-center"></i>
                    <span class="mx-3 font-medium">Produk</span>
                </a>
                <a href="/category" class="flex items-center px-5 py-3 text-pink-100 hover:bg-pink-600 hover:text-white rounded-lg transition">
                    <i class="fas fa-tags w-6 text-center"></i>
                    <span class="mx-3 font-medium">Kategori</span>
                </a>
                <a href="{{ route('admin.pesanan.index') }}" class="flex items-center px-5 py-3 text-pink-100 hover:bg-pink-600 hover:text-white rounded-lg transition">
                    <i class="fas fa-box w-6 text-center"></i>
                    <span class="mx-3 font-medium">Pesanan</span>
                </a>

                <a href="#" class="flex items-center px-5 py-3 text-pink-100 hover:bg-pink-600 hover:text-white rounded-lg transition">
                    <i class="fas fa-chart-line w-6 text-center"></i>
                    <span class="mx-3 font-medium">Laporan</span>
                </a>
                <a href="#" class="flex items-center px-5 py-3 text-pink-100 hover:bg-pink-600 hover:text-white rounded-lg transition">
                    <i class="fas fa-cog w-6 text-center"></i>
                    <span class="mx-3 font-medium">Pengaturan</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="flex justify-between items-center px-6 py-4 bg-white shadow-md">
                <div class="flex items-center">
                    <button class="text-gray-500 focus:outline-none md:hidden">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="text-2xl font-bold text-gray-700 ml-4">Dashboard</h1>
                </div>

                <!-- User Dropdown -->
                <div x-data="{ dropdownOpen: false }" class="relative">
                    <button @click="dropdownOpen = !dropdownOpen"
                        class="flex items-center space-x-3 focus:outline-none">
                        <img src="https://i.pravatar.cc/150?u=a042581f4e29026704d" alt="Admin"
                            class="w-10 h-10 rounded-full border-2 border-pink-500 shadow">
                        <span class="hidden md:block font-semibold text-gray-700">Admin Dara</span>
                        <i class="fas fa-chevron-down text-sm hidden md:block text-gray-600"></i>
                    </button>

                    <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                        class="absolute right-0 mt-3 w-52 bg-white rounded-md overflow-hidden shadow-lg z-10"
                        x-transition>
                        <a href="#" class="block px-5 py-3 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
                        <a href="#" class="block px-5 py-3 text-sm text-gray-700 hover:bg-gray-100">Pengaturan</a>
                        
                        <!-- Logout Button -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                class="w-full text-left px-5 py-3 text-sm text-red-600 font-semibold hover:bg-red-50">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
