<?php

namespace App\Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;

class FinancePurchaseDetail extends Model
{
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
        'purchase_id',
        'items',
        'type',
        'qty',
        'price',
        'price_total',
        'desc',
        'tax_label',
        'tax_value'
    ];
}
