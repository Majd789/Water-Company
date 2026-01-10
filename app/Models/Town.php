<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Town extends Model
{
    use HasFactory;

    protected $fillable = ['town_name', 'town_code', 'unit_id'];
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }


    public function stations()
    {
        return $this->hasMany(Station::class);
    }
     public function projectActivities()
    {
        // هذه العلاقة تعني أن "القرية" تحتوي على "عدة أنشطة مشاريع"
        return $this->hasMany(ProjectActivity::class, 'town_id', 'id');
    }
}

