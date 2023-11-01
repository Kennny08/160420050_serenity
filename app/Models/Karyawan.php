<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    public function perawatans()
    {
        return $this->belongsToMany(Perawatan::class, 'karyawan_perawatan', 'karyawan_id', 'perawatan_id');
    }

    public function penjualanperawatans()
    {
        return $this->hasMany(PenjualanPerawatan::class, 'karyawan_id');
    }

    public function riwayatpengambilanproduks()
    {
        return $this->hasMany(RiwayatPengambilanProduk::class, 'karyawan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}