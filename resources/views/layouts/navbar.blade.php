    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title ?? 'Dara Cake' }}</title>

        {{-- Fonts --}}
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

        <link rel="icon" href="{{ asset('gambar/5.png') }}" type="image/png">
        <link rel="shortcut icon" href="{{ asset('gambar/5.png') }}" type="image/png">
        <link rel="apple-touch-icon" href="{{ asset('gambar/5.png') }}">

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
                /* Smooth fade + slide up */
        .notif-animate {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        .notif-show {
            opacity: 1;
            transform: translateY(0);
        }

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

            #notifDropdown {
        opacity: 0;
        transform: translateY(10px);
        transition: all .25s ease-out;
    }

    #notifDropdown.open {
        opacity: 1;
        transform: translateY(0);
    }
        </style>
    </head>

    <body class="font-sans bg-white text-brandDark overflow-x-hidden">

        {{-- NAVBAR --}}
        <nav class="fixed top-0 w-full z-50 backdrop-blur-md bg-brandCream/90 shadow-sm transition-all">
            <div class="max-w-7xl mx-auto flex items-center justify-between px-4 md:px-8 py-3 md:py-4">

                {{-- Logo --}}
                <a href="/" class="flex items-center gap-2 md:gap-3 shrink-0">
                    <img src="{{ asset('gambar/5.png') }}" class="w-9 h-9 md:w-12 md:h-12 rounded-full shadow object-cover">
                    <span class="text-brandRed font-serif text-xl md:text-2xl font-semibold tracking-wide">DaraCake</span>
                </a>

                {{-- Menu Desktop --}}
                <ul class="hidden md:flex items-center space-x-8 lg:space-x-10 text-brandDark font-medium text-sm lg:text-base">
                    <li><a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}">Home</a></li>
                    <li><a href="{{ route('customer.produk.list') }}" class="nav-link {{ request()->is('produk*') ? 'active' : '' }}">Shop</a></li>
                    <li><a href="{{ route('custom-cake.index') }}" class="nav-link">Custom Cake</a></li>
                </ul>

                {{-- USER + NOTIF + CART --}}
                <div class="flex items-center gap-3 md:gap-5 text-lg md:text-xl text-brandRed">


                    {{-- USER DROPDOWN --}}
                    @auth
                    <div class="relative">
                        <button id="userDropdownBtn" class="flex items-center gap-2 hover:opacity-80 focus:outline-none">
                            <i class="fa fa-user"></i>
                            <span class="text-sm font-medium hidden md:inline">{{ Auth::user()->name }}</span>
                            <i class="fa fa-chevron-down text-xs hidden md:inline"></i>
                        </button>

                        <div id="userDropdownMenu" class="hidden absolute right-0 mt-4 w-48 bg-white text-brandDark border border-gray-100 rounded-xl shadow-xl py-2 text-sm z-50">
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

                    {{-- CART --}}
                    <a href="{{ route('keranjang.index') }}" class="relative hover:opacity-80">
                        <i class="fa fa-shopping-cart"></i>

                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] font-bold w-4 h-4 flex items-center justify-center rounded-full">
                                {{ count(session('cart')) }}
                            </span>
                        @endif
                    </a>

                    {{-- NOTIFIKASI --}}
                    @auth
                    <div class="relative">
                        <button id="notifBtn" class="hover:opacity-80 relative">
                            <i class="fa fa-bell"></i>

                            {{-- Badge Notifikasi --}}
                            @php
                                $notifCount = $notifikasiGlobal->where('is_read', 0)->count();
                            @endphp

                            @if($notifCount > 0)
                                <span class="absolute -top-2 -right-2 bg-red-600 text-white text-[10px] font-bold w-4 h-4 flex items-center justify-center rounded-full">
                                    {{ $notifCount }}
                                </span>
                            @endif
                        </button>

                        {{-- Dropdown Notifikasi --}}
                        <div id="notifDropdown"
                            class="hidden absolute right-0 mt-3 w-72 bg-white text-brandDark border border-gray-200 rounded-xl shadow-lg p-3 z-50">

                            <h4 class="font-semibold text-brandRed mb-2 text-sm">Notifikasi</h4>

                            @if($notifikasiGlobal->count() == 0)
                                <p class="text-gray-500 text-sm py-2 text-center">Belum ada notifikasi</p>
                            @else
                                <div class="max-h-60 overflow-y-auto space-y-2">
                                    @foreach($notifikasiGlobal as $n)
                                        <div class="border-b pb-2">
                                            <p class="text-sm font-medium">{{ $n->judul }}</p>
                                            <p class="text-xs text-gray-600 leading-4">{{ $n->pesan }}</p>
                                            <span class="text-[10px] text-gray-400">
                                                {{ $n->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    @endauth

                    {{-- CUSTOMER SERVICE --}}
                    <a href="{{ route('customer.service') }}"
                    class="hover:opacity-80 transition transform hover:scale-110 relative"
                    title="Customer Service"
                    target="_blank">
                        <i class="fa fa-headset text-brandRed"></i>
                    </a>

                    {{-- Hamburger --}}
                    <button id="mobileMenuBtn" class="md:hidden hover:opacity-80 focus:outline-none ml-1">
                        <i class="fa fa-bars text-xl"></i>
                    </button>
                </div>
            </div>

            {{-- MENU MOBILE --}}
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

        @if(session('success') || session('error'))
        <div id="alertBox"
            class="notif-animate fixed top-20 right-5 md:right-10 bg-white border-l-4 
                    {{ session('success') ? 'border-green-500' : 'border-red-500' }}
                    shadow-xl w-72 md:w-80 rounded-lg px-4 py-3 z-[9999]">
            <div class="flex items-start gap-3">
                <i class="fa {{ session('success') ? 'fa-check-circle text-green-600' : 'fa-times-circle text-red-600' }} text-xl"></i>
                <p class="text-sm text-gray-800 leading-relaxed">
                    {{ session('success') ?? session('error') }}
                </p>
            </div>
        </div>
    @endif


        {{-- MAIN CONTENT --}}
        <main class="pt-[72px] md:pt-[88px]">
            @yield('content')
        </main>


        {{-- Floating WhatsApp --}}
        <a href="https://wa.me/62895611194900?text=Halo,%20saya%20ingin%20memesan%20produk%20Anda."
        class="fixed w-12 h-12 md:w-14 md:h-14 bottom-5 right-5 md:bottom-6 md:right-6 bg-green-500 text-white rounded-full grid place-items-center text-2xl md:text-3xl shadow-xl hover:scale-110 transition z-50"
        target="_blank">
        <i class="fab fa-whatsapp"></i>
        </a>


        <script>
    document.addEventListener("DOMContentLoaded", () => {

        /* -----------------------------
        USER DROPDOWN
        ------------------------------ */
        const userBtn   = document.getElementById("userDropdownBtn");
        const userMenu  = document.getElementById("userDropdownMenu");

        if (userBtn) {
            userBtn.addEventListener("click", (e) => {
                e.stopPropagation();
                userMenu.classList.toggle("hidden");
            });
        }

        /* -----------------------------
        MOBILE MENU
        ------------------------------ */
        const mobileBtn  = document.getElementById("mobileMenuBtn");
        const mobileMenu = document.getElementById("mobileMenu");

        if (mobileBtn) {
            mobileBtn.addEventListener("click", (e) => {
                e.stopPropagation();
                mobileMenu.classList.toggle("hidden");

                const icon = mobileBtn.querySelector("i");
                icon.classList.toggle("fa-bars");
                icon.classList.toggle("fa-times");
            });
        }

        /* -----------------------------
        NOTIFIKASI DROPDOWN + AUTO READ
        ------------------------------ */
        const notifBtn      = document.getElementById("notifBtn");
        const notifDropdown = document.getElementById("notifDropdown");

        if (notifBtn) {
            notifBtn.addEventListener("click", async (e) => {
                e.stopPropagation();

                notifDropdown.classList.toggle("hidden");

                // Tambahkan animasi smooth
                if (!notifDropdown.classList.contains("hidden")) {
                    notifDropdown.classList.add("open");

                    // Auto mark as read
                    await fetch("{{ route('notifikasi.readAll') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        }
                    });

                    // Hilangkan badge notif
                    const badge = notifBtn.querySelector("span");
                    if (badge) badge.remove();

                } else {
                    notifDropdown.classList.remove("open");
                }
            });
        }

        /* -----------------------------
        CLICK OUTSIDE (CLOSE DROPDOWN)
        ------------------------------ */
        document.addEventListener("click", (e) => {
            if (userBtn && !userBtn.contains(e.target) && !userMenu.contains(e.target)) {
                userMenu.classList.add("hidden");
            }

            if (mobileBtn && !mobileBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenu.classList.add("hidden");

                const icon = mobileBtn.querySelector("i");
                icon.classList.add("fa-bars");
                icon.classList.remove("fa-times");
            }

            if (notifBtn && !notifBtn.contains(e.target) && !notifDropdown.contains(e.target)) {
                notifDropdown.classList.add("hidden");
                notifDropdown.classList.remove("open");
            }
        });



        /* -----------------------------
        GLOBAL ALERT (Fade In + Auto Remove)
        ------------------------------ */
        const alertBox = document.getElementById("alertBox");

        if (alertBox) {
            setTimeout(() => alertBox.classList.add("notif-show"), 100);

            setTimeout(() => {
                alertBox.classList.remove("notif-show");
                setTimeout(() => alertBox.remove(), 600);
            }, 3500);
        }
            
    });
    </script>


    </body>
    </html>
