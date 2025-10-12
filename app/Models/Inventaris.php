<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\StokHabisPakai;
use App\Models\Room;
use App\Models\Unit;
use App\Models\Transaction;
use App\Models\Request as ItemRequest; // Alias Request model to avoid conflict

class Inventaris extends Model
{
    protected $table = 'inventaris'; // Specify the table name

    protected $fillable = [
        'kategori',
        'pemilik',
        'sumber_dana',
        'tahun_beli',
        'nomor_unit',
        'kode_inventaris',
        'nama_barang',
        'kondisi',
        'lokasi',
        'unit_id',
        'room_id',
    ];

    // Relationships

    public function stokHabisPakai()
    {
        return $this->hasMany(StokHabisPakai::class, 'id_inventaris', 'id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'item_id', 'id'); // Assuming transactions still use item_id
    }

    public function requests()
    {
        return $this->hasMany(ItemRequest::class, 'item_id', 'id'); // Assuming requests still use item_id
    }
}
