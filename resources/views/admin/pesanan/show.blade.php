@extends('layouts.navbar_admin')

@section('content')
<div class="bg-[#ECE6DA] min-h-screen py-10 px-6">
    <div class="max-w-5xl mx-auto"> {{-- Dibuat sedikit lebih lebar --}}

        <div class="mb-4">
            <a href="{{ route('admin.pesanan.index') }}" 
               class="text-[#700207] hover:text-[#A83F3F] font-medium transition flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Kembali ke Daftar Pesanan
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-md p-8 border border-[#700207]">

            <h2 class="text-3xl font-bold text-[#700207] mb-6 text-center">
                Detail Pesanan #{{ $pesanan->id }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <div class="md:col-span-2 space-y-8">
                    
                    <div>
                        <h3 class="font-semibold text-lg text-[#700207] mb-3">Informasi Pesanan</h3>
                        <div class="grid grid-cols-2 gap-4 text-gray-800 bg-gray-50 p-4 rounded-lg border">
                            <p><strong>Customer:</strong><br>{{ $pesanan->user->name ?? '-' }}</p>
                            <p><strong>Total:</strong><br>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</p>
                            <p><strong>Tanggal:</strong><br>{{ $pesanan->created_at->format('d M Y, H:i') }}</p>
                            <p><strong>Metode:</strong><br>{{ ucfirst(str_replace('_', ' ', $pesanan->metode_pembayaran)) }}</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold text-lg text-[#700207] mb-3">Produk Dipesan:</h3>
                        <div class="overflow-x-auto rounded-lg border border-[#700207]">
                            <table class="w-full text-sm">
                                <thead class="bg-[#700207] text-white">
                                    <tr>
                                        <th class="px-3 py-2 text-left">Nama Produk</th>
                                        <th class="px-3 py-2">Jumlah</th>
                                        <th class="px-3 py-2 text-right">Harga</th>
                                        <th class="px-3 py-2 text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y">
                                    @foreach ($pesanan->detailTransaksi as $detail)
                                    <tr class="hover:bg-[#ECE6DA] transition">
                                        <td class="px-3 py-2">
                                            {{ $detail->produk->nama_produk ?? 'Produk tidak tersedia' }}
                                        </td>
                                        <td class="px-3 py-2 text-center">{{ $detail->jumlah }}</td>
                                        <td class="px-3 py-2 text-right">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                        <td class="px-3 py-2 text-right">Rp {{ number_format($detail->harga * $detail->jumlah, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-1 space-y-6">
                    
                    <div>
                        <h3 class="font-semibold text-lg text-[#700207] mb-3">Pembayaran</h3>
                        
                        @if($pesanan->metode_pembayaran == 'cod')
                            <div class="p-4 bg-blue-50 text-blue-700 rounded-lg border border-blue-200 text-center">
                                <p class="font-semibold">Cash on Delivery (COD)</p>
                                <p class="text-sm">Tidak memerlukan bukti pembayaran.</p>
                            </div>
                        @elseif($pesanan->bukti_pembayaran)
                            <p class="text-sm text-gray-600 mb-2">Bukti Bayar (Transfer/QRIS):</p>
                            <a href="{{ asset('storage/' . $pesanan->bukti_pembayaran) }}" target="_blank">
                                {{-- PERBAIKAN: Path asset storage yang benar --}}
                                <img src="{{ asset('storage/' . $pesanan->bukti_pembayaran) }}" 
                                     alt="Bukti Pembayaran" 
                                     class="w-full rounded-lg shadow border border-[#700207] cursor-pointer hover:opacity-80 transition">
                            </a>
                        @else
                            <div class="p-4 bg-yellow-50 text-yellow-700 rounded-lg border border-yellow-200 text-center">
                                <p class="font-semibold">Menunggu Pembayaran</p>
                                <p class="text-sm">Pelanggan belum mengunggah bukti bayar.</p>
                            </div>
                        @endif
                    </div>

                    <div>
                        <h3 class="font-semibold text-lg text-[#700207] mb-3">Ubah Status</h3>
                        <form action="{{ route('admin.pesanan.updateStatus', $pesanan->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status Saat Ini: 
                                <span class="font-bold text-[#A83F3F]">{{ $pesanan->status }}</span>
                            </label>
                            
                            <select name="status" id="status" class="border border-[#700207] rounded px-3 py-2 w-full mb-4 focus:ring-[#A83F3F] focus:border-[#A83F3F]">
                                {{-- Opsi dinonaktifkan jika status sudah melewatinya --}}
                                
                                <option value="Menunggu Konfirmasi" 
                                    {{ $pesanan->status == 'Menunggu Konfirmasi' ? 'selected' : '' }}
                                    {{ in_array($pesanan->status, ['Akan Diproses', 'Diproses', 'Selesai']) ? 'disabled' : '' }}>
                                    Menunggu Konfirmasi
                                </option>
                                
                                <option value="Akan Diproses" 
                                    {{ $pesanan->status == 'Akan Diproses' ? 'selected' : '' }}
                                    {{ in_array($pesanan->status, ['Diproses', 'Selesai']) ? 'disabled' : '' }}>
                                    Akan Diproses (COD / Dikonfirmasi)
                                </option>

                                <option value="Diproses" 
                                    {{ $pesanan->status == 'Diproses' ? 'selected' : '' }}
                                    {{ $pesanan->status == 'Selesai' ? 'disabled' : '' }}>
                                    Diproses (Sedang disiapkan)
                                </option>

                                <option value="Selesai" 
                                    {{ $pesanan->status == 'Selesai' ? 'selected' : '' }}>
                                    Selesai
                                </option>
                                
                                <option value="Dibatalkan" 
                                    {{ $pesanan->status == 'Dibatalkan' ? 'selected' : '' }}
                                    {{ $pesanan->status == 'Selesai' ? 'disabled' : '' }}>
                                    Dibatalkan
                                </option>
                            </select>

                            <button type="submit" 
                                    class="bg-[#700207] hover:bg-[#A83F3F] text-white px-6 py-2 rounded-lg transition w-full font-semibold"
                                    {{ $pesanan->status == 'Selesai' ? 'disabled' : '' }}>
                                {{ $pesanan->status == 'Selesai' ? 'Pesanan Selesai' : 'Simpan Perubahan' }}
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection