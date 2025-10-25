<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SafetyProfile extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'safety_profiles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'station_id',
        'has_ppe',
        'ppe_types',
        'ppe_training_provided',
        'has_fire_extinguishers',
        'has_evacuation_plan',
        'chemical_storage_safe',
        'has_warning_signs',
        'has_first_aid_kit',
        'first_aid_training_provided',
        'emergency_numbers_visible',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'has_ppe' => 'boolean',
        'ppe_training_provided' => 'boolean',
        'has_fire_extinguishers' => 'boolean',
        'has_evacuation_plan' => 'boolean',
        'chemical_storage_safe' => 'boolean',
        'has_warning_signs' => 'boolean',
        'has_first_aid_kit' => 'boolean',
        'first_aid_training_provided' => 'boolean',
        'emergency_numbers_visible' => 'boolean',
    ];

    /**
     * Get the station that this safety profile belongs to.
     */
    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class);
    }
}
