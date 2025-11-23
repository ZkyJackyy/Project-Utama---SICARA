<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    protected $table = 'ulasan';

    protected $fillable = [
        'pesanan_id',
        'user_id',
        'product_id',
        'rating',
        'ulasan',

    ];

    public function pesanan()
    {
        return $this->belongsTo(Transaksi::class, 'pesanan_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
