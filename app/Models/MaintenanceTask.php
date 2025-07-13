<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceTask extends Model
{
    use HasFactory;
    protected $fillable = [
        'technician_name',
        'maintenance_date',
        'unit_id',
        'location',
        'fault_description',
        'fault_cause',
        'maintenance_actions',
        'is_fixed',
        'reason_not_fixed',
        'notes',
    ];

     public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

}
