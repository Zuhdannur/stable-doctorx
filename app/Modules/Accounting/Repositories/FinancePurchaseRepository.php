<?php
namespace App\Modules\Accounting\Repositories;

use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;

use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\Supplier;
use App\Modules\Accounting\Models\FinanceTax;
use App\Modules\Attribute\Models\LogActivity;
use App\Modules\Accounting\Models\FinanceAccount;
use App\Modules\Accounting\Models\FinanceJournal;
use App\Modules\Accounting\Models\FinancePurchase;
use App\Modules\Accounting\Models\FinanceTransaction;

use App\Modules\Accounting\Models\FinancePurchaseDetail;
use App\Modules\Accounting\Models\FinancePurchaseToCash;

class FinancePurchaseRepository extends BaseRepository 
{
    public function model()
    {
        return FinancePurchase::class;
    }

    public function store(array $data)
    {
        // dd($data);
        return DB::transaction(function () use($data) {
            $date_arr = explode('/',$data['date_trx']);
            $trx_date ='';
            if(sizeof($date_arr) > 2){
                $trx_date = $date_arr[2].'-'.$date_arr[1].'-'.$date_arr[0];
            }

            $potongan_percentage = 1;
            $savePotongan = false;

            // calc potongan
            if(isset($data['potongan'])){
                $data['potongan_value'] = $data['potongan_value'] == '' ? 0 : $data['potongan_value'];
                $data['potongan_value'] = str_replace('.', '', $data['potongan_value']);
                
                $savePotongan = true;
                $potongan_percentage = $data['potongan_value'] / 100;

            }

            $person = Supplier::findOrfail($data['supplier']);
            
            //save data to transaction table
            $transaction = FinanceTransaction::create([
                'trx_type_id' => config('finance_trx.trx_types.purchase'), 
                'memo' => $data['notes'],
                'person' => $person->supplier_name.' - '.$person->company_name,
                'person_id' => $person->id,
                'person_type' => config('finance_trx.person_type.supplier'),
                'trx_date' =>$trx_date,
                'potongan' => ( isset($data['potongan_value']) || $data['potongan_value'] != '' ? $data['potongan_value'] : 0 ),
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id
            ]);

            if($transaction){
                $transaction_code = FinanceTransaction::generateTrxCode($transaction->trx_type_id, $transaction->trxType->label);

                $transaction = FinanceTransaction::find($transaction->id);
                $transaction->transaction_code = $transaction_code;
                
                
                $transaction->save();
                
                $due_date ='';
                $status = '0'; //status 1 = lunas ,0 == belum lunas
                $date_arr = explode('/',$data['due_date']);
                $potongan_total = intval(currency()->digit($data['sub_total']) * $data['potongan_value'] / 100);

                if(sizeof($date_arr) > 2){
                    $due_date = $date_arr[2].'-'.$date_arr[1].'-'.$date_arr[0];
                }

                //store to finance purchase
                $purchase_trx = FinancePurchase::create([
                    'transaction_id' => $transaction->id,
                    'status' =>  $status,
                    'due_date' => $due_date,
                    'remain_payment' => currency()->digit($data['total_amount']),
                    'total' => currency()->digit($data['total_amount']),
                ]);

                if($purchase_trx){
                    $totalloop = $data['product'];
                    $tax_total = 0;

                    // create journal potongan and update account potongan balanced
                    $biaya = 0;
                    if(isset($data['biaya'])){
                        $biaya = FinanceAccount::sumDebit($data['biaya'], (currency()->digit($data['biaya_value']) == '' ? 0 : currency()->digit($data['biaya_value']) ));

                        $biayaJournal = FinanceJournal::create([
                            'transaction_id' => $transaction->id,
                            'type' => config('finance_journal.types.kredit'), 
                            'account_id' => $biaya->id,
                            'value' => (currency()->digit($data['biaya_value']) == '' ? 0 : currency()->digit($data['biaya_value']) ),
                            'tax'  => '',  
                            'tags' => 'is_biaya',                    
                            'balance'  => $biaya->balance,                      
                            'description' => '',                        
                        ]);
                        // dd($biayaJournal);
                        // set biaya for sum total
                        $biaya = (currency()->digit($data['biaya_value']) == '' ? 0 : currency()->digit($data['biaya_value']) );
                    }

                    $persediaan_value = 0;
                    foreach($totalloop as $key => $val){
                        $type = '';
                        $items = '';
                        $product = explode('#',$val);
                        if(sizeof($product) > 2){
                            if($product['1'] == 'product'){
                                $type = 'product';
                                $items = $product[0].'#'.$product[2];
                            }
                        }else{
                            throw new GeneralException(trans('accounting::exceptions.purchase.create_error'));
                        };

                        $tax = '';
                        $total_tax = 0;
                        $tax_id = '';
                        if(isset($data['tax_form'][$key])){
                            $tax = FinanceTax::findOrfail($data['tax_form'][$key]);
                            $tax_value = $tax->percentage;

                            if($tax){
                                // tax total
                                $total_tax = intval(currency()->digit($data['price'][$key]) * currency()->digit($data['qty'][$key]) * $tax->percentage / 100);
                                
                                $tax_sales = FinanceAccount::sumDebit($tax->account_tax_purchase, $total_tax);

                                //journal tax
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

                                $tax_id = $tax->id;
                            }
                        }

                        // store to purchase detail
                        $purchase_detail = FinancePurchaseDetail::create([
                            'purchase_id' => $purchase_trx->id,
                            'items' => $items,
                            'type' => $type,
                            'qty' => $data['qty'][$key],
                            'price' => currency()->digit($data['price'][$key]),
                            'price_total' => currency()->digit($data['total'][$key]),
                            'desc' => (isset($data['desc'][$key]) ? $data['desc'][$key] : ''),
                            'tax_label' => isset($tax->tax_name) ? $tax->tax_name.'-'.$tax->percentage.'%' : '',
                            'tax_value' => isset($tax_value) ? $tax_value : '',
                        ]);

                        // update product quantity
                        $product = Product::findOrfail($product[0]);

                        // sum harga beli rata_rata
                        $_persediaan_awal = $product->quantity * $product->purchase_price;
                        $_pembelian_bersih =  ( intval(currency()->digit($data['price'][$key]) * $data['qty'][$key]) + $tax_total + $biaya ) - $potongan_total;
                        $_barang_tersedia = $_persediaan_awal + $_pembelian_bersih;

                        $_harga_beli_rata_rata = $_barang_tersedia / ($product->quantity + $purchase_detail->qty);

                        $product->quantity = $product->quantity + $purchase_detail->qty;
                        $product->purchase_price = currency()->digit($data['price'][$key]);
                        $product->purchase_price_avg = $_harga_beli_rata_rata;

                        // sum product price (dimatikan dulu karna input harga secara manual)
                        // $up_purchase = 0;
                        // if($product->sales_type == 1){ //0 by purchase_price_avg, 1 by  purchase_price
                        //     $up_purchase = $product->purchase_price_avg * $product->percentage_price_sales / 100;
                        // }else{
                        //     $up_purchase = $product->purchase_price * $product->percentage_price_sales / 100;
                        // }
                        // $product->price = $product->price + $up_purchase;

                        // update min stock status
                        if($product->quantity < $product->min_stock){
                            $product->is_min_stock = 1;
                        } 

                        // save product tax
                        if(!empty($tax_id)){
                            $product->tax_id = $tax_id;
                        }

                        $product->save();
                        

                        $persediaan_value = $persediaan_value + intval(currency()->digit($data['price'][$key]) * $data['qty'][$key]) + $tax_total;
                    }

                    // create journal potongan and update account potongan balanced
                    if($savePotongan){
                        $potongan = FinanceAccount::sumKredit($data['potongan'],( $potongan_total != '' ?  $potongan_total : 0 ));

                        $potonganJournal = FinanceJournal::create([
                            'transaction_id' => $transaction->id,
                            'type' => config('finance_journal.types.kredit'), 
                            'account_id' => $data['potongan'],
                            'value' => ( $potongan_total != '' ?  $potongan_total : 0 ),
                            'tax'  => '',  
                            'tags' => 'is_potongan',                    
                            'balance'  => $potongan->balance,                      
                            'description' => '',                        
                        ]);
                    }

                    // update persediaan balance and store to journal
                    $persediaan = FinanceAccount::sumDebit(config('finance_account.default.acc_persediaan_obat'), $persediaan_value);

                    $jornalsPersediaan =  FinanceJournal::create([
                        'transaction_id' => $transaction->id,
                        'type' => config('finance_journal.types.debit'), 
                        'account_id' => config('finance_account.default.acc_persediaan_obat'),
                        'value' => $persediaan_value,
                        'tax'  => '',    
                        'tags' => 'is_persediaan',              
                        'balance'  => $persediaan->balance,                      
                        'description' => '',                        
                    ]);

                    // update hutang balance
                    $valueHutang = currency()->digit($data['total_amount']);
                    $hutang = FinanceAccount::sumKredit(config('finance_account.default.acc_hutang'), $valueHutang);
                   
                    // save hutang jurnal
                    $jornals = FinanceJournal::create([
                        'transaction_id' => $transaction->id,
                        'type' => config('finance_journal.types.kredit'), 
                        'account_id' => $hutang->id,
                        'value' => currency()->digit($data['total_amount']),
                        'tax'  => '',    
                        'tags' => 'is_hutang',              
                        'balance'  => $hutang->balance,                      
                        'description' => '',                        
                    ]);

                    $log = new LogActivity();
                    $log->module_id = config('my-modules.accounting');
                    $log->action = "Create Biaya Payment";
                    $log->desc = "Kode Transaksi : $transaction->transaction_code, Tipe Transaksi : ".config('finance_trx.label_trx_types.'.$transaction->trx_type_id);

                    $log->save();
                    return $transaction;
                }

                throw new GeneralException(trans('accounting::exceptions.purchase.create_error'));
            }
            throw new GeneralException(trans('accounting::exceptions.purchase.create_error'));
        });
    }

