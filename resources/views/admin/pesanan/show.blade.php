@extends('layouts.navbar_admin')

@section('content')
<div class="bg-[#ECE6DA] min-h-screen py-10 px-6">
    <div class="max-w-6xl mx-auto"> {{-- Lebar dimaksimalkan --}}

        <div class="mb-6 flex justify-between items-center">
            <a href="{{ route('admin.pesanan.index') }}" 
               class="text-[#700207] hover:text-[#A83F3F] font-medium transition flex items-center gap-2 bg-white px-4 py-2 rounded-full shadow-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <span class="text-sm text-gray-500">ID Transaksi: #{{ $pesanan->id }}</span>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm animate-fade-in-down">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm animate-fade-in-down">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- KOLOM KIRI: DETAIL ITEM & INFO --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- 1. Info Pelanggan & Status (Card) --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-bold text-lg text-[#700207] mb-4 border-b pb-2">Informasi Pelanggan</h3>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Nama Pelanggan</p>
                            <p class="text-gray-900 font-medium text-lg">{{ $pesanan->user->name ?? 'Guest' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Kontak (Email)</p>
                            <p class="text-gray-900">{{ $pesanan->user->email ?? '-' }}</p>
                            {{-- Jika ada kolom no_hp di tabel users, buka komen ini: --}}
                            {{-- <p class="text-gray-600 text-sm mt-1"><i class="fab fa-whatsapp text-green-500"></i> {{ $pesanan->user->no_hp }}</p> --}}
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Tanggal Pemesanan</p>
                            <p class="text-gray-900">{{ $pesanan->created_at->format('d F Y, H:i') }} WIB</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Status Pesanan</p>
                            <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs font-bold text-white 
                                {{ $pesanan->status == 'Selesai' ? 'bg-green-500' : 
                                  ($pesanan->status == 'Dibatalkan' ? 'bg-red-500' : 
                                  ($pesanan->status == 'Diproses' ? 'bg-blue-500' : 'bg-yellow-500')) }}">
                                {{ $pesanan->status }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- 2. Daftar Produk (Card) --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50">
                        <h3 class="font-bold text-lg text-[#700207]">Rincian Produk</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-[#700207] text-white uppercase text-xs">
                                <tr>
                                    <th class="px-6 py-3">Produk</th>
                                    <th class="px-6 py-3 text-center">Qty</th>
                                    <th class="px-6 py-3 text-right">Harga Satuan</th>
                                    <th class="px-6 py-3 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($pesanan->detailTransaksi as $detail)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900 text-base">
                                            {{ $detail->produk->nama_produk ?? 'Produk Dihapus' }}
                                        </div>
                                        
                                        {{-- 👇 BAGIAN PENTING: MENAMPILKAN CATATAN CUSTOM 👇 --}}
                                        @if($detail->catatan)
                                            <div class="mt-2 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                                <p class="text-xs font-bold text-yellow-800 mb-1 flex items-center gap-1">
                                                    <i class="fas fa-sticky-note"></i> Detail Custom:
                                                </p>
                                                <p class="text-sm text-gray-700 italic leading-relaxed">
                                                    "{{ $detail->catatan }}"
                                                </p>
                                            </div>
                                        @endif
                                        {{-- 👆 AKHIR BAGIAN PENTING 👆 --}}
                                    </td>
                                    <td class="px-6 py-4 text-center font-medium">{{ $detail->jumlah }}</td>
                                    <td class="px-6 py-4 text-right text-gray-600">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right font-bold text-[#700207]">Rp {{ number_format($detail->harga * $detail->jumlah, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right font-bold text-gray-700">Total Pembayaran</td>
                                    <td class="px-6 py-4 text-right font-extrabold text-xl text-[#700207]">
                                        Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>

            {{-- KOLOM KANAN: AKSI & BUKTI --}}
            <div class="space-y-6">

                {{-- 3. Panel Aksi (Ubah Status) --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-bold text-lg text-[#700207] mb-4">Kelola Status</h3>
                    
                    <form action="{{ route('admin.pesanan.updateStatus', $pesanan->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Update Status Pesanan</label>
                        <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 mb-4 focus:ring-[#700207] focus:border-[#700207]">
                            @foreach(['Menunggu Konfirmasi', 'Akan Diproses', 'Diproses', 'Selesai', 'Dibatalkan'] as $statusOption)
                                <option value="{{ $statusOption }}" 
                                    {{ $pesanan->status == $statusOption ? 'selected' : '' }}
                                    {{ ($pesanan->status == 'Selesai' || $pesanan->status == 'Dibatalkan') ? 'disabled' : '' }}>
                                    {{ $statusOption }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit" 
                            class="w-full bg-[#700207] hover:bg-[#5a0105] text-white font-bold py-3 rounded-lg transition shadow-lg 
                            {{ ($pesanan->status == 'Selesai' || $pesanan->status == 'Dibatalkan') ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ ($pesanan->status == 'Selesai' || $pesanan->status == 'Dibatalkan') ? 'disabled' : '' }}>
                            Simpan Perubahan
                        </button>
                    </form>
                </div>

                {{-- 4. Bukti Pembayaran --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-bold text-lg text-[#700207] mb-4">Bukti Pembayaran</h3>
                    
                    <div class="mb-3">
                        <span class="text-sm text-gray-500">Metode:</span>
                        <span class="font-bold text-gray-800 block text-lg">{{ strtoupper(str_replace('_', ' ', $pesanan->metode_pembayaran)) }}</span>
                    </div>

                    @if($pesanan->metode_pembayaran == 'cod')
                        <div class="p-4 bg-blue-50 text-blue-800 rounded-lg border border-blue-100 text-center">
                            <i class="fas fa-hand-holding-usd text-2xl mb-2"></i>
                            <p class="font-medium">Bayar di Tempat (COD)</p>
                            <p class="text-xs mt-1">Pastikan kurir menerima uang saat pengantaran.</p>
                        </div>
                    @elseif($pesanan->bukti_pembayaran)
                        <div class="group relative">
                            <a href="{{ asset('storage/' . $pesanan->bukti_pembayaran) }}" target="_blank">
                                <img src="{{ asset('storage/' . $pesanan->bukti_pembayaran) }}" 
                                     alt="Bukti Transfer" 
                                     class="w-full rounded-lg border border-gray-200 shadow-sm hover:opacity-90 transition cursor-zoom-in">
                                <div class="absolute inset-0 flex items-center justify-center bg-black/20 opacity-0 group-hover:opacity-100 transition rounded-lg pointer-events-none">
                                    <span class="bg-white px-3 py-1 rounded-full text-xs font-bold shadow">Klik untuk memperbesar</span>
                                </div>
                            </a>
                        </div>
                        <a href="{{ asset('storage/' . $pesanan->bukti_pembayaran) }}" download class="block text-center text-sm text-[#700207] hover:underline mt-3">
                            <i class="fas fa-download"></i> Download Bukti
                        </a>
                    @else
                        <div class="p-6 bg-red-50 text-red-700 rounded-lg border border-red-100 text-center">
                            <i class="fas fa-exclamation-circle text-2xl mb-2"></i>
                            <p class="font-bold">Belum Ada Bukti</p>
                            <p class="text-xs">Pelanggan belum mengunggah bukti pembayaran.</p>
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>
</div>
@endsection