<?php

namespace App\Imports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Room;
use App\Models\User;

class ItemsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $room = Room::where('name', $row['room_name'])->first();
        $user = User::where('name', $row['responsible_user'])->first(); // Assuming 'Responsible User' is the heading

        return new Item([
            'name' => $row['name'],
            'description' => $row['description'],
            'condition' => $row['condition'] ?? null,
            'quantity' => $row['quantity'],
            'unit' => $row['unit'],
            'acquisition_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['acquisition_date']),
            'room_id' => $room ? $room->id : null,
            'user_id' => $user ? $user->id : null,
        ]);
    }
}
