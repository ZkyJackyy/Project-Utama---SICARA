{{-- File: resources/views/tickets/partials/chat_bubbles.blade.php --}}

@foreach($ticket->messages as $msg)
    @php $isMe = $msg->from_id == auth()->id(); @endphp
    
    <div class="flex w-full {{ $isMe ? 'justify-end' : 'justify-start' }} group mb-4">
        <div class="flex max-w-[85%] md:max-w-[70%] gap-3 {{ $isMe ? 'flex-row-reverse' : 'flex-row' }}">
            
            {{-- AVATAR (Hanya untuk lawan bicara) --}}
            @if(!$isMe)
                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gradient-to-br from-gray-700 to-gray-900 text-white flex items-center justify-center text-xs font-bold shadow-md mt-1">
                    {{ substr($msg->sender->name, 0, 1) }}
                </div>
            @endif

            {{-- BUBBLE PESAN --}}
            <div class="relative shadow-md p-3.5 
                {{ $isMe 
                    ? 'bg-[#700207] text-white rounded-2xl rounded-tr-sm' 
                    : 'bg-white text-gray-800 rounded-2xl rounded-tl-sm' 
                }}">
                
                {{-- Nama Pengirim --}}
                @if(!$isMe)
                    <p class="text-[10px] font-bold text-[#700207] mb-1 opacity-80">{{ $msg->sender->name }}</p>
                @endif

                <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ $msg->body }}</p>
                
                {{-- Waktu & Status Read --}}
                <div class="flex items-center justify-end gap-1 mt-1 opacity-70">
                    <span class="text-[10px] italic">
                        {{ $msg->created_at->format('H:i') }}
                    </span>
                    @if($isMe)
                        <i class="fa fa-check-double text-[10px] {{ $msg->is_read ? 'text-blue-300' : 'text-gray-300' }}"></i>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endforeach

@if($ticket->status == 'closed')
    <div class="flex justify-center mt-6">
        <div class="bg-gray-800 text-white px-4 py-2 rounded-full text-xs font-medium flex items-center gap-2 shadow-lg">
            <i class="fa fa-lock"></i>
            Percakapan ini telah ditutup.
        </div>
    </div>
@endif