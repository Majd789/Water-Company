<?php

namespace App\Exports;

use App\Models\WaterQualityTest;
use Maatwebsite\Excel\Concerns\FromCollection;

class WaterQualityTestsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return WaterQualityTest::all();
    }
}
