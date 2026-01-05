@extends('layouts.navbar')

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white shadow-xl rounded-xl p-6">
    <h2 class="text-xl font-semibold text-brandRed mb-4 flex items-center gap-2">
        <i class="fa fa-headset"></i> Customer Service
    </h2>

    <p class="text-sm text-gray-600 mb-5">
        Silakan pilih topik yang ingin Anda tanyakan:
    </p>

    <div class="space-y-3">
        @php
            $chats = [
                'Halo CS Dara Cake, saya ingin bertanya tentang produk.',
                'Halo CS Dara Cake, saya ingin bertanya tentang pesanan saya.',
                'Halo CS Dara Cake, saya ingin bertanya tentang custom cake.',
                'Halo CS Dara Cake, saya ingin konfirmasi pembayaran.',
                'Halo CS Dara Cake, saya ingin komplain / bantuan.'
            ];
        @endphp

        @foreach($chats as $chat)
        <form action="{{ route('customer.service.chat') }}" method="POST">
            @csrf
            <input type="hidden" name="message" value="{{ $chat }}">
            <button type="submit"
                class="w-full text-left border rounded-lg px-4 py-3
                       hover:bg-brandCream/50 hover:border-brandRed
                       transition flex items-center gap-2">
                <i class="fa fa-comment-dots text-brandRed"></i>
                <span class="text-sm">{{ $chat }}</span>
            </button>
        </form>
        @endforeach
    </div>
</div>
@endsection
