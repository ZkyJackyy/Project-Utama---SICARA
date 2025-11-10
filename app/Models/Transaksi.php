<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $fillable = [
        'user_id',
        'metode_pembayaran',
        'bukti_pembayaran',
        'total',
        'status',
        'is_custom',
    ];

    // ✅ Tambahkan relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ✅ Ubah nama relasi agar konsisten
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
    }
}
