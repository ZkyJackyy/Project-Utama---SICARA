<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        /* Custom scrollbar (opsional, untuk estetika) */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">

    <div class="flex h-screen bg-gray-200">
        <aside class="w-64 bg-gray-800 text-white flex-shrink-0 hidden md:block">
            <div class="p-4 text-2xl font-bold border-b border-gray-700">
                DARA CAKE
            </div>
            <nav class="mt-4">
                <a href="/dashboard-admin" class="flex items-center px-4 py-3 bg-gray-700 text-white">
                    <i class="fas fa-tachometer-alt w-6 text-center"></i>
                    <span class="mx-3">Dashboard</span>
                </a>
                <a href="/daftar-produk" class="flex items-center px-4 py-3 mt-2 text-gray-400 hover:bg-gray-700 hover:text-white">
                    <i class="fas fa-box-open w-6 text-center"></i>
                    <span class="mx-3">Produk</span>
                </a>
                <a href="#" class="flex items-center px-4 py-3 mt-2 text-gray-400 hover:bg-gray-700 hover:text-white">
                    <i class="fas fa-shopping-cart w-6 text-center"></i>
                    <span class="mx-3">Pesanan</span>
                </a>
                <a href="#" class="flex items-center px-4 py-3 mt-2 text-gray-400 hover:bg-gray-700 hover:text-white">
                    <i class="fas fa-users w-6 text-center"></i>
                    <span class="mx-3">Pelanggan</span>
                </a>
                <a href="#" class="flex items-center px-4 py-3 mt-2 text-gray-400 hover:bg-gray-700 hover:text-white">
                    <i class="fas fa-chart-line w-6 text-center"></i>
                    <span class="mx-3">Laporan</span>
                </a>
                <a href="#" class="flex items-center px-4 py-3 mt-2 text-gray-400 hover:bg-gray-700 hover:text-white">
                    <i class="fas fa-cog w-6 text-center"></i>
                    <span class="mx-3">Pengaturan</span>
                </a>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="flex justify-between items-center p-4 bg-white border-b">
                <div class="flex items-center">
                    <button class="text-gray-500 focus:outline-none md:hidden">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="text-2xl font-semibold ml-4">Dashboard</h1>
                </div>

                <div x-data="{ dropdownOpen: false }" class="relative">
                    <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-2 relative focus:outline-none">
                        <img src="https://i.pravatar.cc/150?u=a042581f4e29026704d" alt="Admin" class="w-10 h-10 rounded-full">
                        <span class="hidden md:block">Admin Dara</span>
                        <i class="fas fa-chevron-down text-sm hidden md:block"></i>
                    </button>

                    <div x-show="dropdownOpen" @click.away="dropdownOpen = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md overflow-hidden shadow-xl z-10" style="display: none;">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pengaturan</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                @yield('content')
                
            </main>
        </div>
    </div>
</body>
</html>