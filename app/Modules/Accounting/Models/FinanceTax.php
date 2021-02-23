<?php

namespace App\Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Accounting\Models\Traits\Attribute\FinanceTaxAttribute;

class FinanceTax extends Model
{
    use FinanceTaxAttribute;
    
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
        'tax_name', 
        'account_tax_sales', //for billing
        'account_tax_purchase', //for purchase
        'percentage',
        'created_by',
        'updated_by',
    ];

    public function accSales()
    {
        return $this->hasOne('App\Modules\Accounting\Models\FinanceAccount','id','account_tax_sales');
    }

    public function accPurchase()
    {
        return $this->hasOne('App\Modules\Accounting\Models\FinanceAccount','id','account_tax_purchase');
    }

    public static function optionList()
    {
        $tax = self::get();
        $taxList ='<option></option>';
        foreach($tax as $val){
            $taxList .= '<option value="'.$val->id.'" data-tax="'.$val->percentage.'">'.strtoupper($val->tax_name).'</option>';
        }

        return $taxList;
    }
}
