<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Merek extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function produks()
    {
        return $this->hasMany(Produk::class, 'merek_id');
    }
}