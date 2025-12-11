@extends('layouts.navbar')

@section('content')
<div class="max-w-3xl mx-auto p-4">
    <h2 class="text-2xl font-semibold mb-4">Notifikasi</h2>

    @foreach($notifications as $notif)
        <div class="p-4 mb-3 rounded-lg shadow 
            {{ $notif->is_read ? 'bg-gray-100' : 'bg-brandCream' }}">

            <h4 class="text-lg font-bold">{{ $notif->judul }}</h4>
            <p class="text-sm mt-1">{{ $notif->pesan }}</p>

            @if(!$notif->is_read)
                <a href="{{ route('notifikasi.read', $notif->id) }}"
                   class="text-blue-600 text-sm mt-2 inline-block">
                    Tandai sudah dibaca
                </a>
            @endif
        </div>
    @endforeach
</div>
@endsection
