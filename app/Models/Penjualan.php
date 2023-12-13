<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    public function penjualanperawatans()
    {
        return $this->hasMany(PenjualanPerawatan::class, 'penjualan_id');
    }

    public function reservasi()
    {
        return $this->hasOne(Reservasi::class, 'penjualan_id');
    }
    public function ulasan()
    {
        return $this->hasOne(Ulasan::class, 'penjualan_id');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function produks()
    {
        return $this->belongsToMany(Produk::class, 'penjualan_produk', 'penjualan_id', 'produk_id')->withPivot('kuantitas', 'harga')->withTimestamps()->withTrashed();
    }

    public function diskon()
    {
        return $this->belongsTo(Diskon::class, 'diskon_id');
    }

    public function pakets()
    {
        return $this->belongsToMany(Paket::class, 'paket_penjualan', 'penjualan_id', 'paket_id')->withPivot('jumlah', 'harga')->withTimestamps()->withTrashed();
    }

}