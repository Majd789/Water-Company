<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectMainStatus extends Model
{
    protected $table = 'project_main_statuses'; // تحديد اسم الجدول يدوياً
    public $timestamps = false;
    protected $guarded = [];
}
