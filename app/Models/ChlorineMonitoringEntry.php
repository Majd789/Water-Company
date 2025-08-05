<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChlorineMonitoringEntry extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'chlorine_monitoring_entries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lab_report_id',
        'monitoring_date',
        'source_type',
        'points_monitored',
        'free_chlorine_ratio',
        'is_decrease_found',
        'was_treated',
        'executor_entity',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'monitoring_date' => 'date',
        'is_decrease_found' => 'boolean',
        'was_treated' => 'boolean',
        'free_chlorine_ratio' => 'decimal:2',
    ];

    /**
     * Get the lab report that this entry belongs to.
     */
    public function labReport(): BelongsTo
    {
        return $this->belongsTo(LabReport::class);
    }
}