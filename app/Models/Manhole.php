<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manhole extends Model
{
    use HasFactory;

    protected $fillable = [
        'station_id',
        'unit_id',
        'town_id',
        'manhole_name',
        'status',
        'stop_reason',
        'has_flow_meter',
        'chassis_number',
        'meter_diameter',
        'meter_status',
        'meter_operation_method_in_meter',
        'has_storage_tank',
        'tank_capacity',
        'general_notes',
    ];

    /**
     * العلاقة مع المحطة
     */
    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    /**
     * العلاقة مع الوحدة
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * العلاقة مع البلدة
     */
    public function town()
    {
        return $this->belongsTo(Town::class);
    }
}