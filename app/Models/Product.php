<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    
    protected $table = 'products';

    use HasFactory, SoftDeletes;

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

//     protected $casts = [
//     'gambar' => 'array',
// ];
}
