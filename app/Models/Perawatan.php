<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perawatan extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function karyawans()
    {
        return $this->belongsToMany(Karyawan::class, 'karyawan_perawatan', 'perawatan_id', 'karyawan_id')->withTrashed();
    }

    public function penjualanperawatans()
    {
        return $this->hasMany(PenjualanPerawatan::class, 'perawatan_id');
    }

    public function produks()
    {
        return $this->belongsToMany(Produk::class, 'perawatan_produk', 'perawatan_id', 'produk_id')->withTrashed();
    }

    public function pakets()
    {
        return $this->belongsToMany(Paket::class, 'paket_perawatan', 'perawatan_id', 'paket_id');
    }
}