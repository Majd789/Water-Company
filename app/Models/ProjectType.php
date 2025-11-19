<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectType extends Model
{
    // لا يوجد timestamps (created_at, updated_at) لهذا الجدول
    public $timestamps = false;

    protected $guarded = [];
}
