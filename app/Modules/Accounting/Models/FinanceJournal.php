<?php

namespace App\Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Accounting\Models\Traits\Attribute\FinanceAccountAttribute;

class FinanceJournal extends Model
{
    use FinanceAccountAttribute;

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
        'account_id',
        'balance',
        'type',
        'value',
        'tax',
        'description',
        'tags',
    ];

    public function account()
    {
        return $this->hasOne('App\Modules\Accounting\Models\FinanceAccount','id','account_id');
    }

    public function transaction()
    {
        return $this->hasOne('App\Modules\Accounting\Models\FinanceTransaction','id','transaction_id');
    }

}
