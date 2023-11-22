<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paket extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function produks()
    {
        return $this->belongsToMany(Produk::class, 'paket_produk', 'paket_id', 'produk_id')->withPivot('jumlah')->withTrashed();
    }

    public function perawatans()
    {
        return $this->belongsToMany(Perawatan::class, 'paket_perawatan', 'paket_id', 'perawatan_id')->withTrashed();
    }

    public function penjualans()
    {
        return $this->belongsToMany(Penjualan::class, 'paket_penjualan', 'paket_id', 'penjualan_id');
    }
}