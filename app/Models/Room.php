<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Item;

class Room extends Model
{
    protected $fillable = [
        'nama_ruangan',
        'lokasi',
        'unit_id',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
