<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;
    protected $fillable = [
        'complaint_type_id',
        'town_id',
        'complainant_name',
        'building_code',
        'details',
        'location_type',
        'is_repeated',
        'image_path',
        'status',
    ];
    /**
     * Get the complaint type that owns the complaint.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
     public function complaintType()
        {
        return $this->belongsTo(ComplaintType::class);
    }
    /**
     * Get the town that owns the complaint.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
     public function town()
    {
        return $this->belongsTo(Town::class);
    }
}
