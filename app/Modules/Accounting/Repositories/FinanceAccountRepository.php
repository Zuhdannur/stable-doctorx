<?php

namespace App\Modules\Accounting\Repositories;

use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use App\Modules\Attribute\Models\LogActivity;
use App\Modules\Accounting\Models\FinanceAccount; 
use App\Modules\Accounting\Models\FinanceJournal; 
use App\Modules\Accounting\Models\FinanceTransaction; 

class FinanceAccountRepository extends BaseRepository 
{
    public function model()
    {
        return FinanceAccount::class;
    }

    public function create(array $data)
    {
        return DB::transaction(function () use($data){
            $cash = FinanceAccount::create([
                'account_name' => $data['acc_name'],
                'description' => $data['acc_desc'],
                'account_category_id' => $data['category'],
                'balance' => 0,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id
            ]);

            if($cash){
                $acc_code = FinanceAccount::generateAccountCode($data['category'],$cash->accountCategory->category_code);                
                
                FinanceAccount::where('id', $cash->id)->update(['account_code' => $acc_code]);
                
                $log = new LogActivity();
                $log->module_id = config('my-modules.accounting');
                $log->action = "Create Finance Account";
                $log->desc = "Kode Akun : $acc_code, Nama Akun : ".$data['acc_name'];
                
                $log->save();

                return $cash;
            };

            throw new GeneralException(trans('accounting::exceptions.account.create_error'));

        });
    }

    public function createCashAccount(array $data)
    {
        return DB::transaction(function () use($data){
            $cash = FinanceAccount::create([
                'account_name' => $data['acc_name'],
                'description' => $data['acc_desc'],
                'account_category_id' => config('finance_account.default.cash'),
                'balance' => 0,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id
            ]);

            if($cash){
                $acc_code = FinanceAccount::generateAccountCode(8,$cash->accountCategory->category_code);                
                
                FinanceAccount::where('id', $cash->id)->update(['account_code' => $acc_code]);
                
                $log = new LogActivity();
                $log->module_id = config('my-modules.accounting');
                $log->action = "Create Finance Account";
                $log->desc = "Kode Akun : $acc_code, Nama Akun : ".$data['acc_name'];
                
                $log->save();

                return $cash;
            };

            throw new GeneralException(trans('accounting::exceptions.account.create_error'));

        });
    }

    public function update(array $data)
    {
        return DB::transaction(function() use($data){
            $account = FinanceAccount::findOrFail($data['id']);
            $account->account_name = $data['acc_name'];
            $account->description = $data['acc_desc'];
            $account->save();

            $log = new LogActivity();
            $log->module_id = config('my-modules.accounting');
            $log->action = "Update Finance Account";
            $log->desc = "Kode Akun : $account->account_code, Nama Akun : ".$account->account_name;
            
            $log->save();

            return $account;            
        });
    }

    public function storeJournal(array $data)
    {
        return DB::transaction(function () use ($data) {

            $transaction = FinanceTransaction::create([
                'trx_type_id' => config('finance_trx.trx_types.general'), 
                'memo' => $data['notes'],
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id
            ]);

            if($transaction){
                $transaction_code = FinanceTransaction::generateTrxCode($transaction->trx_type_id, $transaction->trxType->label);

                $transaction = FinanceTransaction::find($transaction->id);
                $transaction->transaction_code = $transaction_code;

                $transaction->save();
                $date_arr = explode('/',$data['date_trx']);
                $trx_date ='';

                if(sizeof($date_arr) > 2){
                    $trx_date = $date_arr[2].'-'.$date_arr[1].'-'.$date_arr[0];
                }

                $totalLoop = $data['acc'];
                $kredit = 0;
                $debit = 0;

                //looping for store data to journal
                foreach($totalLoop as $key => $val){

                    //replace 
                    $value = str_replace('.','',$data['total'][$key]);

                    if($data["type"][$key] == config('finance_journal.types.debit')){

                        //update balance account
                         $account = FinanceAccount::sumDebit($val,$value);

                        // save account jurnal
                        $jornals = FinanceJournal::create([
                             'transaction_id' => $transaction->id,
                             'type' => config('finance_journal.types.debit'), 
                             'account_id' => $val,
                             'value' => $value,
                             'balance'  => $account->balance,                      
                             'description' => $data['acc_desc'][$key],                        
                        ]);

                        $debit = intval($debit + $value);
 
                    }else if($data["type"][$key] == config('finance_journal.types.kredit')){
                        
                        //update balance account
                        $account = FinanceAccount::sumKredit($val,$value);

                        // save account jurnal
                        $jornals = FinanceJournal::create([
                             'transaction_id' => $transaction->id,
                             'type' => config('finance_journal.types.kredit'), 
                             'account_id' => $val,
                             'value' => $value,
                             'balance'  => $account->balance,                      
                             'description' => $data['acc_desc'][$key],                        
                        ]);

                        $kredit = intval($kredit + $value);
                    }else{
                        throw new GeneralException(trans('accounting::exceptions.journal.create_error'));
                    }


                }

                if($debit != $kredit){
                    throw new GeneralException(trans('accounting::exceptions.journal.balance_diff'));
                }

                $log = new LogActivity();
                $log->module_id = config('my-modules.accounting');
                $log->action = "Create General Journal";
                $log->desc = "Kode Transaksi : $transaction->transaction_code, Tipe Transaksi : ".config('finance_trx.label_trx_types.'.$transaction->trx_type_id);
                
                $log->save();

                return $transaction;
            }

            throw new GeneralException(trans('accounting::exceptions.journal.create_error'));
        });
    }
}
