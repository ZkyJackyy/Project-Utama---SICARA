<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('customer.notifikasi.index', compact('notifications'));
    }

    public function markRead($id)
    {
        $notif = Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $notif->update(['is_read' => 1]);

        return back();
    }

    public function readAll()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        Notifikasi::where('user_id', Auth::id())
                  ->where('is_read', 0)
                  ->update(['is_read' => 1]);

        return response()->json(['success' => true]);
    }
}
