<?php

namespace App\Modules\Accounting\Repositories;

use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;

// model
use App\Modules\Accounting\Models\FinanceTax;
use App\Modules\Attribute\Models\LogActivity;
use App\Modules\Accounting\Models\FinanceAccount;
use App\Modules\Accounting\Models\FinanceJournal;
use App\Modules\Accounting\Models\FinanceTransaction;

class FinanceCashTransactionRepository extends BaseRepository 
{
    public function model()
    {
        return FinanceTransaction::class;
    }

    public function storeReceipt(array $data)
    {
        return DB::transaction(function () use($data){
            $date_arr = explode('/',$data['date_trx']);
            $trx_date ='';
            if(sizeof($date_arr) > 2){
                $trx_date = $date_arr[2].'-'.$date_arr[1].'-'.$date_arr[0];
            }

            $transaction = FinanceTransaction::create([
                'trx_type_id' => config('finance_trx.trx_types.receive'),
                'memo' => $data['notes'],
                'person' => $data['buyer'],
                'trx_date' => $trx_date,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id
            ]);

            if($transaction){
                $transaction_code = FinanceTransaction::generateTrxCode($transaction->trx_type_id, $transaction->trxType->label);

                $transaction = FinanceTransaction::find($transaction->id);
                $transaction->transaction_code = $transaction_code;

                $transaction->save();
                
                
        
                $totalloop = $data['acc_receipt'];
                $totalCash = 0;
                    
                foreach($totalloop as $key => $val){
                    $data['total'][$key] = str_replace('.','',$data['total'][$key]);
                    $tax_journal = '';
                    $total_tax = 0;

                    //add and update tax account
                    if(isset($data['tax_form'][$key])){
                        $tax = FinanceTax::find($data['tax_form'][$key]);
                        // dd($tax);
                             
                        if($tax){
                            $total_tax = intval($data['total'][$key] * $tax->percentage / 100);

                            $tax_sales = FinanceAccount::sumDebit($tax->account_tax_purchase, $total_tax);

                            $updateJournal = FinanceJournal::create([
                                'transaction_id' => $transaction->id,
                                'type' => config('finance_journal.types.debit'),
                                'account_id' => $tax->account_tax_purchase,
                                'value' => $total_tax,
                                'tax'  => '',      
                                'tags' => 'is_tax',                
                                'balance'  => $tax_sales->balance,                      
                                'description' => '',  
                            ]);

                                
                            $tax_journal = $tax->tax_name."-$tax->percentage".'%';
                        }
                    }
                        
                    //update balance account
                    $account = FinanceAccount::sumKredit($val, $data['total'][$key]);

                    // save account jurnal
                    $jornals = FinanceJournal::create([
                        'transaction_id' => $transaction->id,
                        'type' => config('finance_journal.types.kredit'), 
                        'account_id' => $val,
                        'value' => $data['total'][$key],
                        'tax'  => $tax_journal,  
                        'tags' => 'is_receipt',                    
                        'balance'  => $account->balance,                      
                        'description' => $data['acc_desc'][$key],                        
                    ]);

                    $totalCash = $totalCash + $data['total'][$key] + $total_tax;
                };

                // update cash balance
                $cash = FinanceAccount::sumDebit($data['acc_cash'], $totalCash);
                 
                // save account jurnal
                $jornals = FinanceJournal::create([
                        'transaction_id' => $transaction->id,
                        'type' => config('finance_journal.types.debit'), 
                        'account_id' => $data['acc_cash'],
                        'value' => $totalCash,
                        'tax'  => '',  
                        'tags' => 'is_cash',                    
                        'balance'  => $cash->balance,                      
                        'description' => '',                        
                ]);
                
                $log = new LogActivity();
                $log->module_id = config('my-modules.accounting');
                $log->action = "Create Receive Payment";
                $log->desc = "Kode Transaksi : $transaction->transaction_code, Tipe Transaksi : ".config('finance_trx.label_trx_types.'.$transaction->trx_type_id);

                $log->save();
                
                return $transaction;
            }

            throw new GeneralException(trans('accounting::exceptions.receipt.create_error'));
        });
    }

    public function storeTransfer(array $data)
    {
        return DB::transaction(function () use($data) {
            $date_arr = explode('/',$data['date_trx']);
            $trx_date = '';
            if(sizeof($date_arr) > 2){
                $trx_date = $date_arr[2].'-'.$date_arr[1].'-'.$date_arr[0];
            }

            $transaction = FinanceTransaction::create([
                'trx_type_id' => config('finance_trx.trx_types.transfer'),
                'memo' => $data['notes'],
                'person' => '-',
                'trx_date' => $trx_date,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id
            ]);

            if($transaction){
                // update transaction code
                $transaction_code = FinanceTransaction::generateTrxCode($transaction->trx_type_id, $transaction->trxType->label);

                $transaction = FinanceTransaction::find($transaction->id);
                $transaction->transaction_code = $transaction_code;

                $transaction->save();

                $data['total'] = str_replace('.','',$data['total']);
                //update balance account transfer
                $transfer = FinanceAccount::sumKredit($data['acc_cash'], $data['total']);

                // save account transfer jurnal
                $jornals = FinanceJournal::create([
                    'transaction_id' => $transaction->id,
                    'type' => config('finance_journal.types.kredit'),
                    'account_id' => $data['acc_cash'],
                    'value' => $data['total'],
                    'tags' => 'is_cash',
                    'balance'  => $transfer->balance,                      
                ]);

                //update balance receipt account
                $receipt = FinanceAccount::sumDebit($data['acc_trf'], $data['total']);
                  
                // save account receipt to journal
                $jornalReceipts = FinanceJournal::create([
                    'transaction_id' => $transaction->id,
                    'type' => config('finance_journal.types.debit'),
                    'account_id' => $data['acc_trf'],
                    'value' => $data['total'],
                    'tags' => 'is_trf',
                    'balance' => $receipt->balance
                ]);

                $log = new LogActivity();
                $log->module_id = config('my-modules.accounting');
                $log->action = "Create Transfer Payment";
                $log->desc = "Kode Transaksi : $transaction->transaction_code, Tipe Transaksi : ".config('finance_trx.label_trx_types.'.$transaction->trx_type_id);

                $log->save();

                return $transaction;
            }

            throw new GeneralException(trans('accounting::exceptions.transfer.create_error'));
        });
    }

