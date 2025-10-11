@extends('layouts.navbar_admin')

@section('title', 'Admin Dashboard - Tambah Kategori')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-lg max-w-2xl mx-auto">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Tambah Kategori Baru</h2>

    {{-- Arahkan action ke route 'kategori.store' --}}
    <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        {{-- Input Nama Kategori --}}
        <div class="mb-6">
            <label for="nama_kategori" class="block text-gray-700 font-semibold mb-2">Nama Kategori</label>
            <input type="text" id="nama_kategori" name="jenis_produk"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                   placeholder="Contoh: Kue Kering" required>
        </div>

        {{-- Tombol Simpan --}}
        <div class="text-center">
            <button type="submit"
                    class="bg-pink-500 hover:bg-pink-600 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-300">
                Simpan Kategori
            </button>
        </div>
    </form>
</div>

@endsection