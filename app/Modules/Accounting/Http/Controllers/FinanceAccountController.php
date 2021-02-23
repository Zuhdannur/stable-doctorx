<?php

namespace App\Modules\Accounting\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;

// model
use App\Modules\Accounting\Models\FinanceAccount;
use App\Modules\Accounting\Models\FinanceAccountCategory;
use App\Modules\Accounting\Models\FinanceJournal;
use App\Modules\Accounting\Models\FinanceTransaction;

use App\Modules\Accounting\Repositories\FinanceAccountRepository;

use DataTables;

class FinanceAccountController extends Controller
{
    protected $financeAccountRepository;

    public function __construct(FinanceAccountRepository $financeAccountRepository)
    {
        $this->financeAccountRepository = $financeAccountRepository;
    }

    public function index(Request $request)
    {
        if($request->ajax()){
            $model = FinanceAccount::with('accountCategory')->orderBy('account_code','asc');

            return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('action', function($data){
                $btn = $data->edit_button;
                $btn .= $data->journal_button;
                return $btn;
            })
            ->editColumn('id', '{{$id}}')
            ->editColumn('balance', function($data){
                return currency()->rupiah($data->balance, setting()->get('currency_symbol'));
            })
            ->rawColumns(['action'])
            ->make(true);

        }
        return view('accounting::account.index');

    }

    public function getFormCreate()
    {
        $category = FinanceAccountCategory::orderBy('category_code','ASC')
                    ->where('parent_id', '>', 0)
                    ->with('parent')
                    ->get();

        $categoryList = '<option></option>';
        foreach($category as $val){
            $name = $val->category_code.' - '.$val->category_name.'('.$val->parent['category_name'].')';
            $categoryList .= '<option value="'.$val->id.'">'.$name.'</option>';
        }

        return view('accounting::account.create')
                ->withCategory($categoryList);
    }

    public function getFormEdit(FinanceAccount $financeAccount)
    {
        return view('accounting::account.edit')
                ->withAccount($financeAccount);
    }

    public function store(Request $request)
    {
        $status = false;
        $message = 'Akses tidak sah !!!';

        if($request->isMethod('post')){
            if (auth()->user()->cannot('create finance account')) {
                return response()->json(['messages' => trans('exceptions.cannot_update')], 401);
            }

            $save = $this->financeAccountRepository->create($request->input());
            if($save){
                $status = true;
                $message = __('accounting::alerts.account.created');
            }else{
                $status = false;
                $message = trans('accounting::exceptions.account.create_error');
            }
        }else if($request->isMethod('patch')){
            if (auth()->user()->cannot('update finance account')) {
                return response()->json(['messages' => trans('exceptions.cannot_update')], 401);
            }

            $update = $this->financeAccountRepository->update($request->input());
            if($update){
                $status = true;
                $message = __('accounting::alerts.account.updated');
            }else{
                $status = false;
                $message = trans('accounting::exceptions.account.update_error');
            }
        }

        return response()->json(array('status' => $status, 'message' => $message));

    }

    public function createJournal()
    {
        $account = FinanceAccount::orderBy('account_code','ASC')->get();
        $accountList = '<option></option>';

        foreach ($account as $val) {
            $name = $val->account_code.' - '.$val->account_name.' ('.$val->accountCategory['category_name'].')';
            $accountList .= '<option value="'.$val->id.'">'.$name.'</option>';
        }

        $type = '';
        foreach(config('finance_journal.types_list') as $key => $val){
            $type .= '<option value="'.$key.'">'.$val.'</option>';
        }
        return view('accounting::account.journal.create')
        ->withAccount($accountList)
        ->withType($type);
    }

    public function storeJournal(Request $request)
    {
        $save = $this->financeAccountRepository->storeJournal($request->input());
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
            $message = __('accounting::alerts.journal.created');
        }else{
            $status = false;
            $message = trans('accounting::exceptions.journal.create_error');
        }  

        return response()->json(array('status' => $status, 'message' => $message, 'data' => $save));
    }

    public function showJournal($id, Request $request)
    {

        if($request->ajax()){
            $model = FinanceJournal::where('account_id',$id)->
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

        return view('accounting::account.journal.show', compact('id'));
    }
    
    public function showJournalDetail(FinanceTransaction $trx)
    {
        if($trx->biaya){
            return redirect()->route('admin.accounting.biaya.show', $trx->biaya);
        }else if($trx->purchase){
            return redirect()->route('admin.accounting.purchase.show', $trx->purchase);
        }else{
            $trx_type = $trx->trx_type_id;
            if(!empty($trx_type)){
                switch ($trx_type) {
                    case config('finance_trx.trx_types.receive') :
                        return redirect()->route('admin.accounting.cash.journal.receipt.show', $trx);
                        break;
                    case config('finance_trx.trx_types.transfer') :
                        return redirect()->route('admin.accounting.cash.journal.transfer.show', $trx);
                        break;
                    case config('finance_trx.trx_types.send') :
                        return redirect()->route('admin.accounting.cash.journal.send.show', $trx);
                        break;
                    case config('finance_trx.trx_types.general') :
                        return redirect()->route('admin.accounting.account.journal.general', $trx);
                        break;
                    case config('finance_trx.trx_types.purchase_payment') :
                        return redirect()->route('admin.accounting.purchase.paymentDetail', $trx);
                        break;
                    case config('finance_trx.trx_types.biaya_payment') :
                        return redirect()->route('admin.accounting.biaya.paymentDetail', $trx);
                        break;
                    case config('finance_trx.trx_types.opname') :
                        return redirect()->route('admin.accounting.account.journal.general', $trx);
                        break;
                    case config('finance_trx.trx_types.invoice') :
                        return redirect()->route('admin.accounting.account.journal.general', $trx);
                        break;
                    case config('finance_trx.trx_types.invoice_payment') :
                        return redirect()->route('admin.accounting.account.journal.general', $trx);
                        break;
                    default:
                        abort(404);
                        break;
                }
            }else{
                abort(404);
            }
        }
    }

    public function generalJournal(FinanceTransaction $trx)
    {
        return view('accounting::account.journal.general')
            ->withTrx($trx);
    }

    public function download(FinanceTransaction $trx)
    {
        return Storage::download('finance_attachment/'.$trx->attachment_file);
    }
}
