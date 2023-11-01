<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    use HasFactory;

    public function produks()
    {
        return $this->belongsToMany(Produk::class, 'paket_produk', 'paket_id', 'produk_id')->withPivot('jumlah');
    }

    public function perawatans()
    {
        return $this->belongsToMany(Perawatan::class, 'paket_perawatan', 'paket_id', 'perawatan_id');
    }
}