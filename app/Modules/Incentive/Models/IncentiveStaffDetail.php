<?php

namespace App\Modules\Incentive\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncentiveStaffDetail extends Model
{
    use SoftDeletes;
    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    protected $fillable = [
        'invoice_id',
        'staff_id',
        'incentive_id',
        'type',
        'entity_id',
        'value_type',
        'value',
        'price',
        'incenvite_value',
        'date'
    ];

    public function invoice()
    {
        return $this->hasOne('App\Modules\Billing\Models\Billing', 'id', 'invoice_id');
    }

    public function service()
    {
        return $this->hasOne('App\Modules\Product\Models\Service', 'id', 'entity_id');
    }

    public function product()
    {
        return $this->hasOne('App\Modules\Product\Models\Product', 'id', 'entity_id');
    }
}
