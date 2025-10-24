<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Inventaris;
use App\Models\User;

class Acquisition extends Model
{
    protected $fillable = [
        'inventaris_id',
        'quantity',
        'acquisition_date',
        'source',
        'price',
        'notes',
        'user_id',
    ];

    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
