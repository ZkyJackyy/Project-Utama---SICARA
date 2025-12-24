@extends('layouts.navbar')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 flex items-center justify-center font-['Poppins']">
    
    <div class="max-w-5xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row">
        
        {{-- BAGIAN KIRI: Visual & Info --}}
        <div class="md:w-5/12 bg-[#700207] p-10 text-white flex flex-col justify-between relative overflow-hidden">
            {{-- Dekorasi Background --}}
            <div class="absolute -top-10 -left-10 w-40 h-40 bg-red-800 rounded-full opacity-50 blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-60 h-60 bg-red-900 rounded-full opacity-50 blur-3xl"></div>

            <div class="relative z-10">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mb-6 shadow-inner">
                    <i class="fa fa-headset text-3xl text-white"></i>
                </div>
                <h2 class="text-3xl font-extrabold mb-4 leading-tight">Kami Siap <br>Membantu Anda</h2>
                <p class="text-red-100 text-sm leading-relaxed mb-6">
                    Maaf jika Anda mengalami kendala dengan pesanan Dara Cake. Silakan isi formulir di samping, tim kami akan segera menindaklanjuti laporan Anda dalam 1x24 jam.
                </p>
            </div>

            <div class="relative z-10 mt-8 md:mt-0 space-y-4">
                <div class="flex items-center gap-4 bg-white/10 p-3 rounded-xl backdrop-blur-sm border border-white/10">
                    <div class="w-10 h-10 rounded-full bg-white text-[#700207] flex items-center justify-center">
                        <i class="fa fa-phone"></i>
                    </div>
                    <div>
                        <p class="text-xs text-red-200">Hotline</p>
                        <p class="font-bold">+62 812-3456-7890</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 bg-white/10 p-3 rounded-xl backdrop-blur-sm border border-white/10">
                    <div class="w-10 h-10 rounded-full bg-white text-[#700207] flex items-center justify-center">
                        <i class="fa fa-envelope"></i>
                    </div>
                    <div>
                        <p class="text-xs text-red-200">Email</p>
                        <p class="font-bold">support@daracake.com</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- BAGIAN KANAN: Form Input --}}
        <div class="md:w-7/12 p-8 md:p-12 bg-white">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-2xl font-bold text-gray-800">Formulir Pengaduan</h3>
                <a href="{{ route('tickets.index') }}" class="text-sm text-gray-500 hover:text-[#700207] flex items-center gap-2 transition">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
            </div>

            <form action="{{ route('tickets.store') }}" method="POST" class="space-y-6">
                @csrf
                
                {{-- Input Kategori --}}
                <div class="relative group">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 ml-1">Kategori Masalah</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa fa-tag text-gray-400 group-focus-within:text-[#700207] transition"></i>
                        </div>
                        <select name="category" class="block w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-700 focus:bg-white focus:ring-2 focus:ring-[#700207] focus:border-transparent transition-all outline-none appearance-none cursor-pointer">
                            <option>Pesanan Tidak Sampai</option>
                            <option>Kue Rusak / Basi</option>
                            <option>Masalah Pembayaran</option>
                            <option>Pelayanan Kurir</option>
                            <option>Lainnya</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <i class="fa fa-chevron-down text-gray-400 text-xs"></i>
                        </div>
                    </div>
                </div>

                {{-- Input Subjek --}}
                <div class="relative group">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 ml-1">Subjek / Judul</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa fa-heading text-gray-400 group-focus-within:text-[#700207] transition"></i>
                        </div>
                        <input type="text" name="subject" class="block w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-[#700207] focus:border-transparent transition-all outline-none" placeholder="Contoh: Topping kue berantakan" required>
                    </div>
                </div>

                {{-- Input Pesan --}}
                <div class="relative group">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 ml-1">Detail Kronologi</label>
                    <div class="relative">
                        <div class="absolute top-4 left-0 pl-4 pointer-events-none">
                            <i class="fa fa-align-left text-gray-400 group-focus-within:text-[#700207] transition"></i>
                        </div>
                        <textarea name="message" rows="4" class="block w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-[#700207] focus:border-transparent transition-all outline-none resize-none" placeholder="Ceritakan detail masalahnya di sini..." required></textarea>
                    </div>
                </div>

                {{-- Tombol Submit --}}
                <div class="pt-2">
                    <button type="submit" class="w-full bg-[#700207] text-white font-bold py-4 rounded-xl shadow-lg shadow-red-900/20 hover:bg-[#5a0105] hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2">
                        <span>Kirim Laporan</span>
                        <i class="fa fa-paper-plane"></i>
                    </button>
                    <p class="text-center text-xs text-gray-400 mt-4">
                        <i class="fa fa-lock mr-1"></i> Data Anda aman dan terenkripsi.
                    </p>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection