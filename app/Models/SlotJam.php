<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlotJam extends Model
{
    use HasFactory;

    protected $table = "slot_jams";

    public function penjualanserawatans()
    {
        return $this->belongsToMany(PenjualanPerawatan::class, 'slot_jam_penjualan_perawatan', 'slot_jam_id', 'penjualan_perawatan_id')->withTimestamps();
    }
}