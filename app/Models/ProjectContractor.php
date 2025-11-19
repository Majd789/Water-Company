<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

// مهم: هذا الموديل يجب أن يمتد من Pivot لأنه يمثل الجدول الوسيط
class ProjectContractor extends Pivot
{
    // تفعيل الـ id كـ primary key للجدول الوسيط
    public $incrementing = true;

    protected $table = 'project_contractors';
    protected $guarded = [];

    public function project() { return $this->belongsTo(Project::class); }
    public function contractor() { return $this->belongsTo(Contractor::class); }
    public function contractorStatus() { return $this->belongsTo(ContractorStatus::class); }

    /**
     * كل عقد مقاول له العديد من المهام
     */
    public function tasks() { return $this->hasMany(ContractorTask::class); }
}
