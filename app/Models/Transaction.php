<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'item_id',
        'jenis',
        'jumlah',
        'tanggal',
        'user_id',
        'keterangan',
    ];

    public function item()
    {
        return $this->belongsTo(Inventaris::class, 'item_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
