<?php

namespace App\Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Accounting\Models\Traits\Attribute\FinanceAccountAttribute;

class FinanceAccount extends Model
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
        'account_name', 
        'account_code',
    	'account_category_id', 
    	'balance', 
    	'description'
    ];

    public function accountCategory()
    {
    	return $this->hasOne('App\Modules\Accounting\Models\FinanceAccountCategory', 'id', 'account_category_id');
    }

    public static function generateAccountCode($category_id,$category_code)
    {
        $count = self::where('account_category_id',$category_id)->count();
        
        return $category_code.str_pad($count,4 , '0', STR_PAD_LEFT);

    }

    /**
     * @param $id account_id
     * @param $value balance for journal
     */
    public static function sumKredit($id, $value)
    {
        $account = self::findOrfail($id);
        $type = $account->accountCategory->type;
        $balanceNow = $account->balance;

        //update balance account
        switch ($type) {
            case config('finance_account.type.kewajiban'):
                $account->balance = intval($balanceNow + $value);
                break;
            case config('finance_account.type.ekuitas'):
                $account->balance = intval($balanceNow + $value);
                break;
            case config('finance_account.type.pendapatan'):
                $account->balance = intval($balanceNow + $value);
                break;
            case config('finance_account.type.beban'):
                $account->balance = intval($balanceNow - $value);
                break;
            
            default:
                $account->balance = intval($balanceNow - $value);
                break;
        }

        $account->save();

        return $account;
    }

    /**
     * @param $id account_id
     * @param $value balance for journal
     */
    public static function sumDebit($id, $value)
    {
        $account = self::findOrfail($id);
        $type = $account->accountCategory->type;
        $balanceNow = $account->balance;

        //update balance account
        switch ($type) {
            case config('finance_account.type.kewajiban'):
                $account->balance = intval($balanceNow - $value);
                break;
            case config('finance_account.type.ekuitas'):
                $account->balance = intval($balanceNow - $value);
                break;
            case config('finance_account.type.pendapatan'):
                $account->balance = intval($balanceNow - $value);
                break;
            case config('finance_account.type.beban'):
                $account->balance = intval($balanceNow + $value);
                break;
            
            default:
                $account->balance = intval($balanceNow + $value);
                break;
        }

        $account->save();

        return $account;
    }

    public function journal()
    {
        $this->hasMany('App\Modules\Accounting\Models\FinanceJournal', 'id', 'account_id');
    }
    
}
