@extends('layouts.navbar')

@section('title', 'Register')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-cover bg-center px-4 pt-28 md:pt-32 relative"
     style="background-image: url('{{ asset('gambar/bg7.jpg') }}');">

    {{-- 🌸 Kartu Utama --}}
    <div class="relative bg-white/90 rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row w-full max-w-5xl animate-fade-in z-10">

        {{-- 💕 Bagian Kiri (Background Gambar + Ilustrasi Tengah) --}}
        <div class="hidden md:flex w-1/2 items-center justify-center relative animate-slide-left overflow-hidden bg-cover bg-center" 
             style="background-image: url('{{ asset('gambar/bg22.jpg') }}');">
            
            {{-- Overlay lembut --}}
            <div class="absolute inset-0 bg-pink-600/40 mix-blend-multiply"></div>

            {{-- ✨ Gambar ilustrasi di tengah --}}
            <img src="{{ asset('gambar/kue5.png') }}" 
                 alt="Register Illustration" 
                 class="w-4/5 max-w-sm opacity-95 drop-shadow-2xl select-none pointer-events-none object-contain z-10">
        </div>

        {{-- 🧁 Bagian Kanan (Form Register) --}}
        <div class="w-full md:w-1/2 p-8 md:p-10 flex flex-col justify-center animate-slide-right">
            <div class="flex flex-col items-center mb-8">
                <img src="{{ asset('gambar/5.png') }}" alt="Logo" class="w-20 mb-3 animate-float">
                <h1 class="text-pink-500 text-2xl font-bold">REGISTER</h1>
            </div>

            <form action="{{ route('register.post') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Nama --}}
                <div class="relative">
                    <i class="fa fa-user absolute top-3 left-4 text-gray-400"></i>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="w-full pl-11 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-400 outline-none transition"
                        placeholder="Full Name" required>
                </div>

                {{-- Email --}}
                <div class="relative">
                    <i class="fa fa-envelope absolute top-3 left-4 text-gray-400"></i>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="w-full pl-11 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-400 outline-none transition"
                        placeholder="Email" required>
                </div>

                {{-- Password --}}
                <div class="relative">
                    <i class="fa fa-lock absolute top-3 left-4 text-gray-400"></i>
                    <input type="password" name="password" id="password"
                        class="w-full pl-11 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-400 outline-none transition"
                        placeholder="Password" required>
                </div>

                {{-- Konfirmasi Password --}}
                <div class="relative">
                    <i class="fa fa-lock absolute top-3 left-4 text-gray-400"></i>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full pl-11 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-400 outline-none transition"
                        placeholder="Confirm Password" required>
                </div>

                {{-- Checkbox Terms --}}
                <div class="flex items-center text-sm text-gray-600">
                    <input id="terms" name="terms" type="checkbox" class="h-4 w-4 text-pink-500 focus:ring-pink-400 border-gray-300 rounded mr-2">
                    <label for="terms">
                        I agree to the <a href="#" class="text-pink-500 hover:underline">Terms</a> & 
                        <a href="#" class="text-pink-500 hover:underline">Privacy Policy</a>
                    </label>
                </div>

                {{-- Tombol Register --}}
                <button type="submit"
                    class="w-full bg-pink-500 text-white py-2.5 rounded-lg font-semibold hover:bg-pink-600 transition-all transform hover:scale-105 hover:shadow-md">
                    REGISTER
                </button>

                {{-- Garis pemisah --}}
                <div class="flex items-center my-4">
                    <div class="flex-1 h-px bg-gray-300"></div>
                    <span class="px-3 text-gray-500 text-sm">Or Register with</span>
                    <div class="flex-1 h-px bg-gray-300"></div>
                </div>

                {{-- Tombol Google --}}
                <div class="flex gap-3">
                    <a href="{{ route('google.redirect') }}"
                        class="flex-1 flex items-center justify-center gap-2 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        <img src="https://developers.google.com/identity/images/g-logo.png" class="w-5 h-5" alt="Google">
                        <span class="text-sm text-gray-700">Google</span>
                    </a>
                </div>

                {{-- Login --}}
                <p class="text-center text-sm text-gray-500 mt-5">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-pink-500 font-semibold hover:underline">Login</a>
                </p>
            </form>
        </div>
    </div>
</div>

{{-- ✨ Animasi Kustom --}}
<style>
    @keyframes fade-in {
        0% { opacity: 0; transform: scale(0.97); }
        100% { opacity: 1; transform: scale(1); }
    }
    @keyframes slide-left {
        0% { opacity: 0; transform: translateX(-40px); }
        100% { opacity: 1; transform: translateX(0); }
    }
    @keyframes slide-right {
        0% { opacity: 0; transform: translateX(40px); }
        100% { opacity: 1; transform: translateX(0); }
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-6px); }
    }

    .animate-fade-in { animation: fade-in 0.6s ease-out forwards; }
    .animate-slide-left { animation: slide-left 0.8s ease-out forwards; }
    .animate-slide-right { animation: slide-right 0.8s ease-out forwards; }
    .animate-float { animation: float 3s ease-in-out infinite; }
</style>
@endsection
