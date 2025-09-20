<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    
    protected $table = 'products';

    protected $fillable = [
        'nama_produk',
        'jenis_id',
        'harga',
        'stok',
        'gambar',
        'deskripsi',
    ];

    public function jenis()
    {
        return $this->belongsTo(Jenis::class);
    }
}
