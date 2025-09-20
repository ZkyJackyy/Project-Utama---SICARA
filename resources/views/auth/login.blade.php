@extends('layouts.navbar')

@section('title', 'Login')

@section('content')
    <div class="flex items-center justify-center h-screen">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Login</h2>

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email:</label>
                    <input type="email" name="email" id="email" class="w-full p-2 border border-gray-300 rounded" required>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-bold mb-2">Password:</label>
                    <input type="password" name="password" id="password" class="w-full p-2 border border-gray-300 rounded" required>
                </div>

                <div class="text-center">
                    <button type="submit" class="bg-pink-500 hover:bg-pink-600 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-300">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection