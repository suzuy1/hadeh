<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Unit::create([
            'nama_unit' => 'Fakultas Teknik',
        ]);

        \App\Models\Unit::create([
            'nama_unit' => 'Fakultas Ekonomi',
        ]);

        \App\Models\Unit::create([
            'nama_unit' => 'Perpustakaan',
        ]);
    }
}
