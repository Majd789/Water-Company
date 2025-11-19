<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $guarded = [];

    // تعريف العلاقات (Belongs To)
    public function organization() { return $this->belongsTo(Organization::class); }
    public function projectType() { return $this->belongsTo(ProjectType::class); }
    public function mainStatus() { return $this->belongsTo(ProjectMainStatus::class, 'main_status_id'); }
    public function generalStatus() { return $this->belongsTo(ProjectGeneralStatus::class, 'general_status_id'); }
    public function handoverStatus() { return $this->belongsTo(HandoverStatus::class); }

    // تعريف العلاقات (Has Many)
    public function activities() { return $this->hasMany(ProjectActivity::class); }
    public function projectContracts() { return $this->hasMany(ProjectContractor::class); }
}
