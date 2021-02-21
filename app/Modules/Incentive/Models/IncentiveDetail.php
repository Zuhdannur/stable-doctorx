<?php

namespace App\Modules\Incentive\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncentiveDetail extends Model
{
    use SoftDeletes;
    public $timestamps = false;

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
    	'incentive_id',
    	'type',
    	'entity_id',
    	'value_type',
    	'value'
    ];
}
