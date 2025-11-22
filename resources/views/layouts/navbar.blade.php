<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dara Cake' }}</title>

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brandDark: '#200B06',
                        brandCream: '#ECE6DA',
                        brandAccent: '#ECCFC3',
                        brandRed: '#700207',
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                },
            },
        }
    </script>

    {{-- Additional Style --}}
    <style>
        html { scroll-behavior: smooth; }
        .nav-link {
            position: relative;
            padding-bottom: 4px;
            transition: color .3s ease;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 0;
            height: 2px;
            background: #ECCFC3;
            transition: .3s;
        }
        .nav-link:hover::after, .nav-link.active::after {
            width: 100%;
        }
    </style>
</head>

<body class="font-sans bg-white text-brandDark overflow-x-hidden">

    {{-- NAVBAR --}}
    <nav class="fixed top-0 w-full z-50 backdrop-blur-md bg-brandCream/90 shadow-sm transition-all">
        {{-- Perubahan: px-4 di mobile, px-8 di desktop --}}
        <div class="max-w-7xl mx-auto flex items-center justify-between px-4 md:px-8 py-3 md:py-4">

            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2 md:gap-3 shrink-0">
                {{-- Gambar Logo: Lebih kecil di mobile (w-10) --}}
                <img src="{{ asset('gambar/5.png') }}" class="w-9 h-9 md:w-12 md:h-12 rounded-full shadow object-cover">
                {{-- Teks Logo: Lebih kecil di mobile (text-xl) --}}
                <span class="text-brandRed font-serif text-xl md:text-2xl font-semibold tracking-wide">DaraCake</span>
            </a>

            {{-- Menu Desktop (Hidden di HP) --}}
            <ul class="hidden md:flex items-center space-x-8 lg:space-x-10 text-brandDark font-medium text-sm lg:text-base">
                <li><a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ route('customer.produk.list') }}" class="nav-link {{ request()->is('produk*') ? 'active' : '' }}">Shop</a></li>
                <li><a href="{{ route('custom-cake.index') }}" class="nav-link">Custom Cake</a></li>
            </ul>

            {{-- User + Cart + Mobile Button --}}
            {{-- Perubahan: Gap antar icon diperkecil di mobile --}}
            <div class="flex items-center gap-3 md:gap-5 text-lg md:text-xl text-brandRed">

                @auth
                    <div class="relative">
                        <button id="userDropdownBtn" class="flex items-center gap-2 hover:opacity-80 focus:outline-none">
                            <i class="fa fa-user"></i>
                            {{-- Nama User: Hidden di mobile agar tidak penuh --}}
                            <span class="text-sm font-medium hidden md:inline">{{ Auth::user()->name }}</span>
                            <i class="fa fa-chevron-down text-xs hidden md:inline"></i>
                        </button>
                        
                        {{-- Dropdown Menu --}}
                        <div id="userDropdownMenu" class="hidden absolute right-0 mt-4 w-48 bg-white text-brandDark border border-gray-100 rounded-xl shadow-xl py-2 text-sm z-50 animate-fade-in-down">
                            <div class="px-4 py-2 border-b border-gray-100 md:hidden">
                                <span class="font-bold text-brandRed">{{ Auth::user()->name }}</span>
                            </div>
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 hover:bg-brandCream/50 hover:text-brandRed transition"><i class="fa fa-id-card mr-2"></i> Profile</a>
                            <a href="{{ route('customer.pesanan.index') }}" class="block px-4 py-2 hover:bg-brandCream/50 hover:text-brandRed transition"><i class="fa fa-box mr-2"></i> Pesanan Saya</a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 transition"><i class="fa fa-sign-out-alt mr-2"></i> Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="hover:opacity-80 text-sm md:text-base font-medium border border-brandRed px-3 py-1 rounded-full transition hover:bg-brandRed hover:text-white">
                        Login
                    </a>
                @endauth

                <a href="{{ route('keranjang.index') }}" class="relative hover:opacity-80">
                    <i class="fa fa-shopping-cart"></i>
                    {{-- Badge Keranjang (Opsional: Tambahkan logika count di sini nanti) --}}
                    @if(session('cart') && count(session('cart')) > 0)
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] font-bold w-4 h-4 flex items-center justify-center rounded-full">
                            {{ count(session('cart')) }}
                        </span>
                    @endif
                </a>

                {{-- Tombol Hamburger (Hanya di HP) --}}
                <button id="mobileMenuBtn" class="md:hidden hover:opacity-80 focus:outline-none ml-1">
                    <i class="fa fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        {{-- MENU MOBILE (Dropdown Full Width) --}}
        <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-gray-100 shadow-lg absolute w-full left-0 top-full z-40">
            <ul class="flex flex-col p-4 space-y-2 font-medium text-brandDark">
                <li>
                    <a href="/" class="block px-4 py-3 rounded-lg hover:bg-brandCream/50 hover:text-brandRed {{ request()->is('/') ? 'bg-brandCream/30 text-brandRed' : '' }}">
                        <i class="fa fa-home w-6"></i> Home
                    </a>
                </li>
                <li>
                    <a href="{{ route('customer.produk.list') }}" class="block px-4 py-3 rounded-lg hover:bg-brandCream/50 hover:text-brandRed {{ request()->is('produk*') ? 'bg-brandCream/30 text-brandRed' : '' }}">
                        <i class="fa fa-store w-6"></i> Shop
                    </a>
                </li>
                <li>
                    <a href="{{ route('custom-cake.index') }}" class="block px-4 py-3 rounded-lg hover:bg-brandCream/50 hover:text-brandRed">
                        <i class="fa fa-birthday-cake w-6"></i> Custom Cake
                    </a>
                </li>
            </ul>
        </div>
    </nav>


    {{-- MAIN CONTENT --}}
    {{-- Perubahan: Padding top disesuaikan agar tidak tertutup navbar --}}
    <main class="pt-[72px] md:pt-[88px]">
        @yield('content')
    </main>


    {{-- Floating WhatsApp --}}
    <a href="https://wa.me/62895611194900?text=Halo,%20saya%20ingin%20memesan%20produk%20Anda."
       class="fixed w-12 h-12 md:w-14 md:h-14 bottom-5 right-5 md:bottom-6 md:right-6 bg-green-500 text-white rounded-full grid place-items-center text-2xl md:text-3xl shadow-xl hover:scale-110 transition z-50"
       target="_blank">
       <i class="fab fa-whatsapp"></i>
    </a>


    {{-- Script --}}
    <script>
        // Logic Dropdown User
        const userBtn = document.getElementById('userDropdownBtn');
        const userMenu = document.getElementById('userDropdownMenu');
        
        if(userBtn){
            userBtn.addEventListener('click', (e) => {
                e.stopPropagation(); 
                userMenu.classList.toggle('hidden');
            });
        }

        // Logic Mobile Menu (Hamburger)
        const mobileBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');

        if(mobileBtn){
            mobileBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                mobileMenu.classList.toggle('hidden');
                // Ganti icon bars ke times (X) jika menu terbuka (opsional visual feedback)
                const icon = mobileBtn.querySelector('i');
                if(mobileMenu.classList.contains('hidden')){
                     icon.classList.remove('fa-times');
                     icon.classList.add('fa-bars');
                } else {
                     icon.classList.remove('fa-bars');
                     icon.classList.add('fa-times');
                }
            });
        }

        // Klik di luar menu untuk menutup menu
        document.addEventListener('click', (e) => {
            if(userBtn && !userBtn.contains(e.target) && !userMenu.contains(e.target)){
                userMenu.classList.add('hidden');
            }
            if(mobileBtn && !mobileBtn.contains(e.target) && !mobileMenu.contains(e.target)){
                mobileMenu.classList.add('hidden');
                // Reset icon
                if(mobileBtn) {
                    const icon = mobileBtn.querySelector('i');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        });
    </script>

</body>
</html>