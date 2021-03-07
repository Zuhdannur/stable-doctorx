<?php

namespace App\Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceTransaction extends Model
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
        'transaction_code',
        'trx_type_id',
        'attachment_file',
        'person',
        'memo',
        'person_id',
        'person_type',
        'trx_date',
        'potongan',
        'created_by',
        'updated_by',
    ];

    public function trxType()
    {
        return $this->hasOne('App\Modules\Accounting\Models\FinanceTrxType','id','trx_type_id');
    }

    public function journal()
    {
        return $this->hasMany('App\Modules\Accounting\Models\FinanceJournal','transaction_id','id');
    }

    public function biaya()
    {
        return $this->hasOne('App\Modules\Accounting\Models\FinanceBiayaTrx','transaction_id','id');

    }

    public function biayaPayment()
    {
        return $this->hasOne('App\Modules\Accounting\Models\FinanceBiayaToCash','transaction_id','id');

    }

    public function purchase()
    {
        return $this->hasOne('App\Modules\Accounting\Models\FinancePurchase','transaction_id','id');
    }

    public function purchasePayment()
    {
        return $this->hasOne('App\Modules\Accounting\Models\FinancePurchaseToCash','transaction_id','id');
    }

    public function cash()
    {
        return $this->hasOne('App\Modules\Accounting\Models\FinanceCashTransaction','transaction_id','id');
    }

    public static function generateTrxCode($type_id, $label){
        $count = self::where('trx_type_id', $type_id)->count() + 1;
        return $label."#".$count;
    }

    public function user()
    {
        return $this->hasOne('App\Models\Auth\User', 'id', 'created_by');
    }
}
