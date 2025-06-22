<?php

namespace App\Exports;

use App\Models\DieselTank;
use App\Models\SolarEnergy;
use App\Models\Transformer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AllDataExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new TownsExport(),
            new StationsExport(),
            new WellsExport(),
            new DieselTanksExport(),
            new DisinfectionPumpsExport(),
            new ElectricityHoursExport(),
            new ElectricityTransformersExport(),
            new ElevatedTanksExport(),
            new FiltersExport(),
            new GenerationGroupsExport(),
            new GroundTanksExport(),
            new HorizontalPumpsExport(),
            new InfiltratorsExport(),
            new ManholesExport(),
            new PrivateWellsExport(),
            new PumpingSectorsExport(),
            new SolarEnergiesExport(),
          
        ];
    }
}
