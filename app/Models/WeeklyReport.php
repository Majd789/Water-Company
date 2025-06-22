<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'report_date',
        'sender_name',
        'operational_status',
        'stop_reason',
        'maintenance_works',
        'maintenance_entity',
        'maintenance_image',
        'administrative_works',
        'administrative_image',
        'additional_notes',
    ];

    /**
     * العلاقة مع وحدة المياه
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    public function station()
{
    return $this->belongsTo(\App\Models\Station::class);
}

}
