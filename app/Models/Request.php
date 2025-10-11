<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $fillable = [
        'item_id',
        'jumlah',
        'tanggal_request',
        'status',
        'requester_id',
        'approver_id',
    ];

    public function item()
    {
        return $this->belongsTo(Inventaris::class, 'item_id', 'id');
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
