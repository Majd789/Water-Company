<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectActivity extends Model
{
    use HasFactory;
    
    // يجب استخدام اسم الجدول الصريح لأن لارافيل قد يفترضه activities
    protected $table = 'project_activities';

    protected $fillable = [
        'project_id',
        'unit_id',
        'town_id',
        'station_id',
        'value',
        'activity_name',
        'activity_count',
        'activity_unit',
        'activity_quantity',
        'execution_status',
        'contractor_name',
        'contractor_contact',
        'work_start_date',
        'work_duration_days',
        'executed_count',
        'executed_unit',
        'executed_quantity',
        'final_acceptance_status',
        'actual_end_date',
        'work_item_activity',
        'work_item_count',
        'work_item_unit',
        'work_item_quantity',
        'notes',
    ];

    // تعريف العلاقة العكسية: النشاط الواحد ينتمي لمشروع واحد
      public function project() { return $this->belongsTo(Project::class); }

    // العلاقات الجديدة
    public function unit() { return $this->belongsTo(Unit::class); }
    public function town() { return $this->belongsTo(Town::class); }
    public function station() { return $this->belongsTo(Station::class); }
}