<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - Dara Cake</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Alpine.js Wajib Ada --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    {{-- JQuery (Untuk AJAX Notifikasi) --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c8a8a0; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #b18f87; }
    </style>
</head>

<body class="bg-[#F4ECE9] font-sans" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen">

        {{-- OVERLAY GELAP (Backdrop) untuk Mobile --}}
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-20 bg-black bg-opacity-50 md:hidden">
        </div>

        {{-- SIDEBAR --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 z-30 w-72 bg-gradient-to-b from-[#ECCFC3] to-[#4a0105] text-white shadow-2xl transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-0">
            
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
                   class="relative flex items-center px-5 py-3 rounded-xl
                   {{ request()->is('admin/pesanan*') ? 'bg-[#4a0105] shadow-md text-white' : 'text-gray-200 hover:bg-white/10' }}">
                    <i class="fas fa-box w-6"></i>
                    <span class="ml-3 text-lg font-medium">Pesanan</span>
                </a>

                {{-- Layanan Bantuan --}}
                <a href="{{ route('tickets.index') }}"
                   class="relative flex items-center px-5 py-3 rounded-xl transition-all duration-200
                   {{ request()->routeIs('tickets*') ? 'bg-[#4a0105] shadow-md text-white' : 'text-gray-200 hover:bg-white/10 hover:translate-x-1' }}">
                    <i class="fas fa-headset w-6"></i>
                    <span class="ml-3 text-lg font-medium">Layanan Bantuan</span>
                    
                    @php
                        $openTicketCount = \App\Models\Tiket::where('status', 'open')->count();
                    @endphp
                    @if($openTicketCount > 0)
                        <span class="absolute right-4 top-3 bg-red-600 border border-[#4a0105] text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">
                            {{ $openTicketCount }}
                        </span>
                    @endif
                </a>

                {{-- Laporan --}}
                <a href="/laporan"
                   class="flex items-center px-5 py-3 rounded-xl
                   {{ request()->is('laporan') ? 'bg-[#4a0105] shadow-md text-white' : 'text-gray-200 hover:bg-white/10' }}">
                    <i class="fas fa-chart-line w-6"></i>
                    <span class="ml-3 text-lg font-medium">Laporan Penjualan</span>
                </a>

                {{-- Keuangan --}}
                <a href="/keuangan"
                   class="flex items-center px-5 py-3 rounded-xl
                   {{ request()->is('keuangan') ? 'bg-[#4a0105] shadow-md text-white' : 'text-gray-200 hover:bg-white/10' }}">
                    <i class="fas fa-file-invoice-dollar w-6"></i>
                    <span class="ml-3 text-lg font-medium">Laporan Keuangan</span>
                </a>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="flex justify-between items-center px-6 py-4 bg-white shadow-lg border-b border-[#ECCFC3]">
                <div class="flex items-center">
                    {{-- TOMBOL HAMBURGER --}}
                    <button @click="sidebarOpen = true" class="text-gray-600 focus:outline-none md:hidden hover:text-[#4a0105]">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                    
                    <h1 class="text-2xl font-bold text-gray-700 ml-4">Dashboard</h1>
                </div>

                <div class="flex items-center gap-6">
                    
                    {{-- === NOTIFIKASI LONCENG === --}}
                    <div class="relative" id="notif-container">
                        <button id="notif-btn" class="relative p-2 text-gray-500 hover:text-[#700207] transition focus:outline-none">
                            <i class="fas fa-bell text-xl"></i>
                            {{-- Badge Merah --}}
                            <span id="notif-badge" class="hidden absolute top-0 right-0 bg-red-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full shadow-sm animate-pulse border border-white">
                                0
                            </span>
                        </button>

                        {{-- Dropdown Notif --}}
                        <div id="notif-dropdown" class="hidden absolute right-0 mt-4 w-80 bg-white border border-gray-100 rounded-xl shadow-2xl z-50 overflow-hidden origin-top-right transition-all duration-200">
                            <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                                <h3 class="text-sm font-bold text-gray-800">Pesanan Baru</h3>
                                <span id="notif-count-text" class="text-xs text-[#700207] font-semibold">0 Menunggu</span>
                            </div>
                            <div id="notif-list" class="max-h-64 overflow-y-auto custom-scrollbar">
                                <div class="p-4 text-center text-gray-500 text-xs">Memuat...</div>
                            </div>
                            <a href="{{ route('admin.pesanan.index') }}" class="block text-center py-2 text-xs font-bold text-[#700207] bg-gray-50 hover:bg-red-50 transition border-t border-gray-100">
                                Lihat Semua Pesanan
                            </a>
                        </div>
                    </div>
                    {{-- === END NOTIFIKASI === --}}

                    {{-- PROFIL ADMIN --}}
                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-3 focus:outline-none">
                            <img src="https://i.pravatar.cc/150?u=a042581f4e29026704d" alt="Admin"
                                 class="w-10 h-10 rounded-full border-2 border-[#4a0105] shadow-md object-cover">
                            <span class="hidden md:block font-semibold text-gray-700 text-sm">Admin Dara</span>
                            <i class="fas fa-chevron-down text-xs hidden md:block text-gray-500"></i>
                        </button>

                        <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                             class="absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-xl overflow-hidden z-20 border border-gray-100"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             style="display: none;">
                            
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-bold text-gray-800">Admin Dara</p>
                                <p class="text-xs text-gray-500">Administrator</p>
                            </div>

                            {{-- <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#700207] transition">
                                <i class="far fa-user mr-2 w-4"></i> Profil
                            </a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#700207] transition">
                                <i class="fas fa-cog mr-2 w-4"></i> Pengaturan
                            </a> --}}
                            
                            <div class="border-t border-gray-100 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 font-semibold hover:bg-red-50 transition flex items-center">
                                    <i class="fas fa-sign-out-alt mr-2 w-4"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6 md:p-8 bg-[#F4ECE9]">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- AUDIO NOTIFIKASI --}}
    <audio id="notif-sound" src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" preload="auto"></audio>

    {{-- SCRIPT AJAX NOTIFIKASI --}}
<script>
$(document).ready(function() {
    
    // Variabel state
    let lastCount = 0;
    let isFirstLoad = true;

    const notifBadge = $('#notif-badge');
    const notifList = $('#notif-list');
    const notifCountText = $('#notif-count-text');
    const notifSound = document.getElementById('notif-sound');

    function fetchNotifications() {
        $.ajax({
            url: "{{ route('admin.api.notifications') }}", 
            method: "GET",
            dataType: "json", // Pastikan response dianggap JSON
            success: function(response) {
                console.log("Data Notifikasi:", response); // Debugging: Cek di Console Browser

                const count = response.count;
                const orders = response.orders;

                // 1. Update Badge & Text
                if (count > 0) {
                    notifBadge.text(count).removeClass('hidden');
                    notifCountText.text(count + ' Menunggu');
                } else {
                    notifBadge.addClass('hidden');
                    notifCountText.text('Tidak ada baru');
                }

                // 2. Logika Suara (Hanya jika nambah & bukan load pertama)
                if (!isFirstLoad && count > lastCount && count > 0) {
                    try { 
                        notifSound.currentTime = 0; 
                        notifSound.play(); 
                    } catch(e) { console.log("Audio dicegah browser"); }
                }

                lastCount = count;
                isFirstLoad = false; 

                // 3. Update Dropdown List
                let html = '';
                
                // Pastikan orders adalah array dan tidak kosong
                if (Array.isArray(orders) && orders.length > 0) {
                    orders.forEach(order => {
                        // Tentukan warna badge status biar cantik
                        let statusColor = 'bg-gray-100 text-gray-600';
                        if(order.status === 'Menunggu Konfirmasi') statusColor = 'bg-yellow-100 text-yellow-700';
                        if(order.status === 'Akan Diproses') statusColor = 'bg-blue-100 text-blue-700';

                        html += `
                            <a href="${order.link}" class="block px-4 py-3 border-b border-gray-100 hover:bg-gray-50 transition group">
                                <div class="flex justify-between items-start">
                                    <div class="w-2/3">
                                        <p class="text-xs font-bold text-gray-800 group-hover:text-[#700207] truncate">
                                            #${order.kode}
                                        </p>
                                        <p class="text-[10px] text-gray-500 mt-0.5 truncate">
                                            ${order.customer_name}
                                        </p>
                                    </div>
                                    <div class="text-right w-1/3">
                                        <span class="text-[9px] block text-gray-400 mb-1">${order.time}</span>
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded ${statusColor}">
                                            ${order.status}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        `;
                    });
                } else {
                    html = `
                        <div class="py-8 text-center text-gray-400 flex flex-col items-center">
                            <i class="fas fa-check-circle text-2xl mb-2 text-green-500/50"></i>
                            <span class="text-xs">Semua pesanan aman!</span>
                        </div>
                    `;
                }
                
                notifList.html(html);
            },
            error: function(xhr, status, error) {
                console.error("Gagal ambil notif:", error);
            }
        });
    }

    // Toggle Dropdown
    $('#notif-btn').click(function(e) {
        e.stopPropagation();
        $('#notif-dropdown').toggleClass('hidden');
    });

    // Close Dropdown on Outside Click
    $(document).click(function(e) {
        if (!$(e.target).closest('#notif-container').length) {
            $('#notif-dropdown').addClass('hidden');
        }
    });

    // Jalankan Polling
    fetchNotifications(); 
    setInterval(fetchNotifications, 10000); 
});
</script>

</body>
</html>