<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabReportNeed extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lab_report_needs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lab_report_id',
        'need_type',
        'description',
        'maintenance_type',
        'notes',
    ];

    /**
     * Get the lab report that this need belongs to.
     */
    public function labReport(): BelongsTo
    {
        return $this->belongsTo(LabReport::class);
    }
}