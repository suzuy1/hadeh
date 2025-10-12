<?php

namespace App\Exports;

use App\Models\Inventaris;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InventarisExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Inventaris::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Kode Inventaris',
            'Nama Barang',
            'Kategori',
            'Pemilik',
            'Sumber Dana',
            'Tahun Beli',
            'Nomor Unit',
            'Kondisi',
            'Lokasi',
            'Created At',
            'Updated At',
        ];
    }

    public function map($inventaris): array
    {
        return [
            $inventaris->id,
            $inventaris->kode_inventaris,
            $inventaris->nama_barang,
            $inventaris->kategori,
            $inventaris->pemilik,
            $inventaris->sumber_dana,
            $inventaris->tahun_beli,
            $inventaris->nomor_unit,
            $inventaris->kondisi,
            $inventaris->lokasi,
            $inventaris->created_at,
            $inventaris->updated_at,
        ];
    }
}
