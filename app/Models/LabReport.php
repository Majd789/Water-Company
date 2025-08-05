<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LabReport extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lab_reports';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sample_number',
        'samples_count',
        'analysis_date',
        'water_source',
        // Chemical Analysis Fields
        'temperature',
        'turbidity',
        'ph_level',
        'free_chlorine',
        'residual_chlorine',
        'nitrates',
        'total_hardness',
        'chlorides',
        'sulfates',
        'iron',
        'manganese',
        'chemical_sample_status',
        'chemical_notes',
        // Bacterial Analysis Fields
        'total_coliforms',
        'e_coli',
        'total_germ_count',
        'bacterial_sample_status',
        'bacterial_notes',
        // Summary Fields
        'overall_water_quality',
        'is_syrian_spec_compliant',
        'has_bacterial_contamination',
        'has_chemical_contamination',
        'has_serious_contamination',
        'reporter_name',
        'report_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'analysis_date' => 'date',
        'report_date' => 'date',
        'is_syrian_spec_compliant' => 'boolean',
        'has_bacterial_contamination' => 'boolean',
        'has_chemical_contamination' => 'boolean',
        'has_serious_contamination' => 'boolean',
        'temperature' => 'decimal:2',
        'turbidity' => 'decimal:2',
        'ph_level' => 'decimal:2',
        'free_chlorine' => 'decimal:2',
        'residual_chlorine' => 'decimal:2',
    ];

    /**
     * Get the chlorine monitoring entries for the lab report.
     */
    public function chlorineEntries(): HasMany
    {
        return $this->hasMany(ChlorineMonitoringEntry::class);
    }

    /**
     * Get the needs for the lab report.
     */
    public function needs(): HasMany
    {
        return $this->hasMany(LabReportNeed::class);
    }


    /**
     * Get the recommendations for the lab report.
     */
    public function recommendations(): HasMany
    {
        return $this->hasMany(LabReportRecommendation::class);
    }
}