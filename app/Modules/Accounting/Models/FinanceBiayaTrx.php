<?php

namespace App\Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Accounting\Models\Traits\Attribute\FinanceBiayaTrxAttribute;

class FinanceBiayaTrx extends Model
{
    use FinanceBiayaTrxAttribute;
    
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
        'status',
        'due_date',
        'remain_payment',
        'total',
        'created_by',
        'updated_by',
    ];

    public function financeTrx()
    {
        return $this->hasOne('App\Modules\Accounting\Models\FinanceTransaction', 'id', 'transaction_id');
    }

    public function user()
    {
        return $this->hasOne('App\Models\Auth\User', 'id', 'updated_by');
    }
}
