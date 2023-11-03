<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kondisi extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function produks()
    {
        return $this->belongsToMany(Produk::class, 'kondisi_produk', 'kondisi_id', 'produk_id');
    }
}