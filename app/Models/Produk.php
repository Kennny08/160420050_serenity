<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function merek()
    {
        return $this->belongsTo(Merek::class, 'merek_id');
    }

    public function kondisis()
    {
        return $this->belongsToMany(Kondisi::class, 'kondisi_produk', 'produk_id', 'kondisi_id');
    }

    public function perawatans()
    {
        return $this->belongsToMany(Perawatan::class, 'perawatan_produk', 'produk_id', 'perawatan_id');
    }

    public function pakets()
    {
        return $this->belongsToMany(Paket::class, 'paket_produk', 'produk_id', 'paket_id')->withPivot('jumlah');
    }

    public function penjualans()
    {
        return $this->belongsToMany(Penjualan::class, 'penjualan_produk', 'produk_id', 'penjualan_id')->withPivot('kuantitas', 'harga');
    }

    public function riwayatpengambilanproduks()
    {
        return $this->hasMany(RiwayatPengambilanProduk::class, 'produk_id');
    }
    public function pembelians()
    {
        return $this->belongsToMany(Pembelian::class, 'pembelian_produk', 'produk_id', 'pembelian_id')->withPivot('kuantitas', 'harga');
    }
}