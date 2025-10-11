<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ItemsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Item::with(['room', 'user'])->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Description',
            'Condition',
            'Quantity',
            'Unit',
            'Acquisition Date',
            'Room Name',
            'Responsible User',
            'Created At',
            'Updated At',
        ];
    }

    /**
     * @param mixed $item
     * @return array
     */
    public function map($item): array
    {
        return [
            $item->id,
            $item->name,
            $item->description,
            $item->condition ?? 'N/A',
            $item->quantity,
            $item->unit,
            $item->acquisition_date,
            $item->room->name ?? 'N/A',
            $item->user->name ?? 'N/A',
            $item->created_at,
            $item->updated_at,
        ];
    }
}
