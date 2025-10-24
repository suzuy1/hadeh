<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $fillable = [
        'inventaris_id',
        'jumlah',
        'tanggal_request',
        'status',
        'requester_id',
        'approver_id',
    ];

    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
