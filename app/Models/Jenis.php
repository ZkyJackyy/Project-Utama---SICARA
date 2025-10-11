<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jenis extends Model
{

    protected $table = 'jenis';
    protected $fillable = ['jenis_produk'];
    public function products()
{
    return $this->hasMany(Product::class);
    
}
}
