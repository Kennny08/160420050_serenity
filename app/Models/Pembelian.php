<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    public function produks()
    {
        return $this->belongsToMany(Produk::class, 'pembelian_produk', 'pembelian_id', 'produk_id')->withPivot('kuantitas', 'harga')->withTimestamps()->withTrashed();
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->withTrashed();
    }
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id')->withTrashed();
    }
    
}