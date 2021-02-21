<?php

namespace App\Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;

class FinancePurchaseToCash extends Model
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
        'transaction_id',
        'purchase_id',
    ];

    public function financeTrx()
    {
        return $this->hasOne('App\Modules\Accounting\Models\FinanceTransaction', 'id', 'transaction_id');
    }

    public function purchase()
    {
        return $this->hasOne('App\Modules\Accounting\Models\FinancePurchase', 'id', 'purchase_id');
    }
}
