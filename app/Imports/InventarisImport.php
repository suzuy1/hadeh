<?php

namespace App\Imports;

use App\Models\Inventaris;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon; // Tambahkan ini

class InventarisImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Hapus pembuatan kode di sini, biarkan Observer yang menangani
        // $kode_inventaris = ...

        // Pastikan tahun_beli di-parse dengan benar
        $tahunBeli = $row['tahun_beli'];
        if (is_numeric($tahunBeli)) {
             // Handle Excel date serial number
             $tahunBeli = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tahunBeli)->format('Y-m-d');
        } else {
             // Assume string date format YYYY or YYYY-MM-DD etc.
             try {
                 $tahunBeli = Carbon::parse($tahunBeli)->format('Y-m-d');
             } catch (\Exception $e) {
                 // Default ke tanggal hari ini jika format tidak dikenali
                 $tahunBeli = Carbon::now()->format('Y-m-d');
             }
        }


        return new Inventaris([
            // 'kode_inventaris' => $kode_inventaris, // Dihapus, ditangani Observer
            'nama_barang' => $row['nama_barang'],
            'kategori' => $row['kategori'] ?? 'tidak_habis_pakai', // Beri default jika kosong
            'pemilik' => $row['pemilik'],
            'sumber_dana' => $row['sumber_dana'],
            'tahun_beli' => $tahunBeli, // Gunakan tanggal yang sudah diparse
            'nomor_unit' => $row['nomor_unit'],
            // Sesuaikan dengan kolom kondisi baru jika ada di Excel
            'kondisi_baik' => $row['kondisi_baik'] ?? ($row['kondisi'] === 'Baik' ? 1 : 0), // Contoh asumsi
            'kondisi_rusak_ringan' => $row['kondisi_rusak_ringan'] ?? ($row['kondisi'] === 'Rusak Ringan' ? 1 : 0), // Contoh asumsi
            'kondisi_rusak_berat' => $row['kondisi_rusak_berat'] ?? ($row['kondisi'] === 'Rusak Berat' ? 1 : 0), // Contoh asumsi
            'keterangan' => $row['keterangan'] ?? null,
            'lokasi' => $row['lokasi'] ?? null,
            'unit_id' => $this->findOrCreateUnit($row['unit']), // Cari atau buat unit
            'room_id' => $this->findOrCreateRoom($row['ruangan'], $row['unit']), // Cari atau buat ruangan
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'nullable|string|in:tidak_habis_pakai,habis_pakai,aset_tetap',
            'pemilik' => 'required|string|max:255',
            'sumber_dana' => 'required|string|max:255',
            'tahun_beli' => 'required', // Bisa numeric (Excel date) atau string date
            'nomor_unit' => 'required|integer|min:1',
            // Sesuaikan validasi kondisi
            'kondisi_baik' => 'nullable|integer|min:0',
            'kondisi_rusak_ringan' => 'nullable|integer|min:0',
            'kondisi_rusak_berat' => 'nullable|integer|min:0',
            'keterangan' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'unit' => 'required|string|max:255', // Nama unit dari excel
            'ruangan' => 'required|string|max:255', // Nama ruangan dari excel
        ];
    }

    // Helper function to find or create Unit
    private function findOrCreateUnit($nama_unit)
    {
        $unit = \App\Models\Unit::firstOrCreate(['nama_unit' => trim($nama_unit)]);
        return $unit->id;
    }

    // Helper function to find or create Room
     private function findOrCreateRoom($nama_ruangan, $nama_unit_ruangan)
    {
        $unitId = $this->findOrCreateUnit($nama_unit_ruangan); // Get unit ID first
        $room = \App\Models\Room::firstOrCreate(
            ['nama_ruangan' => trim($nama_ruangan), 'unit_id' => $unitId]
        );
        return $room->id;
    }
}
