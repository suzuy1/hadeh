<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StokHabisPakai;
use App\Models\Room;
use App\Models\Unit;
use App\Models\Transaction;
use App\Models\Request as ItemRequest; // Alias Request model to avoid conflict
use App\Observers\InventarisObserver; // Menggunakan Observer
use Illuminate\Database\Eloquent\Attributes\Observed;

#[Observed(InventarisObserver::class)] // Mendaftarkan Observer
class Inventaris extends Model
{
    use HasFactory;

    protected $table = 'inventaris'; // Specify the table name

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_barang',
        'kategori',
        'pemilik',
        'sumber_dana',
        'tahun_beli',
        'nomor_unit',
        'kondisi_baik',
        'kondisi_rusak_ringan',
        'kondisi_rusak_berat',
        'keterangan',
        'lokasi',
        'unit_id',
        'room_id',
        'kode_inventaris', // Meskipun di-generate Observer, tambahkan di sini
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
