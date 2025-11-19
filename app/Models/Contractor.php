<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contractor extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * كل مقاول لديه العديد من عقود المشاريع
     */
    public function projectContracts()
    {
        return $this->hasMany(ProjectContractor::class);
    }
}
