<?php

namespace App\Modules\Incentive\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Incentive\Models\Traits\Attribute\IncentiveStaffAttribute;

class IncentiveStaff extends Model
{
    use IncentiveStaffAttribute, SoftDeletes;

    public $timestamps = false;
    protected $table = 'incentive_staff';
    
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });

        static::updating(function ($model) {
            $model->updated_at = $model->freshTimestamp();
        });
    }

    protected $fillable = [
    	'staff_id',
    	'incentive_id'
    ];

    public function staff()
    {
        return $this->hasOne('App\Modules\Humanresource\Models\Staff', 'id', 'staff_id');
    }

    public function incentive()
    {
        return $this->hasOne('App\Modules\Incentive\Models\Incentive', 'id', 'incentive_id');
    }

    public function details()
    {
        return $this->hasMany('App\Modules\Incentive\Models\IncentiveDetail', 'incentive_id', 'incentive_id');
    }
}
