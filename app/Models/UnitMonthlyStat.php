<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitMonthlyStat extends Model
{
    use HasFactory;

    // لمنع أخطاء Mass Assignment
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'year' => 'integer',
        'month' => 'integer',
    ];

    /**
     * Get the unit that owns the stats.
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
     public function getIsTechnicalCompleteAttribute(): bool
    {
        return $this->produced_water_m3 > 0;
    }
      public function getIsSubscribersCompleteAttribute(): bool
    {
        return $this->total_subscribers > 0;
    }
    // =================================================================
    // ACCESSORS: هنا السحر! حسابات تلقائية عند استدعاء الخاصية
    // =================================================================

    /**
     * حساب حصة الفرد من المياه الموزعة (م³/مشترك).
     */
    protected function perCapitaShare(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->active_subscribers > 0
                ? ($this->distributed_water_m3 / $this->active_subscribers)
                : 0,
        );
    }

    /**
     * حساب نسبة الهدر المئوية.
     */
    protected function waterLossPercentage(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->produced_water_m3 > 0
                ? ($this->lost_water_m3 / $this->produced_water_m3) * 100
                : 0,
        );
    }

    /**
     * حساب نسبة الجباية (كفاءة التحصيل المالي).
     */
    protected function collectionEfficiencyPercentage(): Attribute
    {
        $totalBilled = $this->total_paid_amount + $this->total_defaulters_amount;
        return Attribute::make(
            get: fn () => $totalBilled > 0
                ? ($this->total_paid_amount / $totalBilled) * 100
                : 0,
        );
    }
}
