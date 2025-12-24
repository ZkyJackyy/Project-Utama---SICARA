<?php

namespace App\Models;

use App\Models\Tiket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'from_id', 
        'to_id', 
        'body', 
        'is_read'
    ];

    // Relasi ke Pengirim
    public function sender()
    {
        return $this->belongsTo(User::class, 'from_id');
    }

    // Relasi ke Penerima
    public function receiver()
    {
        return $this->belongsTo(User::class, 'to_id');
    }

    public function ticket()
{
    return $this->belongsTo(Tiket::class);
}
}
