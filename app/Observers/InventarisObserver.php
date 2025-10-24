<?php

namespace App\Observers;

use App\Models\Inventaris;
use Carbon\Carbon;

class InventarisObserver
{
    /**
     * Handle the Inventaris "creating" event.
     *
     * @param  \App\Models\Inventaris  $inventaris
     * @return void
     */
    public function creating(Inventaris $inventaris): void
    {
        // Jika kode inventaris belum di-set (misalnya dari import atau memang baru)
        if (empty($inventaris->kode_inventaris)) {
            $tahunBeli = Carbon::parse($inventaris->tahun_beli)->format('Y');

            // 1. Generate Kode Awal
            $baseKode = sprintf(
                'inv/%s/%s/%s/%03d', // Gunakan %03d untuk 3 digit nomor unit
                strtolower($inventaris->pemilik),
                strtolower($inventaris->sumber_dana),
                $tahunBeli,
                $inventaris->nomor_unit
            );

            // 2. Cek Keunikan dan Tambah Suffix Jika Perlu
            $finalKode = $baseKode;
            $counter = 1;
            // Gunakan query builder untuk mengecek apakah kode sudah ada
            while (\App\Models\Inventaris::where('kode_inventaris', $finalKode)->exists()) {
                $finalKode = $baseKode . '-' . $counter++;
            }

            // 3. Assign kode final ke model SEBELUM disimpan
            $inventaris->kode_inventaris = $finalKode;
        }
    }

    /**
     * Handle the Inventaris "updating" event.
     * (Opsional: Jika ingin kode di-update saat data relevan berubah)
     *
     * @param  \App\Models\Inventaris  $inventaris
     * @return void
     */
    public function updating(Inventaris $inventaris): void
    {
         // Jika field yang mempengaruhi kode berubah
         if ($inventaris->isDirty(['pemilik', 'sumber_dana', 'tahun_beli', 'nomor_unit'])) {
             $tahunBeli = Carbon::parse($inventaris->tahun_beli)->format('Y');
             $newKode = sprintf(
                 'inv/%s/%s/%s/%03d',
                 strtolower($inventaris->pemilik),
                 strtolower($inventaris->sumber_dana),
                 $tahunBeli,
                 $inventaris->nomor_unit
             );

             // Cek keunikan lagi jika perlu
             $originalKode = $newKode;
             $counter = 1;
             while (Inventaris::where('kode_inventaris', $newKode)->where('id', '!=', $inventaris->id)->exists()) {
                 $newKode = $originalKode . '-' . $counter++;
             }
             $inventaris->kode_inventaris = $newKode;
         }
    }
}
