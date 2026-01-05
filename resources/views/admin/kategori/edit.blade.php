@extends('layouts.navbar_admin')

@section('title', 'Admin Dashboard - Edit Kategori')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-lg max-w-2xl mx-auto">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Kategori</h2>

    {{-- Arahkan action ke route 'category.update' dengan ID kategori --}}
    <form action="{{ route('category.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT') {{-- Method spoofing untuk request UPDATE --}}
        
        {{-- Input Nama Kategori --}}
        <div class="mb-6">
            <label for="jenis_produk" class="block text-gray-700 font-semibold mb-2">Nama Kategori</label>
            <input type="text" id="jenis_produk" name="jenis_produk"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                   placeholder="Contoh: Kue Kering"
                   {{-- Tampilkan data lama dari database --}}
                   value="{{ old('jenis_produk', $category->jenis_produk) }}" 
                   required>
        </div>

        {{-- Tombol Simpan --}}
        <div class="text-center">
            <button type="submit"
        class="bg-[#4a0105] hover:bg-[#3a0104] text-white font-bold py-2 px-6 rounded-lg transition-colors duration-300">
    Update Kategori
</button>

        </div>
    </form>
</div>
@endsection