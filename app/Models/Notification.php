<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id', 'transaksi_id', 'judul', 'pesan', 'is_read'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function markAllRead()
{
    Notification::where('user_id', Auth::id())
        ->where('is_read', 0)
        ->update(['is_read' => 1]);

    return response()->json(['success' => true]);
}

}

