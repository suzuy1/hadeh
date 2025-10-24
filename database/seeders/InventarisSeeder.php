<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventaris; // Import model
use App\Models\StokHabisPakai; // Import model

class InventarisSeeder extends Seeder
{
    public function run(): void
    {
        $inventaris1 = Inventaris::create([
            // 'kode_inventaris' => Dihapus, dihandle Observer
            'nama_barang' => 'Pulpen Standard',
            'kategori' => 'habis_pakai', // Sesuaikan
            'pemilik' => 'FT',
            'sumber_dana' => 'DANA-A',
            'tahun_beli' => '2023-01-15', // Format YYYY-MM-DD
            'nomor_unit' => 1,
            'kondisi_baik' => 0, // Untuk habis pakai, jumlah ada di stok
            'kondisi_rusak_ringan' => 0,
            'kondisi_rusak_berat' => 0,
            'lokasi' => 'Rak 1',
            'room_id' => 1,
            'unit_id' => 1,
        ]);

        StokHabisPakai::create([
            'inventaris_id' => $inventaris1->id, // Gunakan ID dari inventaris yang baru dibuat
            'jumlah_masuk' => 100,
            'jumlah_keluar' => 0,
            'tanggal' => now()->toDateString(),
        ]);

        Inventaris::create([
            // 'kode_inventaris' => Dihapus, dihandle Observer
            'nama_barang' => 'Laptop Lenovo',
            'kategori' => 'tidak_habis_pakai', // Sesuaikan
            'pemilik' => 'FE',
            'sumber_dana' => 'DANA-B',
            'tahun_beli' => '2024-03-10', // Format YYYY-MM-DD
            'nomor_unit' => 2,
            'kondisi_baik' => 1, // Jumlah kondisi
            'kondisi_rusak_ringan' => 0,
            'kondisi_rusak_berat' => 0,
            'lokasi' => 'Meja 5',
            'room_id' => 2,
            'unit_id' => 2,
        ]);

        Inventaris::create([
            // 'kode_inventaris' => Dihapus, dihandle Observer
            'nama_barang' => 'Meja Kantor',
            'kategori' => 'aset_tetap', // Sesuaikan
            'pemilik' => 'LIB',
            'sumber_dana' => 'DANA-C',
            'tahun_beli' => '2022-11-20', // Format YYYY-MM-DD
            'nomor_unit' => 3,
            'kondisi_baik' => 0, // Jumlah kondisi
            'kondisi_rusak_ringan' => 1,
            'kondisi_rusak_berat' => 0,
            'lokasi' => 'Area Baca',
            'room_id' => 3,
            'unit_id' => 3,
        ]);

        $inventaris4 = Inventaris::create([
            // 'kode_inventaris' => Dihapus, dihandle Observer
            'nama_barang' => 'Spidol Whiteboard',
            'kategori' => 'habis_pakai', // Sesuaikan
            'pemilik' => 'FT',
            'sumber_dana' => 'DANA-A',
            'tahun_beli' => '2023-01-15', // Format YYYY-MM-DD
            'nomor_unit' => 4,
            'kondisi_baik' => 0,
            'kondisi_rusak_ringan' => 0,
            'kondisi_rusak_berat' => 0,
            'lokasi' => 'Rak 1',
            'room_id' => 1,
            'unit_id' => 1,
        ]);

        StokHabisPakai::create([
            'inventaris_id' => $inventaris4->id, // Gunakan ID dari inventaris yang baru dibuat
            'jumlah_masuk' => 50,
             'jumlah_keluar' => 0,
            'tanggal' => now()->toDateString(),
        ]);
    }
}
