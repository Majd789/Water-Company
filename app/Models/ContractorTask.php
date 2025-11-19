<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorTask extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function projectActivity() { return $this->belongsTo(ProjectActivity::class); }
    public function projectContractor() { return $this->belongsTo(ProjectContractor::class); }
}
