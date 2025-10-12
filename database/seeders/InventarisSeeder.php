<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventarisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Inventaris::create([
            'kode_inventaris' => 'AT/FT/DANA-A/2023/01/001',
            'nama_barang' => 'Pulpen Standard',
            'kategori' => 'AT',
            'pemilik' => 'FT',
            'sumber_dana' => 'DANA-A',
            'tahun_beli' => 2023,
            'kondisi' => 'Baik',
            'lokasi' => 'Rak 1',
            'room_id' => 1, // Laboratorium Komputer
            'unit_id' => 1, // Fakultas Teknik
            'nomor_unit' => 1,
        ]);

        \App\Models\StokHabisPakai::create([
            'id_inventaris' => 1,
            'jumlah_masuk' => 100,
            'tanggal' => now()->toDateString(),
        ]);

        \App\Models\Inventaris::create([
            'kode_inventaris' => 'EL/FE/DANA-B/2024/02/002',
            'nama_barang' => 'Laptop Lenovo',
            'kategori' => 'EL',
            'pemilik' => 'FE',
            'sumber_dana' => 'DANA-B',
            'tahun_beli' => 2024,
            'kondisi' => 'Baik',
            'lokasi' => 'Meja 5',
            'room_id' => 2, // Ruang Kuliah A
            'unit_id' => 2, // Fakultas Ekonomi
            'nomor_unit' => 2,
        ]);

        \App\Models\Inventaris::create([
            'kode_inventaris' => 'PK/LIB/DANA-C/2022/03/003',
            'nama_barang' => 'Meja Kantor',
            'kategori' => 'PK',
            'pemilik' => 'LIB',
            'sumber_dana' => 'DANA-C',
            'tahun_beli' => 2022,
            'kondisi' => 'Rusak Ringan',
            'lokasi' => 'Area Baca',
            'room_id' => 3, // Ruang Administrasi
            'unit_id' => 3, // Perpustakaan
            'nomor_unit' => 3,
        ]);

        \App\Models\Inventaris::create([
            'kode_inventaris' => 'AT/FT/DANA-A/2023/01/004',
            'nama_barang' => 'Spidol Whiteboard',
            'kategori' => 'AT',
            'pemilik' => 'FT',
            'sumber_dana' => 'DANA-A',
            'tahun_beli' => 2023,
            'kondisi' => 'Baik',
            'lokasi' => 'Rak 1',
            'room_id' => 1, // Laboratorium Komputer
            'unit_id' => 1, // Fakultas Teknik
            'nomor_unit' => 4,
        ]);

        \App\Models\StokHabisPakai::create([
            'id_inventaris' => 4,
            'jumlah_masuk' => 50,
            'tanggal' => now()->toDateString(),
        ]);
    }
}
