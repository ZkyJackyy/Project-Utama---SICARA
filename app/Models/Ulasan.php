<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    protected $table = 'ulasan';

    protected $fillable = [
        'pesanan_id',
        'user_id',
        'rating',
        'ulasan',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Transaksi::class, 'pesanan_id');
    }
}
