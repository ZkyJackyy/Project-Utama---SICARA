@extends('layouts.navbar')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#FFC0CB]">
    <div class="bg-white rounded-2xl shadow-xl flex w-full max-w-4xl overflow-hidden">

        <!-- Gambar (kiri) -->
        <div class="hidden md:flex w-1/2 bg-[#FFC0CB] items-center justify-center p-8">
            <img src="{{ asset('gambar/little2.png') }}" alt="Login illustration" class="max-w-sm">
        </div>

        <!-- Form (kanan) -->
        <div class="w-full md:w-1/2 p-10 flex flex-col justify-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Sign In</h2>
            <p class="text-gray-500 mb-6">Unlock your world.</p>

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        <span class="text-red-500">*</span> Email
                    </label>
                    <input type="email" name="email" id="email" 
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
                        placeholder="Enter your email" required>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        <span class="text-red-500">*</span> Password
                    </label>
                    <input type="password" name="password" id="password"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
                        placeholder="Enter your password" required>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition-all">
                    Sign In
                </button>

                {{-- Tombol Google --}}
            <a href="{{ route('google.redirect') }}"
                class="w-full flex items-center justify-center py-3 px-4 rounded-lg bg-white text-gray-800 font-medium shadow-md hover:shadow-lg hover:bg-gray-100 transition-all">
                <img class="w-5 h-5 mr-2" src="https://developers.google.com/identity/images/g-logo.png" alt="Google icon">
                Lanjutkan dengan Google
            </a>
            </form>
        </div>
    </div>
</div>
@endsection
