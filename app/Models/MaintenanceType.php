<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceType extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // علاقة بأنواع الصيانة المرتبطة بها
    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }
}
