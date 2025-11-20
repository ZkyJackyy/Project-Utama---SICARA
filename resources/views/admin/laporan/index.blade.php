@extends('layouts.navbar_admin')

@section('content')
<div class="space-y-10">
    

    {{-- JUDUL --}}
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-extrabold text-[#4a0105] tracking-wide">
            Laporan Penjualan
        </h1>
    </div>

    {{-- KARTU STATISTIK --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Total Pendapatan --}}
        <div class="p-6 bg-white shadow-lg rounded-2xl border border-[#ECCFC3] hover:shadow-2xl transition">
            <h2 class="text-lg font-semibold text-gray-600">Total Pendapatan</h2>
            <p class="text-4xl font-extrabold text-[#4a0105] mt-2">
                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
            </p>
        </div>

        {{-- Jumlah Pesanan --}}
        <div class="p-6 bg-white shadow-lg rounded-2xl border border-[#ECCFC3] hover:shadow-2xl transition">
            <h2 class="text-lg font-semibold text-gray-600">Jumlah Pesanan</h2>
            <p class="text-4xl font-extrabold text-[#4a0105] mt-2">
                {{ $jumlahPesanan }}
            </p>
        </div>

        {{-- Pendapatan Hari Ini --}}
        <div class="p-6 bg-white shadow-lg rounded-2xl border border-[#ECCFC3] hover:shadow-2xl transition">
            <h2 class="text-lg font-semibold text-gray-600">Pendapatan Hari Ini</h2>
            <p class="text-4xl font-extrabold text-[#4a0105] mt-2">
                Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}
            </p>
        </div>

    </div>

    {{-- TABEL RIWAYAT TRANSAKSI --}}
    <div class="bg-white rounded-2xl shadow-xl border border-[#ECCFC3] p-8 print-area">

        <div class="flex justify-end mb-4">
            <button onclick="window.print()" 
                class="bg-[#4a0105] text-white px-4 py-2 rounded-lg hover:bg-[#6a0208] transition">
                Print Laporan
            </button>
        </div>

        <h2 class="text-2xl font-bold text-[#4a0105] mb-5">Riwayat Transaksi</h2>

        {{-- FORM FILTER TANGGAL --}}
<form method="GET" action="{{ route('laporan.index') }}" class="bg-white p-5 rounded-2xl shadow border border-[#ECCFC3] no-print">

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

        {{-- FILTER TANGGAL --}}
        <div>
            <label class="text-sm text-gray-700 font-medium">Tanggal</label>
            <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                class="w-full mt-1 px-3 py-2 rounded-lg border border-gray-300 focus:ring-[#4a0105] focus:border-[#4a0105]">
        </div>

        {{-- FILTER BULAN --}}
        <div>
            <label class="text-sm text-gray-700 font-medium">Bulan</label>
            <select name="bulan"
                class="w-full mt-1 px-3 py-2 rounded-lg border border-gray-300 focus:ring-[#4a0105] focus:border-[#4a0105]">
                <option value="">Semua</option>
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                    </option>
                @endfor
            </select>
        </div>

        {{-- FILTER TAHUN --}}
        <div>
            <label class="text-sm text-gray-700 font-medium">Tahun</label>
            <select name="tahun"
                class="w-full mt-1 px-3 py-2 rounded-lg border border-gray-300 focus:ring-[#4a0105] focus:border-[#4a0105]">
                <option value="">Semua</option>
                @for ($y = 2023; $y <= date('Y'); $y++)
                    <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>
        </div>

        {{-- BUTTON FILTER --}}
        <div class="flex items-end gap-2">
            <button type="submit"
                class="bg-[#4a0105] text-white px-4 py-2 rounded-lg hover:bg-[#6a0208] transition w-full">
                Filter
            </button>

            <a href="{{ route('laporan.index') }}"
                class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition w-full">
                Reset
            </a>
        </div>

    </div>
</form>


        <div class="overflow-x-auto rounded-xl border border-[#ECCFC3]">
            <table class="w-full text-left">
                <thead class="bg-[#ECCFC3] text-[#4a0105]">
                    <tr>
                        <th class="px-5 py-3 font-semibold">ID</th>
                        <th class="px-5 py-3 font-semibold">Customer</th>
                        <th class="px-5 py-3 font-semibold">Total</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                        <th class="px-5 py-3 font-semibold">Tanggal</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($transaksi as $t)
                    <tr class="border-b hover:bg-[#ECCFC3]/20 transition">
                        <td class="px-5 py-3">{{ $t->id }}</td>
                        <td class="px-5 py-3">{{ $t->user->name ?? '-' }}</td>
                        <td class="px-5 py-3">Rp {{ number_format($t->total, 0, ',', '.') }}</td>

                        {{-- STATUS --}}
                        <td class="px-5 py-3">
                            <span class="
                                px-3 py-1 rounded-full text-sm font-medium
                                @if($t->status == 'pending') bg-yellow-200 text-yellow-800 @endif
                                @if($t->status == 'selesai') bg-green-200 text-green-800 @endif
                                @if($t->status == 'batal') bg-red-200 text-red-800 @endif
                            ">
                                {{ ucfirst($t->status) }}
                            </span>
                        </td>

                        <td class="px-5 py-3">{{ $t->created_at->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-6">
            {{ $transaksi->links() }}
        </div>

    </div>

</div>
@endsection

<style>
@media print {

    /* Sembunyikan semua elemen */
    body * {
        visibility: hidden;
    }

    /* Tampilkan hanya area tabel */
    .print-area, 
    .print-area * {
        visibility: visible !important;
    }

    /* Biarkan area tabel berada di posisi paling atas halaman */
    .print-area {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
    }

    /* Hilangkan tombol print */
    button {
        display: none !important;
    }

    nav {
        display: none !important;
    }

    .no-print {
    display: none !important;
}

}
</style>


