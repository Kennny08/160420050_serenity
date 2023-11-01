<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    public function produks()
    {
        return $this->belongsToMany(Produk::class, 'pembelian_produk', 'pembelian_id', 'produk_id')->withPivot('kuantitas', 'harga');
    }
}