    public function storesend(array $data)
    {
        return DB::transaction(function () use($data) {
            
            $date_arr = explode('/',$data['date_trx']);
            $trx_date ='';
            $potongan_percentage = 0;
            $savePotongan = false;
            
             // store potongan
             if(isset($data['potongan'])){
                $data['potongan_value'] = str_replace('.', '', $data['potongan_value']);

                $savePotongan = true;
                $potongan_percentage = $data['potongan_value'] / 100;

            }

            if(sizeof($date_arr) > 2){
                $trx_date = $date_arr[2].'-'.$date_arr[1].'-'.$date_arr[0];
            }

            $transaction = FinanceTransaction::create([
                'trx_type_id' => config('finance_trx.trx_types.send'), 
                'memo' => $data['notes'],
                'person' => $data['receiver'],
                'trx_date' => $trx_date,
                'potongan' => ( isset($data['potongan_value']) ? $data['potongan_value'] : '' ),
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id
            ]);
            
            if($transaction){
                $transaction_code = FinanceTransaction::generateTrxCode($transaction->trx_type_id, $transaction->trxType->label);

                $transaction = FinanceTransaction::find($transaction->id);
                $transaction->transaction_code = $transaction_code;

                $transaction->save();

                $totalloop = $data['acc_purchase'];
                $totalCash = (int) "0";
                $total_tax = (int) "0";
                foreach($totalloop as $key => $val){
                    $tax_journal = '';
                    $data['total'][$key] = str_replace('.','', $data['total'][$key]);

                    //add and update tax account
                    if(isset($data['tax_form'][$key])){
                        $tax = FinanceTax::find($data['tax_form'][$key]);
                        // dd($tax);
                             
                        if($tax){
                            // tax total
                            $total_tax = intval($data['total'][$key] * $tax->percentage / 100);

                            $tax_sales = FinanceAccount::sumDebit($tax->account_tax_purchase, $total_tax);

                            $updateJournal = FinanceJournal::create([
                                'transaction_id' => $transaction->id,
                                'type' => config('finance_journal.types.debit'),
                                'account_id' => $tax->account_tax_purchase,
                                'value' => $total_tax,
                                'tax'  => '',    
                                'tags' => 'is_tax',                  
                                'balance'  => $tax_sales->balance,                      
                                'description' => '',  
                            ]);

                                
                            $tax_journal = $tax->tax_name."-$tax->percentage".'%';
                        }
                    }
                        
                    //update balance account
                    $account = FinanceAccount::sumDebit($val, $data['total'][$key]);

                    // save account jurnal
                    $jornals = FinanceJournal::create([
                        'transaction_id' => $transaction->id,
                        'type' => config('finance_journal.types.debit'), 
                        'account_id' => $val,
                        'value' => $data['total'][$key],
                        'tax'  => $tax_journal,   
                        'tags' => 'is_purchase',                   
                        'balance'  => $account->balance,                      
                        'description' => $data['acc_desc'][$key],                        
                    ]);

                    $totalCash = $totalCash + $data['total'][$key] + $total_tax;
                };

                $potongan_total = intval($totalCash * $potongan_percentage);
                    
                // create journal potongan and update account potongan balanced
                if($savePotongan){
                    $potongan = FinanceAccount::sumKredit($data['potongan'], $potongan_total);

                    $potonganJournal = FinanceJournal::create([
                        'transaction_id' => $transaction->id,
                        'type' => config('finance_journal.types.kredit'), 
                        'account_id' =>  $data['potongan'],
                        'value' => $potongan_total,
                        'tax'  => '',       
                        'tags' => 'is_potongan',               
                        'balance'  => $potongan->balance,                      
                        'description' => '',                        
                    ]);
                }
                // update cash balance
                $cash = FinanceAccount::sumKredit($data['acc_cash'], $totalCash);
                
                $totalCash = $totalCash - $potongan_total;

                // save account jurnal
                $jornals = FinanceJournal::create([
                    'transaction_id' => $transaction->id,
                    'type' => config('finance_journal.types.kredit'), 
                    'account_id' => $data['acc_cash'],
                    'value' => $totalCash,
                    'tax'  => '',   
                    'tags' => 'is_cash',                   
                    'balance'  => $cash->balance,                      
                    'description' => '',                        
                ]);

                $log = new LogActivity();
                $log->module_id = config('my-modules.accounting');
                $log->action = "Create Payment Send";
                $log->desc = "Kode Transaksi : $transaction->transaction_code, Tipe Transaksi : ".config('finance_trx.label_trx_types.'.$transaction->trx_type_id);

                $log->save();

                return $transaction;
            }

            throw new GeneralException(trans('accounting::exceptions.send.create_error'));
        });
    }
}
