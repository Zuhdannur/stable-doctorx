<?php

namespace App\Modules\Accounting\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;

use App\Modules\Accounting\Models\FinanceAccount;
use App\Modules\Accounting\Models\FinanceTax;
use App\Modules\Accounting\Models\FinanceJournal;
use App\Modules\Accounting\Models\FinanceTransaction;
use App\Modules\Accounting\Repositories\FinanceAccountRepository;
use App\Modules\Accounting\Repositories\FinanceCashTransactionRepository;

use DataTables;
use PDF;

class FinanceKasControlller extends Controller
{
    protected $financeAccountRepository;
    protected $financeCashTransactionRepository;

    public function __construct(FinanceAccountRepository $financeAccountRepository)
    {
        $this->financeAccountRepository = $financeAccountRepository;
    }

    public function index(Request $request)
    {
        if($request->ajax()){
            $model = FinanceAccount::where('account_category_id',config('finance_account.default.cash'))
                    ->orderBy('account_name','ASC');

            return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('action', function($data){
                $btn = $data->receipt_money_button;
                $btn .= $data->transfer_money_button;
                $btn .= $data->send_money_button;
                $btn .= $data->journal_cash_button;

                return $btn;
            })
            ->editColumn('balance', function($data){
                return currency()->rupiah($data->balance, setting()->get('currency_symbol'));
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('accounting::kas.index');
    }

    public function createCashAccount()
    {
        return view('accounting::kas.create');
    }

    public function saveCashAccount(Request $request)
    {
        if (auth()->user()->cannot('create cash account')) {
            return response()->json(['messages' => trans('exceptions.cannot_create')], 401);
        }

        $save = $this->financeAccountRepository->createCashAccount($request->input());

        if($save){
            $status = true;
            $message = __('accounting::alerts.account.created');
        }else{
            $status = false;
            $message = trans('accounting::exceptions.account.create_error');
        }

        return response()->json(array('status' => $status, 'message' => $message));
    }

    public function transfer($id)
    {
        $account = FinanceAccount::findOrFail($id);
        $cashAccount = FinanceAccount::where('account_category_id', config('finance_account.default.cash'))
                    ->where('id','!=',$id)->get();

        $cashAccountList = '<option></option>';
        foreach($cashAccount as $val){
            $cashName = $val->account_code.' - '.$val->account_name;
            $cashAccountList .= '<option value="'.$val->id.'">'.strtoupper($cashName).'</option>';
        };

        return view('accounting::kas.transfer')
        ->withAccount($account)
        ->withCashAccountList($cashAccountList);
    }

    public function receipt($id)
    {

        $account = FinanceAccount::findOrFail($id);
        $allAccount = FinanceAccount::where('id','!=',$id)->get();
        $tax = FinanceTax::get();

        $cashAccountList = '<option></option>';
        foreach($allAccount as $val){
            $cashName = $val->account_code.' - '.$val->account_name;
            $cashAccountList .= '<option value="'.$val->id.'">'.strtoupper($cashName).'</option>';
        }

        $taxList ='<option></option>';
        foreach($tax as $val){
            $taxList .= '<option value="'.$val->id.'" data-tax="'.$val->percentage.'">'.strtoupper($val->tax_name).'</option>';
        }

        return view('accounting::kas.receipt')
        ->withAccount($account)
        ->withCashAccountList($cashAccountList)
        ->withTaxList($taxList);
    }

    public function send($id)
    {
        $account = FinanceAccount::findOrFail($id);
        $accountData = FinanceAccount::where('account_category_id','!=', config('finance_account.default.cash'))->get();
        $allAccountData = FinanceAccount::where('id','!=',$id)->get();
        $tax = FinanceTax::get();

        $accountList = '<option></option>';
        foreach($accountData as $val){
            $accountName = $val->account_code.' - '.$val->account_name;
            $accountList .= '<option value="'.$val->id.'">'.strtoupper($accountName).'</option>';
        }

        $allAccountList = '<option></option>';
        foreach($accountData as $val){
            $accountName = $val->account_code.' - '.$val->account_name;
            $allAccountList .= '<option value="'.$val->id.'">'.strtoupper($accountName).'</option>';
        }

        $taxList ='<option></option>';
        foreach($tax as $val){
            $taxList .= '<option value="'.$val->id.'" data-tax="'.$val->percentage.'">'.strtoupper($val->tax_name).'</option>';
        }

        return view('accounting::kas.send')
        ->withAccount($account)
        ->withAccountList($accountList)
        ->withAllAccountList($allAccountList)
        ->withTaxList($taxList);
    }

    public function saveReceipt(Request $request)
    {
        $this->financeCashTransactionRepository = new FinanceCashTransactionRepository;
        $save = $this->financeCashTransactionRepository->storeReceipt($request->input());
        $file = $request->file('file-input');

        if($save){
            if($file){
                //store attachment
                $newFileName = $save->transaction_code.'.'.$file->extension();
                $newFileName = strtolower(str_replace(' ', '-', $newFileName));
                $file->storeAs('finance_attachment',$newFileName);

                //update the attachment column on db
                $save->attachment_file = $newFileName;
                $save->save();
            }

            $status = true;
            $message = __('accounting::alerts.receipt.created');
        }else{
            $status = false;
            $message = trans('accounting::exceptions.receipt.create_error');
        }  

        return response()->json(array('status' => $status, 'message' => $message, 'data' => $save));

    }

    public function saveTransfer(Request $request)
    {
        $this->financeCashTransactionRepository = new FinanceCashTransactionRepository;
        $save = $this->financeCashTransactionRepository->storeTransfer($request->input());
        $file = $request->file('file-input');

        if($save){
            if($file){
                
                //store attachment
                $newFileName = $save->transaction_code.'.'.$file->extension();
                $newFileName = strtolower(str_replace(' ', '-', $newFileName));
                $file->storeAs('finance_attachment',$newFileName);

                //update the attachment column on db
                $save->attachment_file = $newFileName;
                $save->save();
            }

            $status = true;
            $message = __('accounting::alerts.transfer.created');
        }else{
            $status = false;
            $message = trans('accounting::exceptions.transfer.create_error');
        }  

        return response()->json(array('status' => $status, 'message' => $message, 'data' => $save));

    }

    public function saveSend(Request $request)
    {
        $this->financeCashTransactionRepository = new FinanceCashTransactionRepository;
        $save = $this->financeCashTransactionRepository->storeSend($request->input());
        $file = $request->file('file-input');
        
        if($save){
            if($file){
                
                //store attachment
                $newFileName = $save->transaction_code.'.'.$file->extension();
                $newFileName = strtolower(str_replace(' ', '-', $newFileName));
                $file->storeAs('finance_attachment',$newFileName);

                //update the attachment column on db
                $save->attachment_file = $newFileName;
                $save->save();
            }

            $status = true;
            $message = __('accounting::alerts.send.created');
        }else{
            $status = false;
            $message = trans('accounting::exceptions.send.create_error');
        }  

        return response()->json(array('status' => $status, 'message' => $message, 'data' => $save));
    }

    public function showReceipt(FinanceTransaction $trx)
    {
        return view('accounting::kas.journal.receipt')
        ->withTrx($trx);
    }

    public function showTransfer(FinanceTransaction $trx)
    {
        return view('accounting::kas.journal.transfer')
        ->withTrx($trx);
    }

    public function showSend(FinanceTransaction $trx)
    {
        return view('accounting::kas.journal.send')
        ->withTrx($trx);
    }

    public function journal(FinanceAccount $account, Request $request)
    {
        $account = $account->id;
        if($request->ajax()){
            $model = FinanceJournal::where('account_id',$account)->
                    with('transaction')->orderBy('created_at','DESC');

            return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('action', function($data){
                $btn = $data->journal_detail_button;
                return $btn;
            })
            ->editColumn('transaction.trx_date', function($data){
                $date = strtotime($data->transaction->trx_date);
                if($date > 0){
                    return date('d/m/Y', $date);
                }else{
                    return '-';
                }
            })
            ->addColumn('debit', function($data){
                if($data->type == config('finance_journal.types.debit')){
                    return currency()->rupiah($data->value, setting()->get('currency_symbol'));
                }else{
                    return '-';
                }
            })
            ->addColumn('credit', function($data){
                if($data->type == config('finance_journal.types.kredit')){
                    return currency()->rupiah($data->value, setting()->get('currency_symbol'));
                }else{
                    return '-';
                }
            })
            ->editColumn('balance', function($data){
                return currency()->rupiah($data->balance, setting()->get('currency_symbol'));
            })
            ->rawColumns(['action'])
            ->make(true);

        }

        return view('accounting::kas.journal.index', compact('account'));
    }

    public function pdfReceipt(FinanceTransaction $trx)
    {
        $data = ['trx' => $trx];
        $pdf = PDF::loadView('accounting::kas.print.receipt-pdf', $data);
  
        return $pdf->stream();
    }

    public function printReceipt(FinanceTransaction $trx)
    {
        return view('accounting::kas.print.receipt')
        ->withTrx($trx);
    }

    public function pdfSend(FinanceTransaction $trx)
    {
        $data = ['trx' => $trx];
        $pdf = PDF::loadView('accounting::kas.print.send-pdf', $data);
  
        return $pdf->stream();
    }

    public function printSend(FinanceTransaction $trx)
    {
        return view('accounting::kas.print.send')
        ->withTrx($trx);
    }
}
