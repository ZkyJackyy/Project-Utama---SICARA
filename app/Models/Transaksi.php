<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'shipping_method',
        'shipping_cost',
        'shipping_address',
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

    public function ulasan()
    {
        return $this->hasOne(Ulasan::class, 'pesanan_id');
    }

    protected static function boot()
{
    parent::boot();

    static::creating(function ($model) {
        // Format: INV - TAHUNBULANTANGGAL - RANDOM 4 HURUF
        // Contoh: INV-20251224-X7A2
        $model->kode_transaksi = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(4));
    });
}

}
