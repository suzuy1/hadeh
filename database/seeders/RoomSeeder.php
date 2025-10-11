<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Room::create([
            'nama_ruangan' => 'Laboratorium Komputer',
            'lokasi' => 'Gedung A Lantai 1',
            'unit_id' => 1,
        ]);

        \App\Models\Room::create([
            'nama_ruangan' => 'Ruang Kuliah A',
            'lokasi' => 'Gedung B Lantai 2',
            'unit_id' => 2,
        ]);

        \App\Models\Room::create([
            'nama_ruangan' => 'Ruang Administrasi',
            'lokasi' => 'Gedung C Lantai 1',
            'unit_id' => 3,
        ]);

        \App\Models\Room::create([
            'nama_ruangan' => 'Gudang',
            'lokasi' => 'Belakang Gedung A',
            'unit_id' => 1,
        ]);

        \App\Models\Room::create([
            'nama_ruangan' => 'Ruang Rapat',
            'lokasi' => 'Gedung B Lantai 3',
            'unit_id' => 2,
        ]);
    }
}
