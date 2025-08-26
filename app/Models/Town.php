<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Town extends Model
{
    use HasFactory;

    protected $fillable = ['town_name', 'town_code', 'unit_id'];
    protected $appends = ['is_billing_actually_active'];
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
    

    public function stations()
    {
        return $this->hasMany(Station::class);
    }

   
   
   /**
     * Get the town's actual billing activity status.
     *
     * @return bool
     */
    public function getIsBillingActuallyActiveAttribute()
    {
        // نستخدم استعلام exists() لأنه أسرع بكثير من العد
        return DB::table('properties')
            ->join('subscriptions', 'properties.id', '=', 'subscriptions.property_id')
            ->join('billing_data', 'subscriptions.id', '=', 'billing_data.subscription_id')
            ->where('properties.town_id', $this->id) // نستخدم $this->id هنا
            ->whereIn('billing_data.payment_status', ['سدّد', 'تخلّف'])
            ->exists();
    }
}

