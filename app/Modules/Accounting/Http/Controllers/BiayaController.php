<?php

namespace App\Modules\Accounting\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Modules\Accounting\Models\FinanceAccount;
use App\Modules\Accounting\Models\FinanceTax;
use App\Modules\Accounting\Models\FinanceBiayaTrx;
use App\Modules\Accounting\Models\FinanceTransaction;

use App\Modules\Accounting\Repositories\FinanceBiayaRepository;
use Illuminate\Support\Facades\Storage;
use DataTables;
use PDF;

class BiayaController extends Controller
{
    protected $financeBiayaRepository;

    public function __construct(FinanceBiayaRepository $financeBiayaRepository) {
        $this->financeBiayaRepository = $financeBiayaRepository;
    }

    public function index(Request $request)
    {
        // date for view
        $date_1 = \Carbon\Carbon::now()->subdays(30)->format('d/m/Y');     
        $date_2 = \Carbon\Carbon::now()->format('d/m/Y');

        if($request->isMethod('POST')){
            $date_1 = $request->date_1;
            $date_2 = $request->date_2;
        }

        if($request->ajax()){
            $start_date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->date_1)->format('Y-m-d'); 
            $end_date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->date_2)->format('Y-m-d');   

            $model = FinanceBiayaTrx::with('financeTrx')
                    ->whereHas('financeTrx', function($q) use($start_date, $end_date){
                        return $q->whereBetween('trx_date',[$start_date, $end_date] );
                    })
                    ->orderBy('status', 'ASC')
                    ->orderBy('due_date', 'ASC')
                    ->orderBy('transaction_id','DESC');

            return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('action', function($data){
                $btn = '';
                if($data->status < 1){
                    $btn .= $data->pay_button;
                }
                $btn .= $data->show_button;

                return $btn;
            })
            ->editColumn('trx_date', function($data){
                $date = strtotime($data->financeTrx->trx_date);
                if($date > 0){
                    return date('d/m/Y', $date);
                }else{
                    return '-';
                }
            })
            ->editColumn('due_date', function($data){
                $date = strtotime($data->due_date);
                if($date > 0){
                    return date('d/m/Y', $date);
                }else{
                    return '-';
                }
            })
            ->editColumn('status', function($data){
                $label = '';
                if($data->status < 1){
                    $due_date = date_create(date('Y-m-d' ,strtotime($data->due_date)));
                    $now = date_create(date('Y-m-d'));
                    $diff = date_diff($now, $due_date);
                    $diff = $diff->format('%R%a');

                    if($diff > 0){
                        $label = '<span class="badge badge-warning">Belum Lunas</span>';
                    }else{
                        $label = '<span class="badge badge-danger">Jatuh Tempo</span>';
                    }

                }else{
                    $label = '<span class="badge badge-success">Lunas</span>';
                }
                return $label;
            })
            ->editColumn('remain_payment', function($data){
                return currency()->rupiah($data->remain_payment, setting()->get('currency_symbol'));
            })
            ->editColumn('total', function($data){
                return currency()->rupiah($data->total, setting()->get('currency_symbol'));
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
        }
        return view('accounting::biaya.index')
        ->withDate(array('date_1' => $date_1, 'date_2' => $date_2));
    }

    public function create()
    {
        $cash = FinanceAccount::where('account_category_id', config('finance_account.default.cash'))
                ->get();
        $biaya = FinanceAccount::with('accountCategory')
                ->whereHas("accountCategory", function($q){
                    $q->where('type',config('finance_account.type.beban'));
                })
                ->get();
        $allAccount = FinanceAccount::with('accountCategory')
                ->where('account_category_id', '!=' ,config('finance_account.default.cash'))
                ->whereHas("accountCategory", function($q){
                    $q->where('type' ,"!=", config('finance_account.type.ekuitas'));
                })
                ->get();
        $taxList = FinanceTax::optionList();
        
        $cashList = '<option></option>';
        foreach($cash as $val){
            $name = $val->account_code.' - '.$val->account_name;
            $cashList .= '<option value="'.$val->id.'">'.$name.'</option>';
        }

        $biayaList = '<option></option>';
        foreach($biaya as $val){
            $name = $val->account_code.'-'.$val->account_name.' ('.$val->accountCategory->category_name.')';
            $biayaList .= '<option value="' .$val->id. '">'.$name.'</option>';
        }

        $accountList = '<option></option>';
        foreach($allAccount as $val){
            $cashName = $val->account_code.' - '.$val->account_name.'('.$val->accountCategory->category_name.')';
            $accountList .= '<option value="'.$val->id.'">'.strtoupper($cashName).'</option>';
        };

        return view('accounting::biaya.create')
        ->withcash($cashList)
        ->withAllAccountList($accountList)
        ->withBiaya($biayaList)
        ->withTaxList($taxList);
    }

    public function store(Request $request)
    {
        $save = $this->financeBiayaRepository->store($request->input());
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
            $message = __('accounting::alerts.biaya.created');
        }else{
            $status = false;
            $message = trans('accounting::exceptions.biaya.create_error');
        }  

        return response()->json(array('status' => $status, 'message' => $message, 'data' => $save));

    }

    public function showDetail(FinanceBiayaTrx $biaya)
    {
        return view('accounting::biaya.show')
        ->withBiaya($biaya);
    }

    public function bayar(FinanceBiayaTrx $biaya)
    {
        $cashData = FinanceAccount::where('account_category_id',config('finance_account.default.cash'))->get();
        $cashList = '<option></option>';
        foreach($cashData as $val){
            $accountName = $val->account_code.' - '.$val->account_name;
            $cashList .= '<option value="'.$val->id.'">'.strtoupper($accountName).'</option>';
        }

        return view('accounting::biaya.pay')
        ->withCashList($cashList)
        ->withBiaya($biaya);
    }

    public function printPdf(FinanceBiayaTrx $biaya)
    {
        $data = ['biaya' => $biaya];
        $pdf = PDF::loadView('accounting::biaya.print.biaya-pdf', $data);
  
        return $pdf->stream();
    }

    
    public function printHtml(FinanceBiayaTrx $biaya){
        return view('accounting::biaya.print.biaya')
                ->withBiaya($biaya);
    }

    public function storePay(Request $request)
    {
        $biaya = FinanceBiayaTrx::findOrfail($request->biaya_id);
        $save = $this->financeBiayaRepository->storePay($biaya, $request->input());
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
            $message = __('accounting::alerts.biaya.storePay');
        }else{
            $status = false;
            $message = trans('accounting::exceptions.biaya.storePay_error');
        }  

        return response()->json(array('status' => $status, 'message' => $message, 'data' => $save));

    }

    public function download(FinanceBiayaTrx $biaya)
    {
        return Storage::download('finance_attachment/'.$biaya->financeTrx->attachment_file);
    }

    public function paymentDetail(FinanceTransaction $trx)
    {
        return view('accounting::biaya.payment-detail')
        ->withTrx($trx);
    }
}
