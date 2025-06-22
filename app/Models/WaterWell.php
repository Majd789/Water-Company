<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterWell extends Model
{
    use HasFactory;

    protected $table = 'water_wells'; // تحديد اسم الجدول

    // تحديد الأعمدة القابلة للملء
    protected $fillable = [
     'station_code', 'well_name', 'has_flow_meter', 'flow_meter_start', 
        'flow_meter_end', 'water_sold_quantity', 'water_price', 'total_amount', 
        'has_vehicle_filling', 'vehicle_filling_quantity', 'has_free_filling', 
        'free_filling_quantity', 'entity_for_free_filling', 'document_number', 'notes'
    ];
}
