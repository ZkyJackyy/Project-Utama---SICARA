@extends('layouts.navbar')
@section('title', 'Pesanan Saya')
@section('content')

<section class="py-24 px-6 sm:px-10 lg:px-16 bg-[#F3EEE7] min-h-screen">

    <h1 class="text-4xl font-bold text-center text-[#5C0A0A] tracking-wide mb-14">
        Riwayat Pesanan Anda
    </h1>

    {{-- Alert --}}
    @if (session('success'))
        <div class="max-w-4xl mx-auto mb-4 p-4 border border-green-600 bg-green-50 text-green-700 rounded-xl shadow">
            {{ session('success') }}
        </div>
    @endif

    @if ($pesanan->count() > 0)
    <div class="max-w-4xl mx-auto space-y-10">

        @foreach($pesanan as $item)
        <div class="bg-white rounded-3xl shadow-lg border border-[#5C0A0A]/10 p-8 hover:shadow-2xl transition-all">

            {{-- Header --}}
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-[#5C0A0A]">
                    Pesanan #{{ $item->id }}
                </h2>

                {{-- Status Badge --}}
                <span class="
                    px-4 py-1.5 rounded-full text-sm font-bold shadow-sm tracking-wide
                    @if($item->status == 'Menunggu Konfirmasi') bg-yellow-100 text-yellow-700 border border-yellow-300
                    @elseif($item->status == 'Akan Diproses') bg-blue-100 text-blue-700 border border-blue-300
                    @elseif($item->status == 'Selesai') bg-green-100 text-green-700 border border-green-300
                    @elseif($item->status == 'Dibatalkan') bg-red-100 text-red-700 border border-red-300
                    @else bg-gray-100 text-gray-700 border border-gray-300
                    @endif
                ">
                    {{ ucfirst($item->status) }}
                </span>
            </div>

            <p class="text-gray-500 text-sm mb-4">
                Tanggal pesanan: {{ $item->created_at->format('d M Y') }}
            </p>

            {{-- Detail Produk --}}
            <div class="border-t border-gray-200 pt-4 text-gray-700 text-sm space-y-2">
                @foreach($item->detailTransaksi as $detail)
                <div class="flex justify-between">
                    <span class="font-medium">{{ $detail->produk->nama_produk }}</span>
                    <span class="text-gray-500">× {{ $detail->jumlah }}</span>
                </div>
                @endforeach

                <div class="font-bold text-[#5C0A0A] text-lg border-t border-gray-200 pt-3">
                    Total Pembayaran: Rp {{ number_format($item->total, 0, ',', '.') }}
                </div>
            </div>

            {{-- Action --}}
            <div class="mt-6 space-y-4">

                {{-- Batalkan Pesanan --}}
                @if ($item->status == 'Menunggu Konfirmasi')
                    <form action="{{ route('customer.pesanan.batal', $item->id) }}" method="POST"
                        onsubmit="return confirm('Batalkan pesanan ini?');">
                        @csrf @method('PUT')
                        <button class="w-full bg-red-600 text-white py-2.5 rounded-xl font-semibold hover:bg-red-700 shadow-md">
                            Batalkan Pesanan
                        </button>
                    </form>

                @elseif ($item->status != 'Dibatalkan' && $item->status != 'Selesai')
                    <p class="text-center text-gray-500 text-sm italic">
                        Pesanan sedang diproses dan tidak dapat dibatalkan.
                    </p>
                @endif

                {{-- Sudah Mengulas --}}
                @if ($item->status == 'Selesai' && $item->ulasan)
                    <div class="p-4 bg-green-50 border border-green-300 rounded-xl text-green-700">
                        Anda sudah memberikan ulasan untuk pesanan ini.
                    </div>
                @endif

                {{-- Form Ulasan --}}
                @if ($item->status == 'Selesai' && !$item->ulasan)
                    <div class="p-5 bg-[#FAF7F2] border border-[#5C0A0A]/20 rounded-2xl shadow-sm">

                        <h3 class="text-lg font-semibold text-[#5C0A0A] mb-4">Beri Rating & Ulasan</h3>

                        <form action="{{ route('customer.ulasan.store', $item->id) }}" method="POST">
                            @csrf

                            {{-- Rating --}}
                            <div class="flex items-center gap-3 mb-4">
                                <label class="text-sm text-gray-700">Rating:</label>

                                <div class="flex items-center gap-1 cursor-pointer select-none"
                                     id="rating-stars-{{ $item->id }}">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span data-value="{{ $i }}"
                                              class="star-{{ $item->id }} text-3xl text-gray-300 hover:text-yellow-400 transition">
                                            ★
                                        </span>
                                    @endfor
                                </div>

                                <input type="hidden" name="rating" id="rating-input-{{ $item->id }}" required>
                            </div>

                            {{-- Textarea --}}
                            <textarea name="ulasan" rows="3"
                                class="w-full border border-[#5C0A0A]/20 rounded-xl p-3 text-sm focus:ring-2 focus:ring-[#5C0A0A]"
                                placeholder="Tulis ulasan Anda (opsional)..."></textarea>

                            <button type="submit"
                                class="mt-4 w-full bg-[#5C0A0A] text-white py-2.5 rounded-xl font-semibold hover:bg-[#420707] shadow-lg">
                                Kirim Ulasan
                            </button>
                        </form>
                    </div>
                @endif

            </div>

        </div>
        @endforeach

    </div>

    @else
        <p class="text-center text-gray-500 text-lg">Belum ada pesanan 😔</p>
    @endif
</section>

@endsection

{{-- Script Rating --}}
<script>
document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll("[id^='rating-stars-']").forEach(starGroup => {
        const id = starGroup.id.replace("rating-stars-", "");
        const stars = document.querySelectorAll(".star-" + id);
        const input = document.querySelector("#rating-input-" + id);

        stars.forEach((star, index) => {
            star.addEventListener("click", () => {
                const rating = index + 1;
                input.value = rating;

                stars.forEach((s, i) => {
                    s.style.color = i < rating ? "#FACC15" : "#D1D5DB";
                    s.style.transform = i < rating ? "scale(1.2)" : "scale(1)";
                });
            });
        });
    });

});
</script>
