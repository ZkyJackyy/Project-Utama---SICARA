@extends(auth()->user()->role == 'admin' ? 'layouts.navbar_admin' : 'layouts.navbar')

@section('content')
<div class="min-h-screen bg-gray-100 py-6 px-4 sm:px-6 font-['Poppins'] flex justify-center">
    
    <div class="w-full max-w-4xl bg-white shadow-2xl rounded-3xl overflow-hidden flex flex-col h-[85vh]">
        
        {{-- 1. HEADER CHAT --}}
        <div class="bg-white border-b border-gray-100 p-4 flex justify-between items-center z-10 shadow-sm relative">
            <div class="flex items-center gap-4">
                <a href="{{ route('tickets.index') }}" class="w-10 h-10 rounded-full hover:bg-gray-100 flex items-center justify-center text-gray-500 transition">
                    <i class="fa fa-arrow-left"></i>
                </a>

                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="font-bold text-lg text-gray-800 line-clamp-1">
                            #{{ $ticket->id }} - {{ Str::limit($ticket->subject, 30) }}
                        </h1>
                        @if($ticket->status == 'open')
                            <span class="flex h-2.5 w-2.5 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                            </span>
                        @else
                            <i class="fa fa-check-circle text-gray-400"></i>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 flex items-center gap-2">
                        <span class="bg-gray-100 px-2 py-0.5 rounded text-[10px] uppercase font-bold tracking-wide">{{ $ticket->category }}</span>
                        <span>•</span>
                        <span>{{ $ticket->user->name }}</span>
                    </p>
                </div>
            </div>

            @if(auth()->user()->role == 'admin' && $ticket->status == 'open')
            <form action="{{ route('tickets.close', $ticket->id) }}" method="POST">
                @csrf
                <button type="submit" class="group bg-gray-50 hover:bg-gray-900 text-gray-600 hover:text-white border border-gray-200 hover:border-gray-900 px-4 py-2 rounded-xl text-xs font-bold transition-all duration-300 flex items-center gap-2">
                    <i class="fa fa-check group-hover:scale-110 transition-transform"></i>
                    <span>Tandai Selesai</span>
                </button>
            </form>
            @endif
        </div>

        {{-- 2. AREA CHAT (Container) --}}
        <div id="chat-scroller" class="flex-1 overflow-y-auto p-6 bg-[#efe7dd] relative scroll-smooth custom-scrollbar">
            <div class="absolute inset-0 opacity-5 pointer-events-none" style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');"></div>

            {{-- Tanggal --}}
            <div class="relative z-10 mb-4 flex justify-center">
                <span class="bg-gray-200/50 backdrop-blur-sm text-gray-600 text-[10px] font-bold px-3 py-1 rounded-full shadow-sm">
                    {{ $ticket->created_at->translatedFormat('l, d F Y') }}
                </span>
            </div>

            {{-- TEMPAT PESAN AKAN DIMUAT --}}
            <div id="messages-wrapper" class="relative z-10">
                {{-- Include Partial Pertama Kali (Server Side Render) --}}
                @include('ticket.partial.chat_buble', ['ticket' => $ticket])
            </div>
        </div>

        {{-- 3. INPUT AREA (AJAX FORM) --}}
        <div class="bg-white p-4 border-t border-gray-100 z-20">
            @if($ticket->status == 'open')
            <form id="chat-form" class="flex items-end gap-2">
                <div class="flex-1 relative">
                    <textarea 
                        id="message-input"
                        rows="1" 
                        class="w-full bg-gray-100 border-0 rounded-2xl px-5 py-3.5 focus:ring-2 focus:ring-[#700207] focus:bg-white transition-all resize-none text-sm custom-scrollbar"
                        placeholder="Ketik pesan balasan..."
                        required
                        oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                </div>
                <button type="submit" id="send-btn" class="bg-[#700207] hover:bg-[#5a0105] text-white h-[48px] w-[48px] rounded-full flex items-center justify-center shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex-shrink-0">
                    <i class="fa fa-paper-plane text-sm"></i>
                </button>
            </form>
            @else
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center">
                <p class="text-sm text-gray-500 font-medium">Tiket telah diselesaikan.</p>
            </div>
            @endif
        </div>

    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
</style>

{{-- JAVASCRIPT AUTO RELOAD --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const chatScroller = document.getElementById('chat-scroller');
        const messagesWrapper = document.getElementById('messages-wrapper');
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message-input');
        const sendBtn = document.getElementById('send-btn');
        
        // URL untuk Fetch & Send
        const fetchUrl = "{{ route('tickets.fetch', $ticket->id) }}";
        const sendUrl = "{{ route('tickets.reply', $ticket->id) }}";
        const csrfToken = "{{ csrf_token() }}";

        let isUserScrolling = false;

        // 1. Scroll ke bawah saat awal load
        scrollToBottom();

        // Deteksi jika user sedang scroll ke atas (supaya tidak dipaksa turun saat auto reload)
        chatScroller.addEventListener('scroll', () => {
            if (chatScroller.scrollTop + chatScroller.clientHeight < chatScroller.scrollHeight - 50) {
                isUserScrolling = true;
            } else {
                isUserScrolling = false;
            }
        });

        // 2. Fungsi Fetch Pesan (Polling setiap 3 detik)
        function fetchMessages() {
            fetch(fetchUrl)
                .then(response => response.text())
                .then(html => {
                    messagesWrapper.innerHTML = html;
                    // Hanya scroll ke bawah jika user TIDAK sedang membaca chat di atas
                    if (!isUserScrolling) {
                        scrollToBottom();
                    }
                })
                .catch(error => console.error('Error fetching messages:', error));
        }

        // Jalankan Polling
        setInterval(fetchMessages, 3000);

        // 3. Fungsi Kirim Pesan (AJAX)
        if(chatForm) {
            chatForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const message = messageInput.value.trim();
                if(!message) return;

                // Disable tombol saat mengirim
                sendBtn.disabled = true;
                sendBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

                fetch(sendUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ message: message })
                })
                .then(response => {
                    if (response.ok) {
                        messageInput.value = '';
                        messageInput.style.height = 'auto'; // Reset height textarea
                        fetchMessages(); // Refresh chat langsung
                        scrollToBottom();
                    }
                })
                .catch(error => console.error('Error sending message:', error))
                .finally(() => {
                    // Enable kembali
                    sendBtn.disabled = false;
                    sendBtn.innerHTML = '<i class="fa fa-paper-plane text-sm"></i>';
                });
            });
        }

        function scrollToBottom() {
            chatScroller.scrollTop = chatScroller.scrollHeight;
        }
    });
</script>
@endsection