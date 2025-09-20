@extends('layouts.navbar_admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-lg max-w-2xl mx-auto">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Produk</h2>

    <form action="/produk/{{ $product->id }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-6">
            <label for="nama_produk" class="block text-gray-700 font-semibold mb-2">Nama Produk</label>
            <input type="text" id="nama_produk" name="nama_produk"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                   placeholder="Contoh: Kue Bolu Coklat"
                   value="{{ old('nama_produk', $product->nama_produk) }}" required>
        </div>

        <div class="mb-6">
            <label for="jenis_id" class="block text-gray-700 font-semibold mb-2">Jenis Produk</label>
            <select id="jenis_id" name="jenis_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                    required>
                    {{-- <option value="">-- Pilih Jenis Kue --</option>
                <option value="1">Kue Bolu</option>
                <option value="2">Kue Kering</option>
                <option value="3">Kue Ulang Tahun</option>
                <option value="4">Brownies</option> --}}
                @foreach ($jenisProduk as $jenis)
                    <option value="{{ $jenis->id }}" 
                        {{ old('jenis_id', $product->jenis_id) == $jenis->id ? 'selected' : '' }}>
                        {{ $jenis->jenis_produk }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="harga" class="block text-gray-700 font-semibold mb-2">Harga</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">Rp</span>
                    <input type="number" id="harga" name="harga"
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                           placeholder="150000"
                           value="{{ old('harga', (int) $product->harga) }}" required>
                </div>
                @error('harga')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="stok" class="block text-gray-700 font-semibold mb-2">Stok</label>
                <input type="number" id="stok" name="stok"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                       placeholder="Contoh: 50"
                       value="{{ old('stok', $product->stok) }}" required>
            </div>
        </div>
        
        <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2">Gambar Produk</label>
            <div class="mt-1 flex justify-center items-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                <div class="space-y-1 text-center">

                    {{-- Preview gambar lama --}}
                    @if($product->gambar)
                        <div class="mb-3">
                            <p class="text-sm text-gray-600 mb-2">Gambar saat ini:</p>
                            <img src="{{ asset('storage/produk/' . $product->gambar) }}" alt="Gambar Produk" class="mx-auto h-32 w-auto rounded-md">
                        </div>
                    @endif

                    {{-- Preview gambar baru --}}
                    <div id="image-preview-container" class="hidden relative">
                        <img id="image-preview" class="mx-auto h-32 w-auto rounded-md" src="#" alt="Preview Gambar" />
                        <button type="button" id="remove-image-btn" class="absolute top-1 right-1 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs font-bold hover:bg-red-600">
                            &times;
                        </button>
                    </div>
                    
                    <div id="upload-prompt">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="gambar" class="relative cursor-pointer bg-white rounded-md font-medium text-pink-600 hover:text-pink-500 focus-within:outline-none">
                                <span>Upload a file</span>
                                <input id="gambar" name="gambar" type="file" class="sr-only" accept="image/*">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mb-6">
            <label for="deskripsi" class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="4"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                      placeholder="Jelaskan tentang kelezatan dan bahan-bahan produk Anda...">{{ old('deskripsi', $product->deskripsi) }}</textarea>
        </div>

        <div class="text-center">
            <button type="submit"
                    class="bg-pink-500 hover:bg-pink-600 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-300">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
    const gambarInput = document.getElementById('gambar');
    const previewContainer = document.getElementById('image-preview-container');
    const previewImage = document.getElementById('image-preview');
    const uploadPrompt = document.getElementById('upload-prompt');
    const removeBtn = document.getElementById('remove-image-btn');

    if (gambarInput) {
        gambarInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                previewImage.src = URL.createObjectURL(file);
                previewContainer.classList.remove('hidden');
                uploadPrompt.classList.add('hidden');
            }
        });
    }

    if (removeBtn) {
        removeBtn.addEventListener('click', function() {
            gambarInput.value = '';
            previewImage.src = '#';
            previewContainer.classList.add('hidden');
            uploadPrompt.classList.remove('hidden');
        });
    }
</script>
@endsection
