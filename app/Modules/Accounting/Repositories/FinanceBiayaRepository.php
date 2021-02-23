<?php

namespace App\Modules\Accounting\Repositories;

use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;

use App\Modules\Accounting\Models\FinanceAccount;
use App\Modules\Accounting\Models\FinanceTransaction;
use App\Modules\Accounting\Models\FinanceJournal;
use App\Modules\Accounting\Models\FinanceTax;
use App\Modules\Accounting\Models\FinanceBiayaTrx;
use App\Modules\Accounting\Models\FinanceBiayaToCash;

class FinanceBiayaRepository extends BaseRepository 
{
    public function model()
    {
        return FinanceBiayaTrx::class;
    }

    public function store(array $data)
    {   
        return DB::transaction(function () use($data) {
            $date_arr = explode('/',$data['date_trx']);
            $trx_date ='';
            if(sizeof($date_arr) > 2){
                $trx_date = $date_arr[2].'-'.$date_arr[1].'-'.$date_arr[0];
            }

            $potongan_percentage = 0;
            $savePotongan = false;

            // calc potongan
            if(isset($data['potongan'])){
                $data['potongan_value'] = str_replace('.', '', $data['potongan_value']);
                
                $savePotongan = true;
                $potongan_percentage = $data['potongan_value'] / 100;

            }

            $transaction = FinanceTransaction::create([
                'trx_type_id' => config('finance_trx.trx_types.biaya'), 
                'memo' => $data['notes'],
                'person' => $data['receiver'],
                'trx_date' =>$trx_date,
                'potongan' => ( isset($data['potongan_value']) ? $data['potongan_value'] : '' ),
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id
            ]);
            // dd($transaction);
            if($transaction){
                $transaction_code = FinanceTransaction::generateTrxCode($transaction->trx_type_id, $transaction->trxType->label);

                $transaction = FinanceTransaction::find($transaction->id);
                $transaction->transaction_code = $transaction_code;

                $transaction->save();

                $due_date ='';
                $status = 1; //status 1 = lunas ,0 == belum lunas

                if(isset($data['is_due_date']) && $data['is_due_date'] == 'on'){
                    $date_arr = explode('/',$data['date_trx']);
                    if(sizeof($date_arr) > 2){
                        $due_date = $date_arr[2].'-'.$date_arr[1].'-'.$date_arr[0];
                    }
                    $status = 0;
                }

                $biayaTrx = FinanceBiayaTrx::create([
                    'transaction_id' => $transaction->id,
                    'status' => $status,
                    'due_date' => $due_date,
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                ]);

                if($biayaTrx){
                    
                    $totalloop = $data['acc_biaya'];
                    $totalCash = (int) "0";
                    $total_tax = (int) "0";

                    //store biaya to jurnal
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
                            'tags' => 'is_biaya',                     
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
                            'account_id' => $data['potongan'],
                            'value' => $potongan_total,
                            'tax'  => '',  
                            'tags' => 'is_potongan',                    
                            'balance'  => $potongan->balance,                      
                            'description' => '',                        
                        ]);
                    }

                    if($status == 0){ // O code for bayar nanti

                        // update hutang balance
                        $valueHutang = intval($totalCash + $potongan_total);
                        $hutang = FinanceAccount::sumKredit(config('finance_account.default.acc_hutang'), $valueHutang);
                       
                        // save account jurnal
                        $jornals = FinanceJournal::create([
                            'transaction_id' => $transaction->id,
                            'type' => config('finance_journal.types.kredit'), 
                            'account_id' => $hutang->id,
                            'value' => $totalCash,
                            'tax'  => '',    
                            'tags' => 'is_hutang',              
                            'balance'  => $hutang->balance,                      
                            'description' => '',                        
                        ]);

                        $biayaTrx->remain_payment = $totalCash + $potongan_total;
                        $biayaTrx->total = $totalCash + $potongan_total;
                        $biayaTrx->save();

                    }else{

                        // update cash balance
                        $cashValue = intval($totalCash - $potongan_total);
                        $cash = FinanceAccount::sumKredit($data['acc_cash'], $cashValue);

                        // save account jurnal
                        $jornals = FinanceJournal::create([
                            'transaction_id' => $transaction->id,
                            'type' => config('finance_journal.types.kredit'), 
                            'account_id' => $data['acc_cash'],
                            'value' => $cashValue,
                            'tax'  => '', 
                            'tags' => 'is_cash',                     
                            'balance'  => $cash->balance,                      
                            'description' => '',                        
                        ]);

                        $biayaTrx->total = $totalCash - $potongan_total;
                        $biayaTrx->save();
                    }
                }

                $log = new LogActivity();
                $log->module_id = config('my-modules.accounting');
                $log->action = "Create Biaya Transaction";
                $log->desc = "Kode Transaksi : $transaction->transaction_code, Tipe Transaksi : ".config('finance_trx.label_trx_types.'.$transaction->trx_type_id);

                $log->save();
                return $transaction;
            }
            throw new GeneralException(trans('accounting::exceptions.biaya.create_error'));
        });
    }

    public function storePay(FinanceBiayaTrx $financeBiayaTrx, array $data)
    {
        return DB::transaction(function () use ($financeBiayaTrx, $data) {
            $date_arr = explode('/',$data['date_trx']);
            $trx_date ='';
            if(sizeof($date_arr) > 2){
                $trx_date = $date_arr[2].'-'.$date_arr[1].'-'.$date_arr[0];
            }

            //store data finance transaction
            $transaction = FinanceTransaction::create([
                'trx_type_id' => config('finance_trx.trx_types.biaya_payment'), 
                'memo' => $data['notes'],
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id
            ]);

            if($transaction){
                // create transaction code
                $transaction_code = FinanceTransaction::generateTrxCode($transaction->trx_type_id, $transaction->trxType->label);

                $transaction = FinanceTransaction::find($transaction->id);
                $transaction->transaction_code = $transaction_code;

                $transaction->save();

                $hutang = FinanceJournal::where('transaction_id', $financeBiayaTrx->transaction_id)
                        ->where('tags', 'is_hutang')
                        ->first();
                    
                if($hutang){
                    // update balance account hutang and then stor to journal
                    $value = str_replace('.','',$data['total_pay']);
                    $hutang_acc = FinanceAccount::sumDebit($hutang->account_id, $value);

                    $jurnalHutang = FinanceJournal::create([
                        'transaction_id' => $transaction->id,
                        'type' => config('finance_journal.types.debit'),
                        'account_id' => $hutang_acc->id,
                        'tags' => 'is_biaya_payment',
                        'value' => $data['total_pay'],
                        'balance' => $hutang_acc->balance,
                    ]);

                    // update balanse account cash and then store to jornal
                    $cash_acc = FinanceAccount::sumKredit($data['acc_cash'], $value);
                     
                    $jurnalCash = FinanceJournal::create([
                        'transaction_id' => $transaction->id,
                        'type' => config('finance_journal.types.kredit'),
                        'account_id' => $cash_acc->id,
                        'tags' => 'is_cash',
                        'value' => $value,
                        'balance' => $cash_acc->balance,
                    ]);

                    // update data biaya trx
                    $financeBiayaTrx->remain_payment = intval($financeBiayaTrx->remain_payment - $value);
                    if($financeBiayaTrx->remain_payment < 1){
                        $financeBiayaTrx->status = 1;
                    }
                    $financeBiayaTrx->save();

                    $transaction->person = $financeBiayaTrx->financeTrx->person;
                    $transaction->save();

                    // store to finance biaya to cash
                    $biayaTocash = FinanceBiayaToCash::create([
                        'transaction_id' => $transaction->id,
                        'biaya_trx_id' => $financeBiayaTrx->id,
                        'created_by' => auth()->user()->id,
                        'updated_by' => auth()->user()->id,
                    ]);

                    $log = new LogActivity();
                    $log->module_id = config('my-modules.accounting');
                    $log->action = "Create Biaya Transaction";
                    $log->desc = "Kode Transaksi : $transaction->transaction_code, Tipe Transaksi : ".config('finance_trx.label_trx_types.'.$transaction->trx_type_id);

                    $log->save();
                    return $transaction;
                }
                throw new GeneralException("ERROR, DATA TERSEBUT SUDAH LUNAS !");
            }

            throw new GeneralException(trans('accounting::exceptions.biaya.storePay_error'));

        });
    }
}
