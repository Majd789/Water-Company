<?php

namespace App\Imports;

use App\Models\Town;

use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class TownsImport implements ToCollection
{
    public function collection(Collection $rows)
{
    $rows->shift(); // حذف الصف الأول (العناوين)

    foreach ($rows as $row) {
        try {
            Town::create([
                'town_name' => $row[0], // العمود الأول
                'town_code' => $row[1], // العمود الثاني
                'unit_id' => $row[2], // العمود الثالث
            ]);
        } catch (\Exception $e) {
            Log::error('Error importing town: ' . $e->getMessage());
        }
    }
}
}
