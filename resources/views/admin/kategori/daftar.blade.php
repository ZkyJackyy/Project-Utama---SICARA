@extends('layouts.navbar_admin')

@section('title', 'Admin Dashboard - Daftar Kategori')

@section('content')
    {{-- Header: Judul dan Tombol Tambah --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Daftar Kategori</h1>
        {{-- Pastikan Anda memiliki route bernama 'kategori.create' --}}
        <a href="{{ route('category.create') }}" class="inline-block bg-pink-500 hover:bg-pink-600 text-white font-bold py-2 px-4 rounded-lg transition">
            + Tambah Kategori
        </a>
    </div>

    {{-- Tabel Data Kategori --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="w-full">
            <thead class="bg-gray-50 border-b-2 border-gray-200">
                <tr class="text-left text-sm font-bold text-gray-600 uppercase">
                    {{-- Kolom Nomor --}}
                    <th class="px-6 py-3 w-20 text-center">No</th>
                    
                    {{-- Kolom Nama Kategori --}}
                    <th class="px-6 py-3">Kategori</th>
                    
                    {{-- Kolom Aksi --}}
                    <th class="px-6 py-3 w-40 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                {{-- Ganti $products menjadi $categories atau variabel yang sesuai dari controller Anda --}}
                @forelse ($categories as $index => $category)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-center text-gray-700">
                        {{-- Menggunakan $loop->iteration untuk penomoran otomatis --}}
                        {{ $loop->iteration }}
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-900">
                        {{ $category->jenis_produk }} {{-- Sesuaikan dengan nama kolom di database Anda --}}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center space-x-4">
                            {{-- Tombol Edit --}}
                            <a href="{{ route('category.edit', $category->id) }}" class="text-blue-500 hover:text-blue-700 font-semibold">Edit</a>
                            
                            {{-- Tombol Hapus dalam Form --}}
                            <form onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');" action="{{ route('category.destroy', $category->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-semibold">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                {{-- Pesan jika tidak ada data kategori --}}
                <tr>
                    <td colspan="3" class="text-center py-10 text-gray-500">
                        Belum ada data kategori.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection