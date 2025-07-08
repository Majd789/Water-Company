<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = ['unit_name', 'general_notes', 'governorate_id'];

    public function towns()
    {
        return $this->hasMany(Town::class);
    }
    public function users()
{
    return $this->hasMany(User::class);
}

public function governorate()
{
    return $this->belongsTo(Governorate::class);
}
 public function stations()
    {
        // نصل إلى نموذج Station::class من خلال نموذج Town::class
        return $this->hasManyThrough(Station::class, Town::class);
    }

}
