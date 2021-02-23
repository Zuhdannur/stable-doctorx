<?php

namespace App\Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceBiayaToCash extends Model
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
        'biaya_trx_id',
        'created_by',
        'updated_by',
    ];

    public function financeTrx()
    {
        return $this->hasOne('App\Modules\Accounting\Models\FinanceTransaction', 'id', 'transaction_id');
    }

    public function biayaTrx()
    {
        return $this->hasOne('App\Modules\Accounting\Models\FinanceBiayaTrx', 'id', 'biaya_trx_id');
    }

}
