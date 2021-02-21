<?php

namespace App\Modules\Accounting\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Product\Models\Supplier;
use App\Modules\Accounting\Models\FinanceTax;
use App\Modules\Accounting\Models\FinanceAccount;
use App\Modules\Accounting\Models\FinancePurchase;
use App\Modules\Product\Models\Product;
use App\Modules\Accounting\Models\FinanceTransaction;

use App\Modules\Accounting\Repositories\FinancePurchaseRepository;
use App\Http\Controllers\Controller;

use DataTables;
use PDF;

class FinancePurchaseController extends Controller
{

    protected $financePurchaseRepository;

    public function __construct(FinancePurchaseRepository $financePurchaseRepository) {
        $this->financePurchaseRepository = $financePurchaseRepository;
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

            $model = FinancePurchase::with('financeTrx')
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
                    // dd($diff);
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
        return view('accounting::purchase.index')
        ->withDate(array('date_1' => $date_1, 'date_2' => $date_2));
    }

    public function create()
    {
        $supplier = Supplier::optionlist();
        $tax = FinanceTax::optionList();
        $product = new Product;
        $allAccount = FinanceAccount::with('accountCategory')
                ->where('account_category_id', '!=' ,config('finance_account.default.cash'))
                ->whereHas("accountCategory", function($q){
                    $q->where('type' ,"!=", config('finance_account.type.ekuitas'))
                    ->where('type' ,"!=", config('finance_account.type.beban'));
                })
                ->get();

        $accountList = '<option></option>';
        foreach($allAccount as $val){
            $cashName = $val->account_code.' - '.$val->account_name.'('.$val->accountCategory->category_name.')';
            $accountList .= '<option value="'.$val->id.'">'.strtoupper($cashName).'</option>';
        };

        $accountBeban =  FinanceAccount::with('accountCategory')
        ->where('account_category_id', '!=' ,config('finance_account.default.cash'))
        ->whereHas("accountCategory", function($q){
            $q->where('type' , config('finance_account.type.beban'));
        })
        ->get();

        $beban = '<option></option>';
        foreach($accountBeban as $val){
            $cashName = $val->account_code.' - '.$val->account_name.'('.$val->accountCategory->category_name.')';
            $beban .= '<option value="'.$val->id.'">'.strtoupper($cashName).'</option>';
        };

        return view('accounting::purchase.create')
        ->withProduct($product->optionListWithPurchasePrice())
        ->withAllAccount($accountList)
        ->withBeban($beban)
        ->withTax($tax)
        ->withSupplier($supplier);
    }

    public function storePurchase(Request $request)
    {
        if (auth()->user()->cannot('create pembelian')) {
            return response()->json(['messages' => trans('exceptions.cannot_create')], 401);
        }

        $save = $this->financePurchaseRepository->store($request->input());
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
            $message = __('accounting::alerts.purchase.created');
        }else{
            $status = false;
            $message = trans('accounting::exceptions.purchase.create_error');
        }

        return response()->json(array('status' => $status, 'message' => $message));
    }

    public function showDetail(FinancePurchase $purchase)
    {
        return view('accounting::purchase.show')
        ->withPurchase($purchase);
    }

    public function bayar(FinancePurchase $purchase)
    {
        $cashData = FinanceAccount::where('account_category_id',config('finance_account.default.cash'))->get();
        $cashList = '<option></option>';
        foreach($cashData as $val){
            $accountName = $val->account_code.' - '.$val->account_name;
            $cashList .= '<option value="'.$val->id.'">'.strtoupper($accountName).'</option>';
        }

        return view('accounting::purchase.pay')
        ->withCashList($cashList)
        ->withPurchase($purchase);
    }

    public function printPdf(FinancePurchase $purchase)
    {
        $data = ['purchase' => $purchase];
        $pdf = PDF::loadView('accounting::purchase.print.purchase-pdf', $data);
  
        return $pdf->stream();
    }

    
    public function printHtml(FinancePurchase $purchase){
        return view('accounting::purchase.print.purchase')
                ->withPurchase($purchase);
    }

    public function download(FinancePurchase $purchase)
    {
        return Storage::download('finance_attachment/'.$purchase->financeTrx->attachment_file);
    }

    public function storePayment(Request $request)
    {
        $purchase = FinancePurchase::findOrfail($request->purchase_id);
        $save = $this->financePurchaseRepository->storePay($purchase, $request->input());
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
            $message = __('accounting::alerts.purchase.storePay');
        }else{
            $status = false;
            $message = trans('accounting::exceptions.purchase.storePay_error');
        }  

        return response()->json(array('status' => $status, 'message' => $message, 'data' => $save));
    }

    public function paymentDetail(FinanceTransaction $trx)
    {
        return view('accounting::purchase.payment-detail')
        ->withTrx($trx);
    }
}
