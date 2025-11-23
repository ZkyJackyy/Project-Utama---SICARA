<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksi';
    protected $fillable = [
        'transaksi_id',
        'produk_id',
        'jumlah',
        'harga',
        'catatan',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    // ✅ Tambahkan ini
    public function produk()
    {
        return $this->belongsTo(Product::class, 'produk_id')->withTrashed();
    }
}
