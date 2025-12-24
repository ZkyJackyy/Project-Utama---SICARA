<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $transaksi->id }} - Dara Cake</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Courier+Prime&family=Poppins:wght@400;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #fff;
            color: #1a1a1a;
        }
        
        /* Agar background warna tercetak (Chrome/Safari) */
        @media print {
            body { -webkit-print-color-adjust: exact; }
            #print-btn { display: none; } /* Sembunyikan tombol saat dicetak */
        }

        .receipt-font {
            font-family: 'Courier Prime', monospace; /* Font ala struk belanja */
        }
    </style>
</head>
<body class="p-8 max-w-3xl mx-auto border border-gray-200 mt-10 shadow-lg print:border-0 print:shadow-none print:mt-0 print:p-0">

    {{-- HEADER --}}
    <div class="flex justify-between items-start mb-8 border-b-2 border-dashed border-gray-300 pb-6">
        <div>
            <h1 class="text-3xl font-bold text-[#700207]">DARA CAKE</h1>
            <p class="text-sm text-gray-500 mt-1">Jl. Jambak Indah No 42 Rimbo Data, Kel. Bandar Buat, Kota Padang</p>
            <p class="text-sm text-gray-500">WhatsApp: 0812-6887-9898</p>
        </div>
        <div class="text-right">
            <h2 class="text-xl font-bold text-gray-800">INVOICE</h2>
            <p class="text-sm text-gray-500">#INV-{{ $transaksi->kode_transaksi }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $transaksi->created_at->format('d M Y, H:i') }}</p>
            
            {{-- Status Lunas/Belum --}}
            <div class="mt-2">
                <span class="border-2 border-green-600 text-green-700 font-bold px-3 py-1 text-xs rounded uppercase tracking-wide transform rotate-[-5deg] inline-block">
                    {{ $transaksi->status == 'Selesai' || $transaksi->status == 'Dikirim' || $transaksi->status == 'Diproses' ? 'LUNAS' : strtoupper($transaksi->status) }}
                </span>
            </div>
        </div>
    </div>

    {{-- INFO PENGIRIMAN --}}
    <div class="flex justify-between mb-8 gap-8">
        <div class="w-1/2">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Penerima</h3>
            <p class="font-bold text-lg text-gray-800">{{ $transaksi->user->name }}</p>
            <p class="text-sm text-gray-600 mt-1 leading-relaxed">
                {{ $transaksi->shipping_address ?? 'Alamat tidak tersedia' }}
            </p>
            <p class="text-sm text-gray-600 mt-1"><span class="font-semibold">Telp:</span> {{ $transaksi->user->no_hp ?? '-' }}</p>
            {{-- <p class="text-sm text-gray-600 mt-1"><span class="font-semibold">Alamat:</span> {{ $transaksi->user->alamat ?? '-' }}</p> --}}
        </div>
        <div class="w-1/2 text-right">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Metode Pengiriman</h3>
            <p class="font-bold text-gray-800">{{ $transaksi->shipping_method }}</p>
            @if($transaksi->tracking_number)
                <p class="text-sm text-gray-600 mt-1">Resi: {{ $transaksi->tracking_number }}</p>
            @endif
        </div>
    </div>

    {{-- TABEL PRODUK --}}
    <div class="mb-8">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b-2 border-gray-800">
                    <th class="py-3 text-sm font-bold text-gray-800 uppercase">Item</th>
                    <th class="py-3 text-sm font-bold text-gray-800 uppercase text-center">Qty</th>
                    <th class="py-3 text-sm font-bold text-gray-800 uppercase text-right">Harga</th>
                    <th class="py-3 text-sm font-bold text-gray-800 uppercase text-right">Total</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                @foreach($transaksi->detailTransaksi as $item)
                <tr class="border-b border-gray-200">
                    <td class="py-4">
                        <p class="font-bold text-gray-800">{{ $item->produk->nama_produk }}</p>
                        @if($item->catatan)
                            <p class="text-xs text-gray-500 italic mt-1">Note: {{ $item->catatan }}</p>
                        @endif
                    </td>
                    <td class="py-4 text-center">{{ $item->jumlah }}</td>
                    <td class="py-4 text-right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td class="py-4 text-right font-medium">Rp {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- TOTAL --}}
    <div class="flex justify-end mb-12">
        <div class="w-1/2 space-y-3">
            <div class="flex justify-between text-sm text-gray-600">
                <span>Subtotal</span>
                <span>Rp {{ number_format($transaksi->total - $transaksi->shipping_cost, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm text-gray-600">
                <span>Ongkos Kirim</span>
                <span>Rp {{ number_format($transaksi->shipping_cost, 0, ',', '.') }}</span>
            </div>
            <div class="border-t border-gray-800 pt-3 flex justify-between text-xl font-bold text-[#700207]">
                <span>TOTAL</span>
                <span>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="text-center border-t-2 border-dashed border-gray-300 pt-6">
        <p class="font-bold text-gray-800 mb-2">Terima Kasih Telah Berbelanja!</p>
        <p class="text-xs text-gray-500">Simpan struk ini sebagai bukti pembayaran yang sah.</p>
        <p class="text-xs text-gray-500 mt-1">Komplain maksimal 1x24 jam setelah barang diterima.</p>
    </div>

    {{-- TOMBOL PRINT (Hanya muncul di layar) --}}
    <div class="fixed bottom-5 right-5 print:hidden" id="print-btn">
        <button onclick="window.print()" class="bg-[#700207] text-white font-bold py-3 px-6 rounded-full shadow-lg hover:bg-[#5a0105] hover:scale-105 transition flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Cetak Invoice
        </button>
    </div>

    <script>
        // Opsional: Otomatis print saat halaman dibuka
        window.onload = function() {
            // Uncomment baris di bawah jika ingin langsung print otomatis
            // window.print();
        }
    </script>
</body>
</html>