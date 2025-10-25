<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
class StationTeam extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'station_teams';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'station_id',
        'maintenance_team_count',
        'water_quality_team_count',
         'contact_number',
        'admin_team_count',
        'maintenance_team_skills',
        'water_quality_team_skills',
    ];

    /**
     * Get the station that this team belongs to.
     */
    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class);
    }
    public function metrics(): MorphMany
    {
        return $this->morphMany(Metric::class, 'metricable');
    }
     public function assessments(): MorphMany
    {
        return $this->morphMany(Assessment::class, 'assessmentable');
    }
}
