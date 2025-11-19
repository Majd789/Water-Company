<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectActivity extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function project() { return $this->belongsTo(Project::class); }
    public function masterActivity() { return $this->belongsTo(MasterActivity::class); }
    public function unit() { return $this->belongsTo(Unit::class); } // الربط مع موديل Unit الموجود لديك
    public function station() { return $this->belongsTo(Station::class); } // الربط مع موديل Station الموجود لديك

    /**
     * كل نشاط له العديد من المهام
     */
    public function tasks() { return $this->hasMany(ContractorTask::class); }
}
