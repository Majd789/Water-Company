<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterWell2 extends Model
{
    protected $table = 'water_wells2'; // تحديد اسم الجدول
    use HasFactory;
      
    protected $fillable = [
        'start',
        'end',
        'date',
        'إسم المُشغل المناوب في المنهل',
        'وحدة المياه',
        'البلدة',
        'المحطات',
        'station_code',
        'well_name',
        'الوضع التشغيلي',
        'سبب التوقف',
        'has_flow_meter',
        'flow_meter_start',
        'flow_meter_end',
        'water_sold_quantity',
        'water_price',
        'total_amount',
        'المبلغ ( $ )من المياه المباعة على المنهل',
        'has_vehicle_filling',
        'vehicle_filling_quantity',
        'has_free_filling',
        'free_filling_quantity',
        'entity_for_free_filling',
        'document_number',
        'notes'
    ];
    public function station()
    {
        return $this->belongsTo(Station::class, 'station_code', 'station_code'); // تأكد من أن هذه الأعمدة صحيحة
    }
}
