<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManholeReport extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'manholes_reports'; 
    protected $fillable = [
        'unit_id',
        'station_id',
        'manhole_id',
        'operator_id',
        'report_date',
        'status',
        'stop_reason',
        'has_flow_meter',
        'flow_meter_counter_number_before',
        'flow_meter_counter_number_after',
        'water_flow_m3',
        'water_m3_price',
        'total_water_price',
        'has_water_refill_for_tankers',
        'water_refill_for_tankers_m3',
        'has_free_water_distribution',
        'free_water_distribution_m3',
        'book_number',
        'notes',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    public function station()
    {
        return $this->belongsTo(Station::class);
    }
    public function manhole()
    {
        return $this->belongsTo(Manhole::class);
    }
    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

}
