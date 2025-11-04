<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'nama_unit',
    ];

    public function inventaris()
    {
        return $this->hasMany(Inventaris::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
