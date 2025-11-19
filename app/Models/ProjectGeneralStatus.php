<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectGeneralStatus extends Model
{
    protected $table = 'project_general_statuses';
    public $timestamps = false;
    protected $guarded = [];
}
