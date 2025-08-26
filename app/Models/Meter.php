<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meter extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'serial_number',
        'diameter',
        'installation_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'installation_date' => 'date',
    ];

    /**
     * Get the subscriptions associated with the meter.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}