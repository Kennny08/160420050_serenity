<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPengambilanProduk extends Model
{
    use HasFactory;

    protected $table = 'riwayat_pengambilan_produks';

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id')->withTrashed();
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id')->withTrashed();
    }
}