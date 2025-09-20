@extends('layouts.navbar')

@section('title', 'Registrasi')

@section('content')
{{-- Memberi background abu-abu pada seluruh halaman agar card form lebih menonjol --}}
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md">
        
        {{-- Judul Form --}}
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900">Buat Akun Baru</h2>
            <p class="mt-2 text-sm text-gray-600">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-medium text-pink-600 hover:text-pink-500">
                    Masuk di sini
                </a>
            </p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Input Nama --}}
            <div>
                <label for="name" class="block text-sm font-bold text-gray-700">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                       class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" 
                       required autocomplete="name" autofocus>
                @error('name')
                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Input Email --}}
            <div>
                <label for="email" class="block text-sm font-bold text-gray-700">Alamat Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                       class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" 
                       required autocomplete="email">
                @error('email')
                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Input Password --}}
            <div>
                <label for="password" class="block text-sm font-bold text-gray-700">Password</label>
                <input type="password" name="password" id="password"
                       class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" 
                       required autocomplete="new-password">
                 @error('password')
                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- Input Konfirmasi Password --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-bold text-gray-700">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" 
                       required autocomplete="new-password">
            </div>

            {{-- Tombol Registrasi --}}
            <div>
                <button type="submit" 
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 transition-colors duration-300">
                    Registrasi
                </button>
            </div>

            {{-- Pemisah "atau" --}}
            <div class="flex items-center justify-center">
                <div class="w-full border-t border-gray-300"></div>
                <span class="px-2 text-sm text-gray-500 bg-white">atau</span>
                <div class="w-full border-t border-gray-300"></div>
            </div>

            {{-- Tombol Login dengan Google --}}
            <div>
                <a href="{{ route('google.redirect') }}" 
                   class="w-full flex items-center justify-center py-2.5 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-300">
                    <img class="w-5 h-5 mr-2" src="https://developers.google.com/identity/images/g-logo.png" alt="Google icon">
                    Lanjutkan dengan Google
                </a>
            </div>
        </form>
    </div>
</div>
@endsection