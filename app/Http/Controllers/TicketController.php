<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tiket;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    // --- HALAMAN UTAMA (LIST TIKET) ---
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            // Admin melihat SEMUA tiket, urutkan yang OPEN (belum selesai) di atas
            $tickets = Tiket::with('user')
                ->withCount(['messages as unread_count' => function($q) {
                    $q->where('is_read', false)->where('from_id', '!=', Auth::id());
                }])
                ->orderByRaw("FIELD(status, 'open', 'closed')") // Open dulu
                ->orderByDesc('updated_at')
                ->get();
            
            return view('admin.ticket.index', compact('tickets'));
        } else {
            // Customer melihat tiket MEREKA SENDIRI
            $tickets = Tiket::where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->get();
                
            return view('ticket.index', compact('tickets'));
        }
    }

    // --- CUSTOMER BUAT TIKET BARU ---
    public function create()
    {
        return view('ticket.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'subject' => 'required',
        'category' => 'required',
        'message' => 'required'
    ]);

    // 1. Buat Tiket
    $ticket = Tiket::create([
        'user_id' => Auth::id(),
        'subject' => $request->subject,
        'category' => $request->category,
        'status' => 'open'
    ]);

    // 2. Tentukan ID Admin (Biasanya ID 1)
    // Jika Anda yakin admin ID-nya 1, tulis angka 1.
    // Jika ragu, cari user dengan role admin.
    $admin = User::where('role', 'admin')->first();
    $adminId = $admin ? $admin->id : 1; 

    // 3. Simpan Pesan (Dengan to_id)
    Message::create([
        'ticket_id' => $ticket->id,
        'from_id' => Auth::id(),
        'to_id' => $adminId, // <--- SOLUSI: Kita paksa kirim ke Admin
        'body' => $request->message,
    ]);

    return redirect()->route('tickets.show', $ticket->id);
}

    // --- DETAIL CHAT DALAM TIKET ---
    public function show($id)
    {
        $ticket = Tiket::with(['messages.sender', 'user'])->findOrFail($id);

        // Validasi: Customer cuma boleh lihat tiket sendiri
        if (Auth::user()->role !== 'admin' && $ticket->user_id !== Auth::id()) {
            abort(403);
        }

        // Tandai pesan terbaca
        Message::where('ticket_id', $id)
            ->where('from_id', '!=', Auth::id())
            ->update(['is_read' => true]);

        return view('ticket.show', compact('ticket'));
    }

    // --- KIRIM BALASAN ---
    public function reply(Request $request, $id)
{
    $ticket = Tiket::findOrFail($id);

    if ($ticket->status === 'closed') {
        return back()->with('error', 'Tiket sudah ditutup.');
    }

    // LOGIKA PENENTUAN PENERIMA (Solusi Error to_id)
    // Cek siapa yang sedang login?
    if (Auth::user()->role == 'admin') {
        // Jika saya Admin, maka penerima adalah Pemilik Tiket (Customer)
        $receiverId = $ticket->user_id;
    } else {
        // Jika saya Customer, maka penerima adalah Admin (ID 1)
        // Cari ID admin (atau hardcode angka 1 jika yakin)
        $admin = User::where('role', 'admin')->first();
        $receiverId = $admin ? $admin->id : 1;
    }

    Message::create([
        'ticket_id' => $id,
        'from_id' => Auth::id(),
        'to_id' => $receiverId, // <--- SOLUSI: Diisi otomatis berdasarkan logika di atas
        'body' => $request->message
    ]);
    
    $ticket->touch(); 

    return back();
}

    // --- ADMIN MENUTUP TIKET ---
    public function close($id)
    {
        $ticket = Tiket::findOrFail($id);
        $ticket->update(['status' => 'closed']);
        return back()->with('success', 'Masalah dianggap selesai.');
    }

    public function fetchMessages($id)
{
    $ticket = Tiket::with(['messages.sender'])->findOrFail($id);

    // Validasi Akses
    if (Auth::user()->role !== 'admin' && $ticket->user_id !== Auth::id()) {
        abort(403);
    }

    // Tandai pesan sebagai terbaca (Read)
    Message::where('ticket_id', $id)
        ->where('from_id', '!=', Auth::id())
        ->where('is_read', false)
        ->update(['is_read' => true]);

    // Kembalikan hanya tampilan partial chat bubbles
    return view('ticket.partial.chat_buble', compact('ticket'))->render();
}
}
