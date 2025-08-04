<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
   use HasFactory;

    protected $fillable = [
        'institution_ref_number', 'institution_ref_date', 'hac_ref_number', 'hac_ref_date',
        'name', 'type', 'organization', 'donor', 'total_cost', 'duration_days',
        'start_date', 'end_date', 'supervisor_name', 'supervisor_contact',
        'status', 'phases_count', 'sites_count', 'stations_count',
    ];
    
    // تعريف العلاقة: المشروع الواحد له العديد من الأنشطة
    public function activities()
    {
        return $this->hasMany(ProjectActivity::class);
    }

}
