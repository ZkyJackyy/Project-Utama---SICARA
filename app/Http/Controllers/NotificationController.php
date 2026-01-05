<?php

namespace App\Http\Controllers;

// use App\Models\Notifikasi;
use App\Models\Transaksi;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('customer.notifikasi.index', compact('notifications'));
    }

    public function markRead($id)
    {
        $notif = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notif->update(['is_read' => 1]);

        return back();
    }

    public function readAll()
    {
        // Cek login
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // Update database
        Notification::where('user_id', Auth::id())
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        return response()->json(['success' => true]);
    }

public function getNewOrders()
    {
        // Status yang dianggap "Baru"
        $status = ['Menunggu Konfirmasi', 'Akan Diproses'];

        // Ambil Data Pesanan
        $orders = Transaksi::with('user')
            ->whereIn('status', $status)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Hitung Total
        $count = Transaksi::whereIn('status', $status)->count();

        // Return JSON dengan format yang PASTI
        return response()->json([
            'count' => $count,
            'orders' => $orders->map(function($order) {
                return [
                    'id' => $order->id,
                    // Gunakan kode_transaksi jika ada, jika tidak pakai ID biasa
                    'kode' => $order->kode_transaksi ?? $order->id, 
                    // Handle jika user terhapus/null
                    'customer_name' => $order->user ? $order->user->name : 'Guest', 
                    // Format waktu
                    'time' => $order->created_at->diffForHumans(), 
                    // Status asli
                    'status' => $order->status, 
                    // Link Detail
                    'link' => route('admin.pesanan.show', $order->id) 
                ];
            })
        ]);
    }
}
