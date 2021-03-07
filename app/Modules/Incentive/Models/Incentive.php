<?php

namespace App\Modules\Incentive\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Incentive\Models\Traits\Attribute\IncentiveAttribute;

class Incentive extends Model
{
    use IncentiveAttribute, SoftDeletes;

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
    	'name',
    	'description',
        'product_incentive',
        'point_value',
        'id_klinik'
    ];

    public function details()
    {
        return $this->belongsToMany('App\Modules\Incentive\Models\IncentiveDetail', 'patient_media_info', 'patient_id', 'media_id');
    }

    public function getProductIncentivePercentAttribute() {
        return $this->product_incentive ? $this->product_incentive . '%' : '-';
    }
}
