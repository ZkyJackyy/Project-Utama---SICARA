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
            }
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
    <nav class="fixed top-0 w-full z-50 backdrop-blur-md bg-brandCream/80 shadow-sm transition-all">
        <div class="max-w-7xl mx-auto flex items-center justify-between px-8 py-4">

            {{-- Logo --}}
            <a href="/" class="flex items-center gap-3">
                <img src="{{ asset('gambar/5.png') }}" class="w-12 h-12 rounded-full shadow">
                <span class="text-brandRed font-serif text-2xl font-semibold tracking-wide">DaraCake</span>
            </a>

            {{-- Menu Desktop --}}
            <ul class="hidden md:flex items-center space-x-10 text-brandDark font-medium">
                <li><a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ route('customer.produk.list') }}" class="nav-link {{ request()->is('produk*') ? 'active' : '' }}">Shop</a></li>
                <li><a href="{{ route('custom-cake.index') }}" class="nav-link">Custome Cake</a></li>
                <li><a href="#contact" class="nav-link">Contact</a></li>
            </ul>

            {{-- User + Cart + Mobile --}}
            <div class="flex items-center gap-4 text-xl text-brandRed">

                @auth
                    <div class="relative">
                        <button id="userDropdownBtn" class="flex items-center gap-2 hover:opacity-80">
                            <i class="fa fa-user"></i>
                            <span class="text-sm hidden sm:inline">{{ Auth::user()->name }}</span>
                        </button>
                        <div id="userDropdownMenu" class="hidden absolute right-0 mt-3 w-40 bg-white text-brandDark border border-gray-200 rounded-lg shadow-lg py-2 text-sm">
    <a href="{{ route('profile.show') }}" class="block px-4 py-2 hover:bg-gray-100">Profile</a>

    <!-- Tambahkan ini -->
    <a href="{{ route('customer.pesanan.index') }}" class="block px-4 py-2 hover:bg-gray-100">Pesanan Saya</a>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100">Logout</button>
    </form>
</div>

                    </div>
                @else
                    <a href="{{ route('login') }}" class="hover:opacity-80"><i class="fa fa-user"></i></a>
                @endauth

                <a href="{{ route('keranjang.index') }}" class="hover:opacity-80">
                    <i class="fa fa-shopping-cart"></i>
                </a>

                <button id="mobileMenuBtn" class="md:hidden">
                    <i class="fa fa-bars"></i>
                </button>
            </div>
        </div>
    </nav>


    {{-- MAIN --}}
    <main class="pt-24">
        @yield('content')
    </main>


    {{-- Floating WhatsApp --}}
    <a href="https://wa.me/62895611194900?text=Halo,%20saya%20ingin%20memesan%20produk%20Anda.%20Apakah%20produk%20Anda%20tersedia."
       class="fixed w-14 h-14 bottom-6 right-6 bg-green-500 text-white rounded-full grid place-items-center text-3xl shadow-xl hover:scale-110 transition"
       target="_blank">
       <i class="fab fa-whatsapp"></i>
    </a>


    {{-- Script --}}
    <script>
        document.getElementById('userDropdownBtn')?.addEventListener('click', () => {
            document.getElementById('userDropdownMenu').classList.toggle('hidden');
        });
    </script>

</body>
</html>
