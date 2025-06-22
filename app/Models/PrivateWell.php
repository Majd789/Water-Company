<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivateWell extends Model
{
    use HasFactory;

    protected $fillable = [
        'station_id',
        'well_name',
        'well_count',
        'distance_from_nearest_well',
        'well_type',
    ];

    /**
     * العلاقة مع المحطة
     */
    public function station()
    {
        return $this->belongsTo(Station::class);
    }
}
