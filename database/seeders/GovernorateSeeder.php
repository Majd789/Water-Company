<?php

namespace Database\Seeders;

use App\Models\Governorate;
use Illuminate\Database\Seeder;

class GovernorateSeeder extends Seeder
{
    public function run()
    {
        $governorates = [
            'دمشق', 'ريف دمشق', 'حلب', 'حمص', 'حماة', 'اللاذقية',
            'طرطوس', 'إدلب', 'دير الزور', 'الرقة', 'الحسكة', 'السويداء', 'درعا', 'القنيطرة'
        ];

        foreach ($governorates as $name) {
            Governorate::create(['name' => $name]);
        }
    }
}
