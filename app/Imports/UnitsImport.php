<?php

namespace App\Imports;

use App\Models\Unit;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class UnitsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $rows->shift(); // حذف الصف الأول (العناوين)

        foreach ($rows as $row) {
            Unit::create([
                'unit_name'     => $row[0], // العمود الأول
                'general_notes' => $row[1] ?? null, // العمود الثاني (اختياري)
            ]);
        }
    }
}
