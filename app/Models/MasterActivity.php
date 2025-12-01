<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterActivity extends Model
{
    protected $table = 'master_activities';
    use HasFactory;
    protected $fillable = [
    'name',
    'code',
    'unit'

];
    protected $guarded = [];
}
