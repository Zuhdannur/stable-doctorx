<?php

namespace App\Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Accounting\Models\FinanceJournal;
use App\Modules\Accounting\Models\FinanceAccount;
use App\Modules\Accounting\Models\Traits\Attribute\FinancePurchaseAttribute;

class FinancePurchase extends Model
{
    use FinancePurchaseAttribute;
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
    ];

    public function financeTrx()
    {
        return $this->hasOne('App\Modules\Accounting\Models\FinanceTransaction', 'id', 'transaction_id');
    }

    public function detail()
    {
        return $this->hasMany('App\Modules\Accounting\Models\FinancePurchaseDetail', 'purchase_id', 'id');
    }

    public function getPotongan()
    {
        return FinanceJournal::where('transaction_id', $this->transaction_id)
            ->where('tags', 'is_potongan')
            ->first();
    }

    public function getBiaya()
    {
        return FinanceJournal::where('transaction_id', $this->transaction_id)
            ->where('tags', 'is_biaya')
            ->first();
    }
}
