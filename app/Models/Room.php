<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Inventaris;

class Room extends Model
{
    protected $fillable = [
        'nama_ruangan',
        'lokasi',
        'unit_id',
    ];

    public function items()
    {
        return $this->hasMany(Inventaris::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
