<?php

namespace App\Imports;

use App\Models\Inventaris;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class InventarisImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Generate kode_inventaris based on the provided data
        $kode_inventaris = sprintf(
            '%s/%s/%s/%s/%03d',
            $row['kategori'],
            $row['pemilik'],
            $row['sumber_dana'],
            $row['tahun_beli'],
            $row['nomor_unit']
        );

        return new Inventaris([
            'kode_inventaris' => $kode_inventaris,
            'nama_barang' => $row['nama_barang'],
            'kategori' => $row['kategori'],
            'pemilik' => $row['pemilik'],
            'sumber_dana' => $row['sumber_dana'],
            'tahun_beli' => $row['tahun_beli'],
            'nomor_unit' => $row['nomor_unit'],
            'kondisi' => $row['kondisi'] ?? 'baik',
            'lokasi' => $row['lokasi'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_barang' => 'required|string|max:100',
            'kategori' => 'required|string|max:10',
            'pemilik' => 'required|string|max:50',
            'sumber_dana' => 'required|string|max:50',
            'tahun_beli' => 'required|date',
            'nomor_unit' => 'required|integer|min:1',
            'kondisi' => 'nullable|string|max:50',
            'lokasi' => 'nullable|string|max:100',
        ];
    }
}
