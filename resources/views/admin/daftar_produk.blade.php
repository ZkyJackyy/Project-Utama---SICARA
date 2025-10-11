@extends('layouts.navbar_admin')

@section('title', 'Admin Dashboard - Daftar Produk')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Daftar Produk</h1>
        <a href="/tambah-produk" class="inline-block bg-pink-500 hover:bg-pink-600 text-white font-bold py-2 px-4 rounded-lg transition">
            + Tambah Produk
        </a>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr class="text-left font-bold text-gray-600">
                    <th class="px-6 py-3">Gambar</th>
                    <th class="px-6 py-3">Nama Produk</th>
                    <th class="px-6 py-3">Jenis</th>
                    <th class="px-6 py-3">Harga</th>
                    <th class="px-6 py-3">Stok</th>
                    <th class="px-6 py-3">Deskripsi</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                    <th class="px-6 py-3">Set Status</th>
                    
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                {{-- Loop data produk Anda dari database akan dimulai di sini --}}
                @foreach ($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                    <img src="{{ asset('storage/produk/' . $product->gambar) }}" alt="Gambar Produk" class="h-16 w-16 object-cover rounded-md">
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{$product->nama_produk}}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $product->jenis->jenis_produk }}</td>
                    <td class="px-6 py-4 text-gray-500">Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-gray-500">{{$product->stok}}</td>
                    <td class="px-6 py-4 text-gray-500 max-w-xs truncate">{{$product->deskripsi}}</td>
                    <td>
                        {{-- Tampilkan status berdasarkan apakah produk di-soft delete atau tidak --}}
                        @if ($product->trashed())
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                Draft (Disembunyikan)
                            </span>
                        @else
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                Published
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <form onsubmit="return confirm('Apakah Anda Yakin Ingin Menghapus Data Ini?');" action="{{ route('produk.destroy', $product->id) }}" method="POST">
                            <a href="{{ route('produk.edit', $product->id) }}" class="text-blue-500 hover:text-blue-700 font-semibold">Edit</a>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 font-semibold">Hapus</button>
                            </form>
                        </div>
                    </td>
                    <td>
                        {{-- Tampilkan tombol aksi yang sesuai --}}
                        @if ($product->trashed())
                            <form action="{{ route('produk.publish', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="font-medium text-green-600 hover:underline">Publish</button>
                        </form>
                        @else
                            <form action="{{ route('produk.unpublish', $product->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menyembunyikan produk ini?');">
                            @csrf
                            @method('DELETE') {{-- Penting untuk route DELETE --}}
                            <button type="submit" class="font-medium text-red-600 hover:underline">Unpublish</button>
                        </form>
                        @endif
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection