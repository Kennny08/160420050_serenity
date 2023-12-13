<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanPerawatan extends Model
{
    use HasFactory;

    protected $table = 'penjualan_perawatan';

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id')->withTrashed();;
    }

    public function perawatan()
    {
        return $this->belongsTo(Perawatan::class, 'perawatan_id')->withTrashed();
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    public function slotjams()
    {
        return $this->belongsToMany(SlotJam::class, 'slot_jam_penjualan_perawatan', 'penjualan_perawatan_id', 'slot_jam_id')->withTimestamps();
    }
}