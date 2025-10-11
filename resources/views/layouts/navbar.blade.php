<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }}</title>

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        // Warna custom "palevioletred"
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        palevioletred: '#DB7093',
                        softpink: '#FCE4EC',
                        rosewhite: '#FFF0F5'
                    },
                    boxShadow: {
                        soft: '0 8px 24px rgba(219, 112, 147, 0.15)'
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fff0f5 0%, #ffe4ec 50%, #fff0f5 100%);
            min-height: 100vh;
            color: #444;
        }
        html {
            scroll-behavior: smooth;
        }
        /* Efek blur glassmorphism untuk navbar */
        .glass-nav {
            backdrop-filter: blur(14px);
            background: rgba(255, 255, 255, 0.75);
        }
    </style>
</head>
<body class="overflow-x-hidden">

{{-- 🌸 Navbar --}}
<nav class="glass-nav shadow-lg sticky top-0 z-50 transition-all duration-300 border-b border-pink-100">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <div class="text-3xl font-extrabold text-palevioletred tracking-tight">
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-palevioletred to-pink-400">DARA CAKE</span>
        </div>

        <ul class="hidden md:flex space-x-8 font-medium">
            <li><a href="#" class="nav-link text-palevioletred border-b-2 border-palevioletred pb-1 transition">Home</a></li>
            <li><a href="{{ route('produk.list') }}" class="nav-link text-gray-700 hover:text-palevioletred border-b-2 border-transparent hover:border-palevioletred pb-1 transition">Products</a></li>
            <li><a href="#about" class="nav-link text-gray-700 hover:text-palevioletred border-b-2 border-transparent hover:border-palevioletred pb-1 transition">About</a></li>
            <li><a href="#contact" class="nav-link text-gray-700 hover:text-palevioletred border-b-2 border-transparent hover:border-palevioletred pb-1 transition">Contact</a></li>
        </ul>

        <div class="flex items-center space-x-4">
            @auth
                <a href="{{ route('profile.show') }}" 
                   class="flex items-center gap-2 text-gray-700 hover:text-palevioletred transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" 
                         viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" 
                         d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span class="font-semibold">{{ Auth::user()->name }}</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                        class="bg-gradient-to-r from-pink-500 to-palevioletred text-white px-5 py-2 rounded-lg font-semibold hover:from-pink-600 hover:to-pink-500 shadow-md transition-all duration-300">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('register') }}" 
                   class="bg-white border border-pink-300 text-palevioletred px-5 py-2 rounded-lg font-semibold hover:bg-pink-50 shadow-soft transition-all duration-300">
                    Register
                </a>
                <a href="{{ route('login') }}" 
                   class="bg-gradient-to-r from-palevioletred to-pink-500 text-white px-5 py-2 rounded-lg font-semibold hover:shadow-lg hover:scale-105 transition-all duration-300">
                    Login
                </a>
            @endauth
        </div>
    </div>
</nav>

{{-- 🌷 Content --}}
<main class="pt-6">
    @yield('content')
</main>


<script>
    // Aktifkan efek "active" di menu navbar
    document.addEventListener('DOMContentLoaded', function() {
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navLinks.forEach(item => {
                    item.classList.remove('border-palevioletred', 'text-palevioletred');
                    item.classList.add('border-transparent', 'text-gray-700');
                });
                this.classList.add('border-palevioletred', 'text-palevioletred');
            });
        });
    });
</script>

</body>
</html>
