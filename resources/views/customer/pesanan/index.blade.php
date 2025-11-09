@extends('layouts.navbar')
@section('title', 'Pesanan Saya')
@section('content')

<section class="py-24 px-6 sm:px-10 lg:px-16 bg-[#ECE6DA] min-h-screen">
    <h1 class="text-3xl font-bold text-center text-[#700207] mb-10">Pesanan Saya</h1>

    @if ($pesanan->count() > 0)
    <div class="max-w-4xl mx-auto space-y-6">

        @foreach($pesanan as $item)
        <div class="bg-white rounded-xl shadow-md border border-[#700207]/30 p-6">

            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-[#700207]">Pesanan #{{ $item->id }}</h2>

                <span class="px-4 py-1 text-sm rounded-full
                    @if($item->status == 'pending') bg-yellow-200 text-yellow-800
                    @elseif($item->status == 'proses') bg-blue-200 text-blue-800
                    @elseif($item->status == 'selesai') bg-green-200 text-green-800
                    @else bg-gray-200 text-gray-700 @endif">
                    {{ ucfirst($item->status) }}
                </span>
            </div>

            <p class="text-sm text-gray-600 mt-2">
                Tanggal: {{ $item->created_at->format('d M Y') }}
            </p>

            <div class="mt-4 border-t border-gray-200 pt-4 text-sm text-gray-700">
                @foreach($item->detailTransaksi as $detail)
                <p><strong>{{ $detail->produk->nama_produk }}</strong> x {{ $detail->jumlah }}</p>
                @endforeach
                
                <p class="font-bold text-[#700207] mt-2">
                    Total: Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                </p>
            </div>
        </div>
        @endforeach

    </div>

    @else
        <p class="text-center text-gray-600 text-lg">Belum ada pesanan 😔</p>
    @endif
</section>

@endsection
