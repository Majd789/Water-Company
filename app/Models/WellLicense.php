<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WellLicense extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'well_licenses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'archive_code',
        'property_number',
        'property_zone',
        'applicant_name',
        'request_type',
        'institution_letter_date',
        'directorate_letter_number',
        'directorate_letter_date',
        'declared_distance_meters',
        'station_id',
        'latitude',
        'longitude',
        'physical_cabinet',
        'physical_shelf',
        'physical_file_id',
        'notes',
        'file_url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'institution_letter_date' => 'date',
        'directorate_letter_date' => 'date',
    ];

    /**
     * Define the allowed request types as a constant.
     * This makes it easy to manage them from one place.
     */
    public const REQUEST_TYPES = [
        'حفر',
        'تجديد',
        'تسوية',
    ];


    /**
     * Get the station that the well license belongs to.
     */
    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class);
    }
}
