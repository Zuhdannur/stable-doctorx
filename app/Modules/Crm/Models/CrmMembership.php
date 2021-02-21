<?php

namespace App\Modules\Crm\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Crm\Models\Traits\Attribute\MembershipAttributes;

class CrmMembership extends Model
{
    use SoftDeletes;
    use MembershipAttributes;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
            // $model->created_by = auth()->user()->id;
        });

        static::updating(function ($model) {
            $model->updated_at = $model->freshTimestamp();
            // $model->updated_by = auth()->user()->id;
        });
    }

    protected $fillable = [
        'patient_id',
        'ms_membership_id',
        'total_point',
    ];

    public function patient()
    {
        return $this->hasOne('App\Modules\Patient\Models\Patient', 'id', 'patient_id');
    }

    public function ms_membership()
    {
        return $this->hasOne('App\Modules\Crm\Models\CrmMsMembership', 'id', 'ms_membership_id');
    }

    public function radeem()
    {
        return $this->hasMany('App\Modules\Crm\Models\CrmRadeemPoint', 'membership_id', 'id');
    }

    public function invoice() {
        return $this->hasMany('App\Modules\Billing\Models\Billing','patient_id','patient_id')->where('status','1');
    }
}
