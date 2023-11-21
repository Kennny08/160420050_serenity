<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diskon extends Model
{
    use HasFactory;

    public function penjualans()
    {
        return $this->hasMany(Penjualan::class, 'diskon_id');
    }
}
