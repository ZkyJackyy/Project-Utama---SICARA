@extends('layouts.navbar_admin')

@section('content')
<div class="bg-[#ECE6DA] min-h-screen py-10 px-6">
    <div class="max-w-6xl mx-auto"> 

        <div class="mb-6 flex justify-between items-center">
            <a href="{{ route('admin.pesanan.index') }}" 
               class="text-[#700207] hover:text-[#A83F3F] font-medium transition flex items-center gap-2 bg-white px-4 py-2 rounded-full shadow-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <span class="text-sm text-gray-500">ID Transaksi: #{{ $pesanan->id }}</span>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- KOLOM KIRI: DETAIL ITEM & INFO --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- 1. Info Pelanggan & Pengiriman --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-bold text-lg text-[#700207] mb-4 border-b pb-2">Informasi Pesanan</h3>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Nama Pelanggan</p>
                            <p class="text-gray-900 font-medium text-lg">{{ $pesanan->user->name ?? 'Guest' }}</p>
                            <p class="text-gray-600 text-sm">{{ $pesanan->user->email ?? '-' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Metode Pengiriman</p>
                            
                            @if(empty($pesanan->shipping_method))
                                {{-- DATA LAMA (KOSONG) --}}
                                <p class="text-gray-400 text-sm italic mt-1">- Data pengiriman tidak tersedia (Pesanan Lama) -</p>
                            
                            @elseif(str_contains(strtoupper($pesanan->shipping_method), 'AMBIL') || str_contains(strtoupper($pesanan->shipping_method), 'PICKUP'))
                                {{-- PICKUP --}}
                                <div class="mt-1 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <p class="text-yellow-800 font-bold flex items-center">
                                        <i class="fas fa-store mr-2"></i> AMBIL DI TOKO
                                    </p>
                                    <p class="text-xs text-yellow-700 mt-1">Customer akan datang mengambil pesanan.</p>
                                </div>
                            
                            @else
                                {{-- EKSPEDISI --}}
                                <p class="text-gray-900 font-bold mt-1">
                                    <i class="fas fa-truck text-gray-500"></i> {{ $pesanan->shipping_method }}
                                </p>
                                <div class="mt-2">
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Alamat Tujuan:</p>
                                    <p class="text-gray-700 text-sm bg-gray-50 p-2 rounded border mt-1">
                                        {{ $pesanan->shipping_address ?? '-' }}
                                    </p>
                                </div>
                            @endif
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
                                    <th class="px-6 py-3 text-right">Harga</th>
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
                                        @if($detail->catatan)
                                            <div class="mt-1 text-xs text-gray-500 bg-yellow-50 p-2 rounded border border-yellow-100">
                                                <i class="fas fa-sticky-note text-yellow-600"></i> Note: "{{ $detail->catatan }}"
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center font-medium">{{ $detail->jumlah }}</td>
                                    <td class="px-6 py-4 text-right">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right font-bold">Rp {{ number_format($detail->harga * $detail->jumlah, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-6 py-3 text-right font-medium text-gray-600">Subtotal</td>
                                    <td class="px-6 py-3 text-right text-gray-800">
                                        Rp {{ number_format($pesanan->total - ($pesanan->shipping_cost ?? 0), 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="px-6 py-3 text-right font-medium text-gray-600">Ongkos Kirim</td>
                                    <td class="px-6 py-3 text-right text-gray-800">
                                        Rp {{ number_format($pesanan->shipping_cost ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr class="bg-red-50">
                                    <td colspan="3" class="px-6 py-4 text-right font-bold text-[#700207]">Total Akhir</td>
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
                        </div>
                    @elseif($pesanan->bukti_pembayaran)
                        <div class="group relative">
                            <a href="{{ asset('storage/' . $pesanan->bukti_pembayaran) }}" target="_blank">
                                <img src="{{ asset('storage/' . $pesanan->bukti_pembayaran) }}" 
                                     alt="Bukti Transfer" 
                                     class="w-full rounded-lg border border-gray-200 shadow-sm hover:opacity-90 transition">
                            </a>
                        </div>
                        <a href="{{ asset('storage/' . $pesanan->bukti_pembayaran) }}" download class="block text-center text-sm text-[#700207] hover:underline mt-3">
                            <i class="fas fa-download"></i> Download Bukti
                        </a>
                    @else
                        <div class="p-6 bg-red-50 text-red-700 rounded-lg border border-red-100 text-center">
                            <i class="fas fa-exclamation-circle text-2xl mb-2"></i>
                            <p class="font-bold">Belum Ada Bukti</p>
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>
</div>
@endsection