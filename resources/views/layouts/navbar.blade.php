<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Menambahkan warna custom "palevioletred" ke konfigurasi Tailwind
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        palevioletred: '#DB7093',   
                    }
                }
            }
        }
    </script>
    <style>
        /* Menambahkan style untuk smooth scroll */
        body {
  background-color: #fcefee; /* Warna dasar yang lembut */
  background-image: 
    radial-gradient(at 10% 20%, #fce2e7 0px, transparent 50%),
    radial-gradient(at 80% 10%, #fdd5d9 0px, transparent 50%),
    radial-gradient(at 70% 90%, #ffd9de 0px, transparent 50%),
    radial-gradient(at 20% 85%, #ffcbc5 0px, transparent 50%);
  min-height: 100vh;
}
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <nav class="bg-white text-gray-800 shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
        
        <div class="text-2xl font-bold text-palevioletred">DARA CAKE</div>
        
        <ul class="flex space-x-8">
            <li>
                <a href="#" class="nav-link py-2 border-b-2 font-semibold transition-colors duration-300 text-palevioletred border-palevioletred">
                    Home
                </a>
            </li>
            <li>
                <a href="#produk" class="nav-link py-2 border-b-2 border-transparent transition-colors duration-300 text-palevioletred hover:border-palevioletred">
                    Products
                </a>
            </li>
            <li>
                <a href="#about" class="nav-link py-2 border-b-2 border-transparent transition-colors duration-300 text-palevioletred hover:border-palevioletred">
                    About Us
                </a>
            </li>
            <li>
                <a href="#contact" class="nav-link py-2 border-b-2 border-transparent transition-colors duration-300 text-palevioletred hover:border-palevioletred">
                    Contact
                </a>
            </li>
        </ul>

        <div class="flex items-center space-x-4">
            
            @auth
                <a href="{{ route('profile.show') }}" class="flex items-center space-x-2 text-gray-700 hover:text-palevioletred transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span class="font-semibold">{{ Auth::user()->name }}</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-600 text-sm">
                        Logout
                    </button>
                </form>

            @else
                <a href="{{ route('register') }}" class="bg-white text-palevioletred px-4 py-2 rounded-lg font-semibold hover:bg-gray-100 border border-gray-200">
                    Register
                </a>
                <a href="{{ route('login') }}" class="bg-palevioletred text-white px-4 py-2 rounded-lg font-semibold hover:bg-pink-500">
                    Login
                </a>
            @endauth

        </div>
        </div>
</nav>


    <div>
        @yield('content')

        {{-- <section id="produk" class="h-screen pt-16">
            <h2 class="text-2xl font-bold">Ini Bagian Produk</h2>
        </section>
        <section id="about" class="h-screen pt-16">
            <h2 class="text-2xl font-bold">Ini Bagian Tentang Kami</h2>
        </section>
        <section id="contact" class="h-screen pt-16">
             <h2 class="text-2xl font-bold">Ini Bagian Kontak</h2>
        </section> --}}

    </div>

    <footer class="bg-pink-600 text-white mt-10">
        <div class="max-w-7xl mx-auto px-6 py-10 grid grid-cols-1 md:grid-cols-3 gap-8">

            <div>
                <h3 class="text-lg font-bold mb-3">DaraCake</h3>
                <p class="text-sm leading-relaxed">
                    Hadir sejak 2020, Daracake selalu berkomitmen menghadirkan kue lezat 
                    dengan bahan berkualitas untuk setiap momen spesial Anda. 🎂
                </p>
            </div>

            <div>
                <h3 class="text-lg font-bold mb-3">Kontak</h3>
                <ul class="space-y-2 text-sm">
                    <li>📍 Jakarta, Indonesia</li>
                    <li>📞 +62 812-3456-7890</li>
                    <li>📧 info@daracake.com</li>
                </ul>
            </div>

            <div>
                <h3 class="text-lg font-bold mb-3">Ikuti Kami</h3>
                <div class="flex space-x-4">
                    <a href="#" class="hover:text-yellow-300">🌐 Instagram</a>
                    <a href="#" class="hover:text-yellow-300">🌐 Facebook</a>
                    <a href="#" class="hover:text-yellow-300">🌐 TikTok</a>
                </div>
            </div>
        </div>

        <div class="bg-pink-700 text-center py-3 text-sm">
            &copy; 2025 <strong>DaraCake</strong> | Developed by <span class="font-semibold">Dimas Aditya Ramadhan</span>
        </div>
    </footer>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil semua link di navigasi
        const navLinks = document.querySelectorAll('.nav-link');

        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // Hapus kelas aktif dari semua link
                navLinks.forEach(item => {
                    item.classList.remove('font-semibold', 'border-palevioletred');
                    item.classList.add('border-transparent');
                });

                // Tambahkan kelas aktif ke link yang diklik
                this.classList.add('font-semibold', 'border-palevioletred');
                this.classList.remove('border-transparent');
            });
        });
    });
</script>

</body>
</html>