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

    public function town() { return $this->belongsTo(Town::class); }
    public function getUnitAttribute()
    {
        return $this->town ? $this->town->unit : null;
    }
    /**
     * كل نشاط له العديد من المهام
     */
    public function tasks() { return $this->hasMany(ContractorTask::class); }
}