    public function storePay(FinancePurchase $purchase, array $data)
    {
        return DB::transaction(function () use($purchase, $data) {
            $date_arr = explode('/',$data['date_trx']);
            $trx_date ='';
            if(sizeof($date_arr) > 2){
                $trx_date = $date_arr[2].'-'.$date_arr[1].'-'.$date_arr[0];
            }

            //store data finance transaction
            $transaction = FinanceTransaction::create([
                'trx_type_id' => config('finance_trx.trx_types.purchase_payment'), 
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

                $hutang = FinanceJournal::where('transaction_id', $purchase->transaction_id)
                        ->where('tags', 'is_hutang')
                        ->first();

                if($hutang){
                    // update balance account hutang and then store to journal
                    $value = str_replace('.','',$data['total_pay']);
                    $hutang_acc = FinanceAccount::sumDebit($hutang->account_id, $value);

                    $jurnalHutang = FinanceJournal::create([
                        'transaction_id' => $transaction->id,
                        'type' => config('finance_journal.types.debit'),
                        'account_id' => $hutang_acc->id,
                        'tags' => 'is_purchase_payment',
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
                    $purchase->remain_payment = intval($purchase->remain_payment - $value);
                    if($purchase->remain_payment < 1){
                        $purchase->status = 1;
                    }
                    $purchase->save();
  
                    $transaction->person = $purchase->financeTrx->person;
                    $transaction->save();
                    // store to finance biaya to cash
                    $purchaseTocash = FinancePurchaseToCash::create([
                        'transaction_id' => $transaction->id,
                        'purchase_id' => $purchase->id,
                    ]);

                    $log = new LogActivity();
                    $log->module_id = config('my-modules.accounting');
                    $log->action = "Create Purchase Payment";
                    $log->desc = "Kode Transaksi : $transaction->transaction_code, Tipe Transaksi : ".config('finance_trx.label_trx_types.'.$transaction->trx_type_id);

                    $log->save();
                    return $transaction;
                }
                throw new GeneralException('ERROR, DATA TERSEBUT SUDAH LUNAS !');
            }
            throw new GeneralException(trans('accounting::exceptions.purchase.storePay_error'));
        });
    }
}
