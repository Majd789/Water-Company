<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\Models\Town;
use Illuminate\Support\Facades\Auth;

class TownsExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        $userUnitId = Auth::user()->unit_id;

        if ($userUnitId) {
            // تصفية البلدات حسب وحدة المستخدم
            $query = Town::where('unit_id', $userUnitId)->with('unit.governorate'); // تحميل العلاقة مع الوحدة والمحافظة
        } else {
            // في حال عدم وجود وحدة، جلب جميع البلدات مع الوحدات والمحافظات
            $query = Town::with('unit.governorate');
        }

        return $query->get()->map(function ($town) {
            return [
                'ID' => $town->id,
                'اسم البلدة' => $town->town_name,
                'كود البلدة' => $town->town_code,
                'اسم الوحدة' => $town->unit->unit_name ?? 'غير محددة',
                'اسم المحافظة' => $town->unit->governorate->name ?? 'غير محددة', // جلب اسم المحافظة
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'اسم البلدة', 'كود البلدة', 'اسم الوحدة', 'اسم المحافظة']; // إضافة عمود اسم المحافظة
    }

    public function title(): string
    {
        return 'البلدات';
    }
}
