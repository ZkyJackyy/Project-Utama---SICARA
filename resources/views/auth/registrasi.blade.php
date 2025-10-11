@extends('layouts.navbar')

@section('title', 'Register')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#FFC0CB]">
    <div class="bg-white rounded-2xl shadow-xl flex w-full max-w-5xl overflow-hidden">

        <!-- Ilustrasi (kiri) -->
        <div class="hidden md:flex w-1/2 bg-[#FFC0CB] items-center justify-center p-8">
            <img src="{{ asset('gambar/little4.png') }}" alt="Register illustration" class="max-w-sm">
        </div>

        <!-- Form Registrasi (kanan) -->
        <div class="w-full md:w-1/2 p-10 flex flex-col justify-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Create an Account</h2>
            <p class="text-gray-500 mb-6">
                Already have an account?
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium">Sign in here</a>
            </p>

            <form action="{{ route('register') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        <span class="text-red-500">*</span> Full Name
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
                        placeholder="Enter your full name" required>
                    @error('name')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        <span class="text-red-500">*</span> Email
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
                        placeholder="Enter your email" required>
                    @error('email')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        <span class="text-red-500">*</span> Password
                    </label>
                    <input type="password" name="password" id="password"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
                        placeholder="Enter your password" required>
                    @error('password')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                        <span class="text-red-500">*</span> Confirm Password
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
                        placeholder="Repeat your password" required>
                </div>

                <!-- Tombol Submit -->
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition-all">
                    Register
                </button>

                <!-- Tombol Google -->
                <a href="{{ route('google.redirect') }}"
                    class="w-full flex items-center justify-center py-2.5 px-4 rounded-lg bg-white text-gray-800 font-medium border border-gray-300 shadow-sm hover:bg-gray-50 transition-all">
                    <img class="w-5 h-5 mr-2" src="https://developers.google.com/identity/images/g-logo.png" alt="Google icon">
                    Continue with Google
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
