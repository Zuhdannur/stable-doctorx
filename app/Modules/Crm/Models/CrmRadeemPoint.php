<?php

namespace App\Modules\Crm\Models;

use Illuminate\Database\Eloquent\Model;

class CrmRadeemPoint extends Model
{
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
            $model->created_by = auth()->user()->id;
        });

        static::updating(function ($model) {
            $model->updated_at = $model->freshTimestamp();
            $model->updated_by = auth()->user()->id;
        });
    }

    protected $fillable = [
        'membership_id',
        'item_code',
        'ammount',
        'point',
        'nominal',
        'invoice_id'
    ];

    public function user()
    {
        return $this->hasOne('App\Models\Auth\User', 'id', 'created_by');
    }

    public function membership()
    {
        return $this->hasOne('App\Modules\Crm\Models\CrmMembership', 'id', 'membership_id');
    }

    function membershipCategory(){
        return $this->hasManyThrough(
            'App\Modules\Crm\Models\CrmMsMembership','App\Modules\Crm\Models\CrmMembership',
            'ms_membership_id',
            'id',
            'membership_id'
        );
    }

    public function invoice()
    {
        return $this->hasOne('App\Modules\Billing\Models\Billing', 'id', 'invoice_id');
    }
}
